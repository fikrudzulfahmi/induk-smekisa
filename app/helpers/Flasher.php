<?php

class Flasher
{
    // Method untuk mengatur pesan flash
    public static function setFlash($pesan, $aksi, $tipe)
    {
        $_SESSION['flash'] = [
            'pesan' => $pesan,
            'aksi'  => $aksi,
            'tipe'  => $tipe // 'success', 'error', 'warning'
        ];
    }

    // Method untuk menampilkan pesan flash
    public static function flash()
    {
        if (isset($_SESSION['flash'])) {
            echo "
            <script>
                // Menunggu dokumen siap sebelum menampilkan alert
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        title: 'Data " . $_SESSION['flash']['pesan'] . "',
                        text: '" . $_SESSION['flash']['aksi'] . "',
                        icon: '" . $_SESSION['flash']['tipe'] . "',
                        timer: 2000,
                        showConfirmButton: false
                    });
                });
            </script>
            ";
            // Hapus session setelah ditampilkan agar tidak muncul lagi
            unset($_SESSION['flash']);
        }
    }
}
