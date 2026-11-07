<?php
/**
 * API Ekstra — endpoint khusus sinkronisasi data siswa untuk aplikasi ekstra.
 *
 * Endpoint:
 *   GET /apiEkstra/siswa?kelas=<nama_rombel>
 *
 * Autentikasi: X-API-KEY (sama dengan endpoint API lainnya, dikelola ApiAuth)
 *
 * Response:
 *   {
 *     "status": "success",
 *     "kelas": "10 DPIB 2",
 *     "data": [
 *       {
 *         "id": 123,
 *         "nis": "1234567890",
 *         "nisn": "0001234567",
 *         "nama": "AHMAD FAUZI",
 *         "jk": "L",
 *         "tgl_lahir": "2010-05-15"
 *       },
 *       ...
 *     ]
 *   }
 *
 * Error 401: API Key tidak valid
 * Error 422: Parameter kelas kosong/tidak valid
 * Error 404: Kelas tidak ditemukan di database
 */
class ApiEkstra extends Controller
{
    public function __construct()
    {
        // CORS headers untuk klien eksternal
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With, X-API-KEY');

        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            http_response_code(200);
            exit;
        }

        header('Content-Type: application/json; charset=utf-8');

        // Proteksi API Key (helper ApiAuth sudah di-load di index.php)
        ApiAuth::authorize();
    }

    /**
     * GET /apiEkstra/siswa?kelas=10%20DPIB%202
     */
    public function siswa()
    {
        $namaKelas = isset($_GET['kelas']) ? trim($_GET['kelas']) : '';

        if ($namaKelas === '') {
            $this->jsonError('Parameter kelas wajib diisi.', 422);
        }

        $model = $this->model('Siswa_model');

        // 1. Cari rombel berdasarkan nama (exact match, case-insensitive)
        $rombel = $model->getRombelByNama($namaKelas);

        if (!$rombel) {
            $this->jsonError("Kelas \"{$namaKelas}\" tidak ditemukan.", 404);
        }

        // 2. Ambil daftar siswa aktif (id_status = 1) dalam rombel tersebut
        $siswaList = $model->getSiswaByRombel($rombel->id_rombel);

        // 3. Format data (kolom yang dibutuhkan aplikasi ekstra)
        $data = array_map(function ($s) {
            $jk = strtoupper(substr(trim((string) $s->jenis_kelamin), 0, 1)); // L / P
            return [
                'id'        => (int) $s->id_induk,
                'nis'       => trim((string) $s->no_induk),
                'nisn'      => ($s->nisn && trim($s->nisn) !== '') ? trim((string) $s->nisn) : null,
                'nama'      => trim((string) $s->nama_siswa),
                'jk'        => in_array($jk, ['L', 'P'], true) ? $jk : null,
                'tgl_lahir' => ($s->tgl_lhr && $s->tgl_lhr !== '0000-00-00') ? substr($s->tgl_lhr, 0, 10) : null,
            ];
        }, $siswaList);

        $this->jsonSuccess([
            'kelas' => $namaKelas,
            'data'  => $data,
        ]);
    }

    // -------------------------------------------------------------------
    // Helper JSON response
    // -------------------------------------------------------------------

    private function jsonSuccess($payload, $status = 200)
    {
        http_response_code($status);
        echo json_encode(array_merge(['status' => 'success'], $payload), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit;
    }

    private function jsonError($message, $status = 400)
    {
        http_response_code($status);
        echo json_encode(['status' => 'error', 'message' => $message], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit;
    }
}
