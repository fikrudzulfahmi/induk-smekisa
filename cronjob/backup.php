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
// 2. KONFIGURASI GOOGLE DRIVE WEBHOOK
// ==========================================
$webhookUrl = defined('G_WEBHOOK_URL') ? G_WEBHOOK_URL : '';
$secretKey  = defined('G_SECRET_KEY') ? G_SECRET_KEY : 'TUsmekisa1968';

date_default_timezone_set('Asia/Jakarta');
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
// 4. UPLOAD FILE KE WEBHOOK (GOOGLE SCRIPT)
// ==========================================
$sqlScript = file_get_contents($localFile);
$base64Data = base64_encode($sqlScript);

$postData = http_build_query([
    'secret'      => $secretKey,
    'filename'    => $driveName,
    'file_base64' => $base64Data
]);

if (empty($webhookUrl)) {
    die("Gagal: URL Webhook (G_WEBHOOK_URL) belum diatur di credentials.php.\n");
}

$ch = curl_init($webhookUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
curl_setopt($ch, CURLOPT_TIMEOUT, 300); // Timeout 5 menit untuk backup besar

$response = curl_exec($ch);
$httpStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

$resData = json_decode($response, true);

// ==========================================
// 5. EVALUASI HASIL & CATAT LOG
// ==========================================
$statusLog = 'gagal';
$keteranganLog = 'Gagal mengunggah file baru ke Webhook Google Drive.';

if ($httpStatus == 200 && $resData && isset($resData['status']) && $resData['status'] === 'success') {
    echo "Sukses! Backup baru ({$driveName}) terunggah.\n";
    if (!empty($resData['deleted'])) {
        echo "File lama yang dihapus: " . implode(", ", $resData['deleted']) . "\n";
    }
    $statusLog = 'sukses';
    $keteranganLog = "Backup berhasil diunggah: {$driveName}";
} else {
    echo "Gagal mengunggah file baru ke Google Drive via Webhook.\n";
    $errorMsg = $resData['message'] ?? $response;
    $keteranganLog = "Gagal via Webhook: " . substr($errorMsg, 0, 150);
    echo "Pesan: " . $errorMsg . "\n";
}

// Hapus file SQL sementara di server lokal
if (file_exists($localFile)) {
    unlink($localFile);
}

// ==========================================
// 6. CATAT LOG KE DATABASE
// ==========================================
try {
    $dsn = "mysql:host={$dbHost};dbname={$dbName};charset=utf8mb4";
    $pdo = new PDO($dsn, $dbUser, $dbPass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

    $sql = "REPLACE INTO backup_status (id, keterangan, status, created_at) 
            VALUES (1, :keterangan, :status, :waktu)";
    $stmt = $pdo->prepare($sql);

    // Masukkan waktu saat ini menggunakan fungsi date() PHP
    $stmt->execute([
        ':keterangan' => $keteranganLog,
        ':status'     => $statusLog,
        ':waktu'      => date('Y-m-d H:i:s')
    ]);
    
    echo "Status log berhasil dicatat ke database.\n";
} catch (PDOException $e) {
    echo "Gagal mencatat status ke database: " . $e->getMessage() . "\n";
}
