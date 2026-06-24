<?php
// ==========================================
// 1. KONFIGURASI DATABASE & FILE
// ==========================================
require_once __DIR__ . '/../app/config/config.php';
// PERBAIKAN: Path disesuaikan karena credentials.php ada di dalam folder cronjob yang sama
require_once __DIR__ . '/../app/config/credentials.php';

// Masukkan konstanta dari config.php ke variabel backup
$dbHost     = DB_HOST;
$dbUser     = DB_USER;
$dbPass     = DB_PASS;
$dbName     = DB_NAME;
$localFile  = __DIR__ . '/temp_backup.sql'; // File sementara tetap di folder cronjob

// ==========================================
// 2. KONFIGURASI GOOGLE DRIVE
// ==========================================
$clientId     = G_CLIENT_ID;
$clientSecret = G_CLIENT_SECRET;
$refreshToken = G_REFRESH_TOKEN;
$folderId     = G_FOLDER_ID;
$driveName    = 'backup_induk.sql';


// ==========================================
// 3. PROSES EKSPOR DATABASE (CIRI KHAS NATIVE)
// ==========================================
$command = "mysqldump -u {$dbUser} -p'{$dbPass}' -h {$dbHost} {$dbName} > {$localFile}";
exec($command, $output, $returnVar);

if ($returnVar !== 0) {
    die("Gagal membuat file backup lokal.");
}

// ==========================================
// 4. GET ACCESS TOKEN (GENERATED OTOMATIS)
// ==========================================
$ch = curl_init("https://oauth2.googleapis.com/token");
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
    'client_id'     => $clientId,
    'client_secret' => $clientSecret,
    'refresh_token' => $refreshToken,
    'grant_type'    => 'refresh_token'
]));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = json_decode(curl_exec($ch), true);
curl_close($ch);

if (!isset($response['access_token'])) {
    die("Gagal mendapatkan Access Token dari Google. Periksa Client ID/Secret/Refresh Token kamu.\n");
}
$accessToken = $response['access_token'];

// ==========================================
// 5. CARI & HAPUS FILE LAMA DI DRIVE
// ==========================================
$query = urlencode("name='{$driveName}' and '{$folderId}' in parents and trashed=false");
$ch = curl_init("https://www.googleapis.com/drive/v3/files?q={$query}");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ["Authorization: Bearer {$accessToken}"]);
$searchResult = json_decode(curl_exec($ch), true);
curl_close($ch);

// Jika file lama ketemu, hapus!
if (!empty($searchResult['files'])) {
    foreach ($searchResult['files'] as $file) {
        $fileId = $file['id'];
        $chDel = curl_init("https://www.googleapis.com/drive/v3/files/{$fileId}");
        curl_setopt($chDel, CURLOPT_CUSTOMREQUEST, 'DELETE');
        curl_setopt($chDel, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($chDel, CURLOPT_HTTPHEADER, ["Authorization: Bearer {$accessToken}"]);
        curl_exec($chDel);
        curl_close($chDel);
        echo "File lama dengan ID {$fileId} berhasil dihapus dari Google Drive.\n";
    }
}

// ==========================================
// 6. UPLOAD FILE BARU (MULTIPART cURL)
// ==========================================
// KUNCI PERBAIKAN: Gunakan string boundary yang rapat dan bersih
$boundary = "BNDR_" . md5(time());

$metadata = json_encode([
    'name'    => $driveName,
    'parents' => [$folderId]
]);

// Pastikan susunan baris dan \r\n di bawah ini tidak ada spasi liar
$body = "--" . $boundary . "\r\n" .
    "Content-Type: application/json; charset=UTF-8\r\n\r\n" .
    $metadata . "\r\n" .
    "--" . $boundary . "\r\n" .
    "Content-Type: application/x-sql\r\n\r\n" .
    file_get_contents($localFile) . "\r\n" .
    "--" . $boundary . "--";

$chUpload = curl_init("https://www.googleapis.com/upload/drive/v3/files?uploadType=multipart");
curl_setopt($chUpload, CURLOPT_POST, true);
curl_setopt($chUpload, CURLOPT_RETURNTRANSFER, true);
curl_setopt($chUpload, CURLOPT_POSTFIELDS, $body);
curl_setopt($chUpload, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer {$accessToken}",
    "Content-Type: multipart/related; boundary=" . $boundary, // Di sini disinkronkan rapat
    "Content-Length: " . strlen($body)
]);

$uploadResponse = curl_exec($chUpload);
$uploadStatus   = curl_getinfo($chUpload, CURLINFO_HTTP_CODE);
curl_close($chUpload);

$uploadResult = json_decode($uploadResponse, true);

// ==========================================
// 7. BERSIHKAN FILE TEMPORER DI SERVER & LIHAT STATUS
// ==========================================
if (file_exists($localFile)) {
    unlink($localFile);
}

// Siapkan variabel untuk pencatatan database
$statusLog = 'gagal';
$keteranganLog = 'Gagal mengunggah file baru ke Google Drive.';

if ($uploadStatus === 200 && isset($uploadResult['id'])) {
    echo "Sukses! Backup baru berhasil diunggah. ID File Drive: " . $uploadResult['id'] . "\n";
    $statusLog = 'sukses';
    $keteranganLog = "Backup berhasil diunggah dengan ID: " . $uploadResult['id'];
} else {
    echo "Gagal mengunggah file baru ke Google Drive.\n";
    echo "HTTP Status Code: " . $uploadStatus . "\n";
    echo "Response Lengkap dari Google:\n" . $uploadResponse . "\n";

    // Jika gagal sebelum upload (misal mysqldump atau token error)
    if (isset($response['error'])) {
        $keteranganLog = "Gagal OAuth: " . $response['error'];
    } elseif ($uploadStatus !== 200) {
        $keteranganLog = "Gagal Drive API (Status: $uploadStatus)";
    }
}

// ==========================================
// BONUS: CATAT LOG KE DATABASE (REPLACE DATA)
// ==========================================
try {
    // Hubungkan ke database menggunakan data dari config.php
    $dsn = "mysql:host={$dbHost};dbname={$dbName};charset=utf8mb4";
    $pdo = new PDO($dsn, $dbUser, $dbPass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    // Gunakan REPLACE INTO agar data lama dengan ID 1 otomatis tertimpa data baru
    $sql = "REPLACE INTO backup_status (id, keterangan, status, created_at) 
            VALUES (1, :keterangan, :status, NOW())";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':keterangan' => $keteranganLog,
        ':status'     => $statusLog
    ]);

    echo "Status backup berhasil dicatat ke database.\n";
} catch (PDOException $e) {
    echo "Gagal mencatat status backup ke database: " . $e->getMessage() . "\n";
}
