<?php require_once '../app/views/templates/header.php'; ?>
<?php require_once '../app/views/templates/sidebar.php'; ?>

<div id="main">
    <div class="page-heading">
        <h3>Daftar Anggota Rombel: <?= htmlspecialchars($data['rombel']->nama_rombel); ?></h3>
        <p class="text-subtitle text-muted">Total Anggota: <?= count($data['siswa']); ?> siswa</p>
    </div>
    <div class="page-content">
        <div class="card">
            <div class="card-header">
                <a href="<?= BASEURL; ?>/rombel" class="btn btn-secondary">Kembali</a>

                <?php // Cek apakah pengguna login DAN (apakah dia admin ATAU walas kelas ini)
                if (
                    isset($_SESSION['guru_id']) &&
                    (Auth::checkRole('admin') || Auth::checkRole('waka') || $_SESSION['guru_id'] == $data['rombel']->id_walas)
                ) :
                ?>
                    <div class="float-end">
                        <?php if (Auth::checkRole('admin')) : ?>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalImportSiswa">
                                <i class="bi bi-file-earmark-excel"></i> Import Siswa (Excel)
                            </button>
                        <?php endif; ?>
                        <a href="<?= BASEURL; ?>/rombel/exportAnggotaExcel/<?= $data['rombel']->id_rombel; ?>" class="btn btn-success" target="_blank">
                            <i class="bi bi-file-earmark-excel-fill"></i> Export Daftar Siswa (Excel)
                        </a>
                    </div>
                <?php endif; ?>
            </div>
            <div class="card-body">
                <table class="table table-striped" id="tabel-anggota">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Nama Siswa</th>
                            <th>No Induk</th>
                            <th>Jenis Kelamin</th>
                            <?php if (Auth::checkRole('admin')) : ?>
                                <th>Aksi</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1;
                        foreach ($data['siswa'] as $siswa) : ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td><?= htmlspecialchars($siswa->nama_siswa); ?></td>
                                <td><?= htmlspecialchars($siswa->no_induk); ?></td>
                                <td><?= htmlspecialchars($siswa->jenis_kelamin); ?></td>

                                <?php if (Auth::checkRole('admin')) : ?>
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-sm btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" title="Aksi Lainnya">
                                                <i class="bi bi-three-dots-vertical">... Aksi Lainnya</i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-custom">
                                                <a href="<?= BASEURL; ?>/siswa/edit/<?= $siswa->id_induk; ?>" class="dropdown-item" title="Edit Data Siswa">
                                                    <i class="bi bi-pencil-fill"></i> Edit
                                                </a>
                                                <a href="<?= BASEURL; ?>/siswa/cetakBukuInduk/<?= $siswa->id_induk; ?>" target="_blank" class="dropdown-item" title="Cetak Buku Induk">
                                                    <i class="bi bi-filetype-pdf"></i> Cetak Induk
                                                </a>
                                                <a href="<?= BASEURL; ?>/siswa/cetakCoverRaport/<?= $siswa->id_induk; ?>" target="_blank" class="dropdown-item" title="Cetak Cover Rapor">
                                                    <i class="bi bi-file-pdf-fill"></i> Cetak Cover Rapor
                                                </a>
                                            </ul>
                                        </div>
                                    </td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php if (Auth::checkRole('admin')) : ?>
    <div class="modal fade" id="modalImportSiswa" tabindex="-1" aria-labelledby="modalImportSiswaLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalImportSiswaLabel">Import Siswa ke Kelas <?= htmlspecialchars($data['rombel']->nama_rombel); ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="<?= BASEURL; ?>/siswa/importExcel" method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <input type="hidden" name="id_rombel" value="<?= $data['rombel']->id_rombel; ?>">
                        <input type="hidden" name="id_komp_keahlian" value="<?= $data['rombel']->id_jurusan; ?>">

                        <div class="alert alert-warning" role="alert">
                            <h6 class="alert-heading"><i class="bi bi-exclamation-triangle-fill"></i> Petunjuk Import:</h6>
                            <ul class="mb-2 s-14" style="padding-left: 20px;">
                                <li>Gunakan file berformat berkas <b>.xlsx</b> atau <b>.xls</b>.</li>
                                <li>Sistem akan mendeteksi isi Rombel dan Kompetensi Keahlian secara otomatis.</li>
                                <li>Tipe data tanggal wajib diatur sebagai format "Text" di Excel dengan pola <code>YYYY-MM-DD</code>.</li>
                            </ul>
                            <hr>
                            <p class="mb-0 s-14">Belum punya template? Unduh di bawah ini:</p>
                            <a href="<?= BASEURL; ?>/siswa/downloadTemplate" class="btn btn-sm btn-primary mt-2">
                                <i class="bi bi-file-earmark-excel"></i> Download Template Excel
                            </a>
                        </div>

                        <div class="mb-3">
                            <label for="file_excel" class="form-label font-bold">Pilih Berkas Excel Template</label>
                            <input class="form-control" type="file" id="file_excel" name="file_excel" accept=".xlsx, .xls" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success">Mulai Proses Import</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php require_once '../app/views/templates/footer.php'; ?>

<script>
    $(document).ready(function() {
        $('#tabel-anggota').DataTable();
    });
</script>