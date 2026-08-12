-- ============================================================
-- MIGRASI: Pindahkan setting Tahun Pelajaran ke Profil Sekolah
-- ============================================================
-- Jalankan file ini di phpMyAdmin (atau terminal MySQL) pada database
-- `ingintau_induk` di hosting, SEKALI saja, sebelum menguji fitur baru.
--
-- Setelah migrasi, tahun pelajaran di-set dari menu Profil Sekolah,
-- bukan lagi diisi manual lewat database.
-- ============================================================

ALTER TABLE `profil_sekolah`
    ADD COLUMN `tahun_pelajaran` VARCHAR(20) NULL DEFAULT NULL
    COMMENT 'Tahun pelajaran aktif, contoh: 2025/2026'
    AFTER `versi_erapor`;

-- (Opsional) Salin nilai tahun pelajaran aktif yang sudah ada dari tabel tp lama,
-- supaya tidak perlu mengisi ulang. Hapus baris ini jika tabel `tp` tidak ada
-- atau ingin mengisi manual lewat form Profil Sekolah.
UPDATE `profil_sekolah` ps
SET ps.`tahun_pelajaran` = (
    SELECT tp.`tp` FROM `tp` tp WHERE tp.`status` = 'Aktif' LIMIT 1
)
WHERE ps.`id` = 1 AND ps.`tahun_pelajaran` IS NULL;
