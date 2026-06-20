<?php
require_once '../app/views/templates/header.php';
require_once '../app/views/templates/sidebar.php';
?>

<div id="main">
    <div class="page-heading">
        <h3>Data Siswa</h3>
        <p class="text-subtitle text-muted">Meliputi data siswa aktif.</p>
    </div>
    <div class="page-content">
        <section class="section">
            <div class="card">
                <div class="card-header">
                    <?php if (Auth::checkRole('admin')) : ?>
                        <a href="<?= BASEURL; ?>/siswa/tambah" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i> Tambah Data Siswa
                        </a>
                    <?php endif; ?>
                    <?php if (Auth::checkRole('admin') || Auth::checkRole('waka')) : ?>
                        <a href="<?= BASEURL; ?>/siswa/exportExcelLengkap" target="_blank" class="btn btn-success float-end ms-2" title="Export Semua Data Siswa Aktif ke Excel">
                            <i class="bi bi-file-earmark-spreadsheet-fill"></i> Export Lengkap (Excel)
                        </a>
                    <?php endif; ?>
                </div>
                <div class="card-body">
                    <table class="table table-striped" id="tabel-siswa">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Siswa</th>
                                <th>Jenis Kelamin</th>
                                <th>No. Induk</th>
                                <th>Rombel</th>
                                <th>Jurusan</th>
                                <th>Status</th>
                                <?php if (Auth::checkRole('admin') || Auth::checkRole('waka')) : ?>
                                    <th>Aksi</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>
</div>
<?php
require_once '../app/views/templates/footer.php';
?>


<?php
// BUAT VARIABEL UNTUK STATUS ADMIN DI SINI
$isAdmin = Auth::checkRole('admin') || Auth::checkRole('waka');
?>
<script>
    $(document).ready(function() {
        $('#tabel-siswa').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "<?= BASEURL; ?>/siswa/getServerSideSiswa",
                "type": "POST"
            },
            "columns": [{
                    "data": null,
                    "orderable": false,
                    "searchable": false,
                    "render": function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                {
                    "data": "nama_siswa"
                },
                {
                    "data": "jenis_kelamin"
                },
                {
                    "data": "no_induk"
                },
                {
                    "data": "nama_rombel"
                },
                {
                    "data": "jurusan"
                },
                {
                    "data": "status"
                },
                {
                    "data": "id_induk",
                    "render": function(data, type, row) {
                        let buttons = ''; // 1. Mulai dengan string kosong

                        // 2. Cek apakah pengguna adalah admin
                        if (<?= $isAdmin ? 'true' : 'false' ?>) {
                            // 3. Jika admin, buat tombol View/Detail
                            buttons = `<a href="<?= BASEURL; ?>/siswa/detail/${data}" class="btn btn-sm btn-info"><i class='bi bi-eye'></i> Detail</a>`;
                        }

                        // 4. Kembalikan hasilnya (tombol jika admin, kosong jika bukan)
                        return buttons;
                    },
                    "orderable": false
                }
            ]
        });
    });
</script>