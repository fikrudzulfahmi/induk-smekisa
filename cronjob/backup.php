<?php
// ==========================================
// 1. KONFIGURASI DATABASE & FILE
// ==========================================
require_once __DIR__ . '/../app/config/config.php';
require_once __DIR__ . '/../app/config/credentials.php';

$dbHost     = DB_HOST;
$dbUser     = DB_USER;
$dbPass     = DB_PASS;
$dbName     = DB_NAME;
$localFile  = __DIR__ . '/temp_backup.sql';

// ==========================================
// 2. KONFIGURASI GOOGLE DRIVE (DENGAN TIMESTAMP)
// ==========================================
$clientId     = G_CLIENT_ID;
$clientSecret = G_CLIENT_SECRET;
$refreshToken = G_REFRESH_TOKEN;
$folderId     = trim(G_FOLDER_ID);

// Menambahkan tanggal dan jam pada nama file
// Contoh hasil: backup_induk_2026-06-25_20-00.sql
$driveName    = 'backup_induk_' . date('Y-m-d_H-i') . '.sql';

// ==========================================
// 3. PROSES EKSPOR DATABASE LOKAL
// ==========================================
$command = "mysqldump -u {$dbUser} -p'{$dbPass}' -h {$dbHost} {$dbName} > {$localFile}";
exec($command, $output, $returnVar);

if ($returnVar !== 0) {
    die("Gagal membuat file backup lokal.\n");
}

// ==========================================
// 4. GET ACCESS TOKEN GOOGLE
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
    die("Gagal mendapatkan Access Token. Periksa credentials.\n");
}
$accessToken = $response['access_token'];

// ==========================================
// 5. CARI & INGAT ID FILE LAMA (MENGGUNAKAN CONTAINS)
// ==========================================
// Mencari file yang mengandung kata 'backup_induk_' agar file kemarin (beda tanggal) tetap terdeteksi
$query = "name contains 'backup_induk_' and '{$folderId}' in parents and trashed=false";
$urlSearch = "https://www.googleapis.com/drive/v3/files?q=" . urlencode($query) . "&fields=files(id,name)";

$chSearch = curl_init($urlSearch);
curl_setopt($chSearch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($chSearch, CURLOPT_HTTPHEADER, ["Authorization: Bearer {$accessToken}"]);
$searchResult = json_decode(curl_exec($chSearch), true);
curl_close($chSearch);

// Simpan daftar ID file lama di memori array
$oldFileIds = [];
if (isset($searchResult['files']) && count($searchResult['files']) > 0) {
    foreach ($searchResult['files'] as $file) {
        $oldFileIds[] = $file['id'];
    }
}

// ==========================================
// 6. UPLOAD FILE BARU (MULTIPART)
// ==========================================
$boundary = "BNDR_" . md5(time());
$metadata = json_encode([
    'name'    => $driveName,
    'parents' => [$folderId]
]);

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
    "Content-Type: multipart/related; boundary=" . $boundary,
    "Content-Length: " . strlen($body)
]);

$uploadResponse = curl_exec($chUpload);
$uploadStatus   = curl_getinfo($chUpload, CURLINFO_HTTP_CODE);
curl_close($chUpload);

$uploadResult = json_decode($uploadResponse, true);

// ==========================================
// 7. EVALUASI HASIL & EKSEKUSI HAPUS LAMA
// ==========================================
$statusLog = 'gagal';
$keteranganLog = 'Gagal mengunggah file baru ke Google Drive.';

// JIKA UPLOAD SUKSES
if ($uploadStatus === 200 && isset($uploadResult['id'])) {
    echo "Sukses! Backup baru ({$driveName}) terunggah. ID: " . $uploadResult['id'] . "\n";
    $statusLog = 'sukses';
    $keteranganLog = "Backup berhasil diunggah: {$driveName}";

    // BARULAH KITA HAPUS FILE LAMANYA DI SINI
    if (!empty($oldFileIds)) {
        foreach ($oldFileIds as $oldId) {
            $chDel = curl_init("https://www.googleapis.com/drive/v3/files/{$oldId}");
            curl_setopt($chDel, CURLOPT_CUSTOMREQUEST, 'DELETE');
            curl_setopt($chDel, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($chDel, CURLOPT_HTTPHEADER, ["Authorization: Bearer {$accessToken}"]);
            curl_exec($chDel);
            curl_close($chDel);
            echo "File lama (ID: {$oldId}) sukses dihapus untuk me-replace.\n";
        }
    }
}
// JIKA UPLOAD GAGAL
else {
    echo "Gagal mengunggah file baru ke Google Drive.\n";
    if ($uploadStatus !== 200) {
        $keteranganLog = "Gagal Drive API (Status: $uploadStatus). File lama TETAP AMAN.";
    }
    echo "Pesan: " . $uploadResponse . "\n";
}

// Hapus file SQL sementara di server lokal
if (file_exists($localFile)) {
    unlink($localFile);
}

// ==========================================
// 8. CATAT LOG KE DATABASE
// ==========================================
try {
    $dsn = "mysql:host={$dbHost};dbname={$dbName};charset=utf8mb4";
    $pdo = new PDO($dsn, $dbUser, $dbPass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

    $sql = "REPLACE INTO backup_status (id, keterangan, status, created_at) 
            VALUES (1, :keterangan, :status, NOW())";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':keterangan' => $keteranganLog,
        ':status'     => $statusLog
    ]);
} catch (PDOException $e) {
    echo "Gagal mencatat status ke database: " . $e->getMessage() . "\n";
}
