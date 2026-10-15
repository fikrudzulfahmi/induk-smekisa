<?php
/**
 * API Data Induk untuk TSM Superapp (read-only, JSON).
 *
 *   GET /api/kelas                 daftar rombel           (?jurusan=TSM untuk menyaring)
 *   GET /api/siswa/{id_rombel}     siswa aktif satu rombel
 *
 * Wajib header:  Authorization: Bearer <token>   (atau X-Api-Token: <token>)
 * Token: define('INDUK_API_TOKEN', '...') di app/config/config.php (tidak di-commit) bila ingin mengganti.
 * Bila tidak didefinisikan, dipakai hash bawaan di bawah (repo publik: JANGAN simpan token asli di kode).
 */
class Api extends Controller
{
    // bcrypt dari token bawaan yang disepakati dengan TSM Superapp
    const TOKEN_HASH = '$2y$11$kY/8uqvADSQCrQSA2lHpYuEP8S/cdd5Q6wHkQ4ucpC/9ua1C2o30u';

    public function __construct()
    {
        // API tidak butuh session; tutup agar tidak menulis/mengunci file session
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_write_close();
        }
        header('Content-Type: application/json; charset=utf-8');
        header('X-Content-Type-Options: nosniff');
        header('Cache-Control: no-store');

        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            $this->json(['message' => 'Method tidak diizinkan.'], 405);
        }
        $this->cekToken();
    }

    public function index()
    {
        $this->json(['message' => 'API Data Induk', 'endpoints' => ['/api/kelas', '/api/siswa/{id_rombel}']]);
    }

    public function kelas()
    {
        try {
            $rows = $this->model('Api_model')->getKelas($_GET['jurusan'] ?? null);
        } catch (\Throwable $e) {
            $this->json(['message' => 'Gagal membaca data kelas.'], 500);
        }
        $data = array_map(function ($r) {
            return [
                'id'           => (int) $r->id_rombel,
                'nama'         => $r->nama_rombel,
                'tingkat'      => $r->tingkat,
                'jurusan'      => $r->jurusan,
                'jumlah_siswa' => (int) $r->jumlah_siswa,
            ];
        }, $rows);
        $this->json(['data' => $data]);
    }

    public function siswa($idRombel = null)
    {
        if (!ctype_digit((string) $idRombel)) {
            $this->json(['message' => 'ID kelas tidak valid.'], 422);
        }
        $model = $this->model('Api_model');
        try {
            $kelas = $model->getKelasById($idRombel);
            if (!$kelas) {
                $this->json(['message' => 'Kelas tidak ditemukan.'], 404);
            }
            $rows = $model->getSiswaByKelas($idRombel);
        } catch (\Throwable $e) {
            $this->json(['message' => 'Gagal membaca data siswa.'], 500);
        }
        $data = array_map(function ($s) {
            $jk = strtoupper(substr(trim((string) $s->jenis_kelamin), 0, 1)); // "Laki-Laki" -> L, "Perempuan" -> P
            return [
                'id'        => (int) $s->id_induk,
                'nis'       => trim((string) $s->no_induk),
                'nisn'      => $s->nisn !== null ? trim((string) $s->nisn) : null,
                'nama'      => trim((string) $s->nama_siswa),
                'jk'        => in_array($jk, ['L', 'P'], true) ? $jk : null,
                'tgl_lahir' => ($s->tgl_lhr && $s->tgl_lhr !== '0000-00-00') ? substr($s->tgl_lhr, 0, 10) : null,
                'status'    => ((int) $s->id_status === 1) ? 'aktif' : strtolower((string) $s->status),
            ];
        }, $rows);
        $this->json([
            'kelas' => ['id' => (int) $kelas->id_rombel, 'nama' => $kelas->nama_rombel, 'tingkat' => $kelas->tingkat, 'jurusan' => $kelas->jurusan],
            'data'  => $data,
        ]);
    }

    // ------------------------------------------------------------------

    private function cekToken()
    {
        $header = $_SERVER['HTTP_AUTHORIZATION'] ?? $_SERVER['REDIRECT_HTTP_AUTHORIZATION'] ?? '';
        if ($header === '' && function_exists('getallheaders')) {
            foreach (getallheaders() as $k => $v) {
                if (strtolower($k) === 'authorization') { $header = $v; break; }
            }
        }
        $token = '';
        if (stripos($header, 'Bearer ') === 0) {
            $token = trim(substr($header, 7));
        } elseif (!empty($_SERVER['HTTP_X_API_TOKEN'])) {
            $token = trim($_SERVER['HTTP_X_API_TOKEN']);
        }
        $valid = $token !== '' && strlen($token) <= 200 && (defined('INDUK_API_TOKEN') && INDUK_API_TOKEN !== ''
            ? hash_equals((string) INDUK_API_TOKEN, $token)
            : password_verify($token, self::TOKEN_HASH));
        if (!$valid) {
            usleep(300000); // perlambat tebakan token
            $this->json(['message' => 'Token tidak valid.'], 401);
        }
    }

    private function json($payload, $status = 200)
    {
        http_response_code($status);
        echo json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit;
    }
}
