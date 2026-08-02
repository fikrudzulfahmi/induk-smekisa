<?php

class Backup extends Controller
{
    public function __construct()
    {
        if (!isset($_SESSION['login_guru'])) {
            header('Location: ' . BASEURL . '/guru/login');
            exit;
        }
        // Asumsi class Auth sudah di-include di Controller atau core.
        if (class_exists('Auth') && !Auth::checkRole('admin')) {
            echo "<script>alert('Akses Ditolak! Anda bukan admin.'); window.history.back();</script>";
            exit;
        }
    }

    public function index()
    {
        $data['judul'] = 'Backup & Restore Database';
        
        $data['guru'] = $this->model('Guru_model')->getGuruById($_SESSION['guru_id']);
        
        // Cek riwayat backup terakhir dari database
        try {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
            $pdo = new PDO($dsn, DB_USER, DB_PASS, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
            $stmt = $pdo->query("SELECT * FROM backup_status ORDER BY created_at DESC LIMIT 1");
            $data['last_backup'] = $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $data['last_backup'] = null;
        }

        $this->view('backup/index', $data);
    }

    public function download()
    {
        $dbHost = DB_HOST;
        $dbUser = DB_USER;
        $dbPass = DB_PASS;
        $dbName = DB_NAME;
        
        date_default_timezone_set('Asia/Jakarta');
        $filename = 'backup_induk_manual_' . date('Y-m-d_H-i-s') . '.sql';
        
        // Simpan sementara di sistem sementara server (sys_get_temp_dir)
        $localFile = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $filename;

        // Jika menggunakan password, formatnya -p'password'. Jika kosong, kosongkan.
        $passStr = empty($dbPass) ? "" : "-p'{$dbPass}'";
        
        // Execute mysqldump
        $command = "mysqldump -u {$dbUser} {$passStr} -h {$dbHost} {$dbName} > {$localFile}";
        exec($command, $output, $returnVar);

        if ($returnVar !== 0) {
            echo "<script>alert('Gagal membuat file backup lokal via mysqldump.'); window.location.href='".BASEURL."/backup';</script>";
            exit;
        }

        if (file_exists($localFile)) {
            $this->logActivity('Download Backup', 'Admin mengunduh file backup database secara manual: ' . $filename);
            
            header('Content-Description: File Transfer');
            header('Content-Type: application/sql');
            header('Content-Disposition: attachment; filename="' . basename($localFile) . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($localFile));
            readfile($localFile);
            
            // Hapus file sementara setelah didownload
            unlink($localFile);
            exit;
        } else {
            echo "<script>alert('File backup gagal ditemukan setelah di-generate.'); window.location.href='".BASEURL."/backup';</script>";
            exit;
        }
    }

    public function triggerDrive()
    {
        $scriptPath = realpath(__DIR__ . '/../../cronjob/backup.php');
        
        if ($scriptPath && file_exists($scriptPath)) {
            // Jalankan via PHP CLI secara sinkron agar bisa dapat balikan status
            exec("php " . escapeshellarg($scriptPath), $output, $returnVar);
            
            $outputStr = implode("\\n", $output);
            $outputStr = addslashes($outputStr); // Escape untuk JS alert

            $this->logActivity('Trigger Auto-Backup', 'Admin memicu cronjob backup secara manual.');
            echo "<script>alert('Hasil Eksekusi:\\n" . $outputStr . "'); window.location.href='".BASEURL."/backup';</script>";
        } else {
            echo "<script>alert('File script backup cronjob tidak ditemukan.'); window.location.href='".BASEURL."/backup';</script>";
        }
        exit;
    }

    public function restore()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['backup_file'])) {
            $file = $_FILES['backup_file'];
            
            if ($file['error'] !== UPLOAD_ERR_OK) {
                echo "<script>alert('Gagal mengunggah file.'); window.location.href='".BASEURL."/backup';</script>";
                exit;
            }

            // Validasi ekstensi
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            if (strtolower($ext) !== 'sql') {
                echo "<script>alert('File harus berformat .sql'); window.location.href='".BASEURL."/backup';</script>";
                exit;
            }

            $sqlContent = file_get_contents($file['tmp_name']);
            if (empty(trim($sqlContent))) {
                echo "<script>alert('File SQL kosong.'); window.location.href='".BASEURL."/backup';</script>";
                exit;
            }

            try {
                $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
                $pdo = new PDO($dsn, DB_USER, DB_PASS, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
                
                // Eksekusi skrip SQL
                $pdo->exec($sqlContent);
                
                $this->logActivity('Restore Database', 'Admin merestore database dari file: ' . $file['name']);
                echo "<script>alert('Database berhasil direstore!'); window.location.href='".BASEURL."/backup';</script>";
            } catch (PDOException $e) {
                $errorMsg = addslashes($e->getMessage());
                echo "<script>alert('Gagal restore database: $errorMsg'); window.location.href='".BASEURL."/backup';</script>";
            }
        } else {
            header('Location: ' . BASEURL . '/backup');
        }
        exit;
    }
}
