<?php require_once '../app/views/templates/header.php'; ?>
<?php require_once '../app/views/templates/sidebar.php'; ?>

<div id="main">
    <div class="page-heading">
        <h3>Buku Induk Siswa</h3>
        <p class="text-subtitle text-muted">Menampilkan seluruh data siswa aktif, lulus, pindah, maupun keluar.</p>
    </div>

    <div class="page-content">
        <?php Flasher::flash(); ?>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <?php if (Auth::checkRole('admin') || Auth::checkRole('waka')) : ?>
                    <!-- <a href="<?= BASEURL; ?>/siswa/exportExcelKeluar" target="_blank" class="btn btn-danger float-end ms-2" title="Export Semua Data Siswa Aktif ke Excel">
                        <i class="bi bi-file-earmark-spreadsheet-fill"></i> PD Keluar (Excel)
                    </a> -->
                    <a href="<?= BASEURL; ?>/siswa/exportExcelInduk" target="_blank" class="btn btn-success float-end ms-2" title="Export Semua Data Siswa Aktif ke Excel">
                        <i class="bi bi-file-earmark-spreadsheet-fill"></i> Export Lengkap (Excel)
                    </a>
                <?php endif; ?>

            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered" id="tabel-data-induk" style="width:100%">
                        <thead class="table-light">
                            <tr>
                                <th width="5%" class="text-center">No.</th>
                                <th width="15%">NIS / NISN</th>
                                <th width="30%">Nama Lengkap</th>
                                <th width="15%">Rombel Terakhir</th>
                                <th width="15%" class="text-center">Status</th>
                                <th width="15%" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../app/views/templates/footer.php'; ?>

<script>
    $(document).ready(function() {
        $('#tabel-data-induk').DataTable({
            "processing": true, // Menampilkan indikator 'Loading...'
            "serverSide": true, // Mengaktifkan Server-Side Processing
            "ajax": {
                "url": "<?= BASEURL; ?>/siswa/getSiswaAjax", // Arahkan ke method Controller
                "type": "POST" // Menggunakan metode POST agar aman mengirim parameter
            },
            "columns": [{
                    "data": 0,
                    "className": "text-center",
                    "orderable": false
                }, // No
                {
                    "data": 1
                }, // NIS
                {
                    "data": 2
                }, // Nama
                {
                    "data": 3
                }, // Rombel
                {
                    "data": 4,
                    "className": "text-center"
                }, // Status
                {
                    "data": 5,
                    "className": "text-center",
                    "orderable": false
                } // Aksi (Tombol)
            ],
            "order": [
                [1, "asc"]
            ], // Default urutkan berdasarkan Nama Siswa (Kolom ke-2) A-Z
            "language": {
                "processing": "<div class='spinner-border text-primary' role='status'><span class='visually-hidden'>Loading...</span></div> Memuat data...",
                "search": "Cari (Nama/NIS):",
                "lengthMenu": "Tampilkan _MENU_ data",
                "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data siswa",
                "infoEmpty": "Tidak ada data tersedia",
                "infoFiltered": "(difilter dari _MAX_ total data)",
                "zeroRecords": "Tidak ditemukan data siswa yang cocok"
            }
        });
    });
</script>