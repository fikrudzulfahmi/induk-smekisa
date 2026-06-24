<?php
// ==========================================
// 1. KONFIGURASI DATABASE & FILE
// ==========================================
require_once __DIR__ . '/../app/config/config.php';

// Masukkan konstanta dari config.php ke variabel backup
$dbHost     = DB_HOST;
$dbUser     = DB_USER;
$dbPass     = DB_PASS;
$dbName     = DB_NAME;
$localFile  = __DIR__ . '/temp_backup.sql'; // File sementara tetap di folder cronjob

// ==========================================
// 2. KONFIGURASI GOOGLE DRIVE
// ==========================================
$clientId     = '860643794750-9f0bboljrv903a57iib8nnktni3ksuen.apps.googleusercontent.com';
$clientSecret = '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';
$refreshToken = '1//046mfSvc5uRZjCgYIARAAGAQSNwF-L9IrFcCDafHQhxtyAa3Eo57zAOyZF5OzV-3rJADr53tDqkmXcDUp2-ReP0wi92fnT_7NWeE';
$folderId     = '1j4Zi1TxnUiOdxW-l_ZdNTXZZvszJfjLU';
$driveName    = 'backup_induk.sql'; // Nama file tetap agar bisa di-replace

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
    die("Gagal mendapatkan Access Token dari Google.");
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
        echo "File lama dengan ID {$fileId} berhasil dihapus.\n";
    }
}

// ==========================================
// 6. UPLOAD FILE BARU (MULTIPART cURL)
// ==========================================
$boundary = "===" . time() . "===";
$metadata = json_encode([
    'name'    => $driveName,
    'parents' => [$folderId]
]);

$delimiter = "外部\r\n--" . $boundary . "\r\n";
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
    "Content-Type: multipart/related; boundary={$boundary}",
    "Content-Length: " . strlen($body)
]);

$uploadResult = json_decode(curl_exec($chUpload), true);
curl_close($chUpload);

// ==========================================
// 7. BERSIHKAN FILE TEMPORER DI SERVER
// ==========================================
if (file_exists($localFile)) {
    unlink($localFile);
}

if (isset($uploadResult['id'])) {
    echo "Sukses! Backup baru berhasil diunggah. ID: " . $uploadResult['id'] . "\n";
} else {
    echo "Gagal mengunggah file baru ke Google Drive.\n";
}
