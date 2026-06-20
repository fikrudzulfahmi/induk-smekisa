<?php require_once '../app/views/templates/header.php'; ?>
<?php require_once '../app/views/templates/sidebar.php'; ?>
<style>
    /* Memastikan dropdown tampil di atas semua elemen */
    .dropdown-menu-custom {
        z-index: 2000 !important;
        box-shadow: 0px 3px 3px rgba(0, 0, 0, 0.19);
        /* Shadow lebih jelas */
        animation: fadeInScale 0.2s ease-out;
        margin-top: 5px;
        min-width: 180px;
    }

    /* Animasi masuk dropdown */
    @keyframes fadeInScale {
        0% {
            opacity: 0;
            transform: scale(0.95);
        }

        100% {
            opacity: 1;
            transform: scale(1);
        }
    }
</style>
<div id="main">
    <div class="page-heading">
        <h3>Data Rombel</h3>
    </div>
    <div class="page-content">
        <?php Flasher::flash(); ?>
        <div class="card">
            <div class="card-header">
                <?php if (Auth::checkRole('admin')) : ?>
                    <a href="<?= BASEURL; ?>/rombel/cetakRekap" class="btn btn-success" target="_blank">
                        <i class="bi bi-file-earmark-pdf"></i> Cetak Rekap Rombel
                    </a>

                    <a href="<?= BASEURL; ?>/rombel/tambah" class="btn btn-primary">Tambah Data</a>
                    <a href="<?= BASEURL; ?>/rombel/kenaikan" class="btn btn-warning">
                        <i class="bi bi-arrow-up-circle-fill"></i> Kenaikan Kelas
                    </a>
                    <a href="<?= BASEURL; ?>/rombel/kelulusan" class="btn btn-info text-white">
                        <i class="bi bi-mortarboard-fill"></i> Kelulusan
                    </a>
                    <a href="<?= BASEURL; ?>/rombel/history" class="btn btn-outline-secondary">
                        <i class="bi bi-clock-history"></i> History Rombel
                    </a><?php endif; ?>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-3">
                        <select id="filter-rombel" class="form-select">
                            <option value="">Semua Rombel</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <select id="filter-jurusan" class="form-select">
                            <option value="">Semua Jurusan</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <select id="filter-tingkat" class="form-select">
                            <option value="">Semua Tingkat</option>
                            <option value="10">10</option>
                            <option value="11">11</option>
                            <option value="12">12</option>
                        </select>
                    </div>
                </div>
                <table class="table table-striped" id="tabel-rombel">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Nama Rombel</th>
                            <th>Tingkat</th>
                            <th>Konsentrasi Keahlian</th>
                            <th>Wali Kelas</th>
                            <th>Jumlah</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php require_once '../app/views/templates/footer.php'; ?>
<?php $isAdmin = Auth::checkRole('admin'); ?>
<script>
    $(document).ready(function() {
        let table = $('#tabel-rombel').DataTable({
            processing: true,
            serverSide: true,
            searching: false, // ⛔ matikan search ketik
            ajax: {
                url: "<?= BASEURL; ?>/rombel/getServerSideRombel",
                type: "POST",
                data: function(d) {
                    d.nama_rombel = $('#filter-rombel').val();
                    d.jurusan = $('#filter-jurusan').val();
                    d.tingkat = $('#filter-tingkat').val();
                }
            },

            "columns": [{
                    "data": null,
                    "orderable": false,
                    "render": function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                {
                    "data": "nama_rombel"
                },
                {
                    "data": "tingkat"
                },
                {
                    "data": "konsentrasi_keahlian"
                },
                {
                    "data": "wali_kelas"
                },
                {
                    "data": "jumlah_anggota"
                },
                {
                    "data": "id_rombel", // Kolom ID sebagai data untuk tombol
                    "orderable": false,
                    "width": "320px", // Sesuaikan lebar agar tombol pas
                    "render": function(data, type, row) {
                        // 1. Tombol Utama (Selalu Tampil)
                        let buttons = `<a href="<?= BASEURL; ?>/rombel/anggota/${data}" class="btn btn-sm btn-info me-1" title="Lihat Anggota"><i class="bi bi-people-fill"></i> Anggota</a>`;

                        buttons += `<a href="<?= BASEURL; ?>/rombel/cetak_pdf/${data}" class="btn btn-sm btn-success me-1" target="_blank" title="Cetak Presensi"><i class="bi bi-file-earmark-pdf"></i> Cetak Presensi</a>`;

                        // 2. Tombol Dropdown (Hanya untuk Admin)
                        if (<?= $isAdmin ? 'true' : 'false' ?>) {
                            buttons += `
                        <div class="btn-group">
                            <button type="button" class="btn btn-sm btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" title="Aksi Lainnya">
                                <i class="bi bi-three-dots-vertical">... Aksi Lainnya</i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-custom">
                                <li><a class="dropdown-item" href="<?= BASEURL; ?>/rombel/cetak_pdf/${data}" target="_blank"><i class="bi bi-file-earmark-pdf me-2"></i>Cetak Presensi</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="<?= BASEURL; ?>/rombel/edit/${data}"><i class="bi bi-pencil-fill me-2"></i>Edit</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-danger" href="javascript:void(0);" onclick="confirmDelete('<?= BASEURL; ?>/rombel/hapus/${data}')"><i class="bi bi-trash-fill me-2"></i>Hapus</a></li>
                            </ul>
                        </div>`;
                        }

                        return buttons; // Kembalikan HTML tombol
                    }
                }
                // ,
                // {
                //     "data": "id_rombel",
                //     "orderable": false,
                //     "render": function(data) {
                //         let buttons = `<a href="<?= BASEURL; ?>/rombel/anggota/${data}" class="btn btn-sm btn-primary" title="Lihat Anggota"><i class="bi bi-people-fill"></i></a>
                //                <a href="<?= BASEURL; ?>/rombel/cetak_pdf/${data}" class="btn btn-sm btn-success" target="_blank" title="Cetak Presensi"><i class="bi bi-file-earmark-pdf"></i></a>`;
                //         if (<?= $isAdmin ? 'true' : 'false' ?>) {
                //             buttons += ` <a href="<?= BASEURL; ?>/rombel/edit/${data}" class="btn btn-sm btn-warning" title="Edit"><i class="bi bi-pencil-fill"></i></a>
                //                  <a href="javascript:void(0);" class="btn btn-sm btn-danger" onclick="confirmDelete('<?= BASEURL; ?>/rombel/hapus/${data}')" title="Hapus"><i class="bi bi-trash-fill"></i></a>`;
                //         }
                //         return buttons;
                //     }
                // }
            ]
        });

        $('#filter-rombel, #filter-jurusan, #filter-tingkat').on('change', function() {
            table.ajax.reload();
        });

        $.getJSON("<?= BASEURL; ?>/rombel/getFilterOptions", function(res) {

            res.rombel.forEach(function(r) {
                $('#filter-rombel').append(`<option value="${r.nama_rombel}">${r.nama_rombel}</option>`);
            });

            res.jurusan.forEach(function(j) {
                $('#filter-jurusan').append(`<option value="${j.jurusan}">${j.jurusan}</option>`);
            });

        });


    });
</script>