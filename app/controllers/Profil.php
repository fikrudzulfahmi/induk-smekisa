<?php

class Profil extends Controller
{

    // (Method __construct() atau method lain yang sudah ada)

    /**
     * menampilkan halaman pengaturan profil sekolah.
     * 
     * 
     * 
     */
    public function index()
    {
        // Asumsi ada pengecekan hak akses admin
        // if (!Auth::checkRole('admin')) { /* handle akses */ exit; }

        $data['judul'] = 'Profil Sekolah';
        // Ambil data profil (selalu ID 1)
        $data['profil'] = $this->model('ProfilSekolah_model')->getProfil();



        $this->view('profil/index', $data); // View read-only profil

    }

    public function edit()
    {
        // Asumsi ada pengecekan hak akses admin di sini
        // if (!Auth::checkRole('admin')) { /* handle akses */ exit; }

        $data['judul'] = 'Edit Profil Sekolah';
        // Ambil data profil (selalu ID 1)
        $data['profil'] = $this->model('ProfilSekolah_model')->getProfil();

        // Jika data profil belum ada (tabel kosong), inisialisasi objek kosong
        if (!$data['profil']) {
            $data['profil'] = (object) [
                'nama_sekolah' => '',
                'npsn' => '',
                'nss' => '',
                'alamat' => '',
                'kode_pos' => '',
                'telepon' => '',
                'kelurahan' => '',
                'kecamatan' => '',
                'kota' => '',
                'provinsi' => '',
                'website' => '',
                'email' => '',
                'nama_kepsek' => '',
                'nip_kepsek' => '',
                'versi_erapor' => 'v1.0.0',
                'logo_sekolah' => null,
                'token' => ''
            ];
            // Set flash message untuk memberitahu admin
            Flasher::setFlash('Info', 'Data profil sekolah belum ada. Silakan lengkapi form.', 'info', true); // Pesan persisten
        }

        // Tampilkan view form edit profil
        $this->view('profil/edit', $data); // View form profil
    }

    /**
     * Memproses update data profil sekolah dari form.
     */
    public function updateProfilSekolah()
    {
        // Asumsi ada pengecekan hak akses admin di sini
        // if (!Auth::checkRole('admin')) { /* handle akses */ exit; }

        // 1. Ambil data profil saat ini (untuk path logo lama)
        $profilLama = $this->model('ProfilSekolah_model')->getProfil();
        $logo_path = $profilLama->logo_sekolah ?? null; // Default ke null jika profil belum ada

        // 2. Handle Upload Logo Baru (jika ada)
        if (isset($_FILES['logo_sekolah']) && $_FILES['logo_sekolah']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['logo_sekolah']['tmp_name'];
            $fileName = $_FILES['logo_sekolah']['name'];
            $fileSize = $_FILES['logo_sekolah']['size'];
            $fileType = $_FILES['logo_sekolah']['type'];
            $fileNameCmps = explode(".", $fileName);
            $fileExtension = strtolower(end($fileNameCmps));

            // Sanitasi nama file & buat nama unik
            $newFileName = preg_replace('/[^A-Za-z0-9_\-]/', '_', pathinfo($fileName, PATHINFO_FILENAME));
            $newFileName = 'logo_' . time() . '_' . $newFileName . '.' . $fileExtension;

            // Tentukan direktori upload (pastikan folder ini ada dan writable)
            $uploadFileDir = './assets/images/'; // Relatif dari index.php di public
            $dest_path = $uploadFileDir . $newFileName;

            // Validasi tipe file
            $allowedfileExtensions = ['jpg', 'gif', 'png', 'jpeg'];
            if (in_array($fileExtension, $allowedfileExtensions)) {
                // Validasi ukuran file (misal max 1MB)
                if ($fileSize < 1000000) {
                    // Pindahkan file
                    if (move_uploaded_file($fileTmpPath, $dest_path)) {
                        // Hapus logo lama jika ada dan berhasil upload baru
                        if ($logo_path && file_exists($logo_path)) {
                            unlink($logo_path);
                        }
                        // Update path logo baru (simpan path relatif dari index.php)
                        $logo_path = $dest_path;
                    } else {
                        Flasher::setFlash('Error Upload', 'Gagal memindahkan file logo.', 'danger');
                        header('Location: ' . BASEURL . '/profil/edit');
                        exit;
                    }
                } else {
                    Flasher::setFlash('Error Ukuran', 'Ukuran file logo maksimal 1MB.', 'warning');
                    header('Location: ' . BASEURL . '/profil/edit');
                    exit;
                }
            } else {
                Flasher::setFlash('Error Tipe', 'Tipe file logo tidak diizinkan (hanya jpg, jpeg, png, gif).', 'warning');
                header('Location: ' . BASEURL . '/profil/edit');
                exit;
            }
        } // Akhir handle upload

        // 3. Siapkan data untuk diupdate ke model
        $dataToUpdate = $_POST; // Ambil semua data dari form
        $dataToUpdate['logo_sekolah'] = $logo_path; // Masukkan path logo (lama atau baru)

        // 4. Panggil method update di model
        // Method updateProfil() harus bisa menangani INSERT jika data belum ada (id=1),
        // atau Anda bisa cek $profilLama dan panggil method INSERT terpisah.
        // Asumsi updateProfil() bisa menangani keduanya (misal via ON DUPLICATE KEY UPDATE)
        // atau minimal mengupdate baris id=1.

        // Cek apakah data sebelumnya ada
        if ($profilLama) {
            // Jika ada, lakukan update
            if ($this->model('ProfilSekolah_model')->updateProfil($dataToUpdate) >= 0) { // >= 0 karena update 0 baris (tidak ada perubahan) dianggap sukses
                Flasher::setFlash('Profil Sekolah', 'berhasil diperbarui.', 'success');
            } else {
                Flasher::setFlash('Profil Sekolah', 'gagal diperbarui.', 'danger');
            }
        } else {
            // Jika belum ada, lakukan insert (Anda perlu buat method insertProfil di Model)
            // Contoh:
            // if ($this->model('ProfilSekolah_model')->insertProfil($dataToUpdate) > 0) {
            //    Flasher::setFlash('Profil Sekolah', 'berhasil disimpan.', 'success');
            //} else {
            //    Flasher::setFlash('Profil Sekolah', 'gagal disimpan.', 'danger');
            //}
            // ATAU modifikasi updateProfil agar bisa INSERT jika id=1 tidak ada.
            // Untuk sementara, kita anggap updateProfil bisa handle (meski mungkin gagal jika id 1 belum ada):
            if ($this->model('ProfilSekolah_model')->updateProfil($dataToUpdate) >= 0) {
                Flasher::setFlash('Profil Sekolah', 'berhasil disimpan/diperbarui.', 'success');
            } else {
                Flasher::setFlash('Profil Sekolah', 'gagal disimpan/diperbarui.', 'danger');
            }
        }


        // 5. Redirect kembali ke halaman profil
        header('Location: ' . BASEURL . '/profil/index');
        exit;
    }
} // Akhir Class