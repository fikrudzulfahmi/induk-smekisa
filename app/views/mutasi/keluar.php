<?php require_once '../app/views/templates/header.php'; ?>
<?php require_once '../app/views/templates/sidebar.php'; ?>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="<?= BASEURL; ?>/assets/extensions/select2-bootstrap-5-theme/select2-bootstrap-5-theme.min.css" />


<div id="main">
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Proses Mutasi Keluar Siswa</h3>
                    <p class="text-subtitle text-muted">Pilih siswa dan tentukan status keluar.</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?= BASEURL; ?>/dashboard">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="<?= BASEURL; ?>/mutasi">Mutasi</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Mutasi Keluar</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <div class="page-content">
        <?php Flasher::flash(); ?>
        <section class="section">
            <div class="card col-md-8">
                <div class="card-header">
                    <h4 class="card-title">Formulir Mutasi Keluar</h4>
                </div>
                <form action="<?= BASEURL; ?>/mutasi/prosesKeluar" method="post" id="formMutasiKeluar">
                    <div class="card-body">

                        <div class="form-group mb-3">
                            <label for="selectSiswa" class="form-label">Cari Siswa (Nama / NIS / NISN) <span class="text-danger">*</span></label>
                            <select class="form-select" id="selectSiswa" name="id_siswa" required></select>
                        </div>

                        <hr>
                        <p>Pilih Jenis Aksi: <span class="text-danger">*</span></p>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="jenis_aksi" id="radioKeluar" value="keluar" required>
                                    <label class="form-check-label" for="radioKeluar">
                                        <b>Mutasi Keluar</b> (Status siswa diubah menjadi "Mutasi Keluar")
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="jenis_aksi" id="radioUndurDiri" value="undur_diri">
                                    <label class="form-check-label" for="radioUndurDiri">
                                        <b>Mengundurkan Diri</b> (Status siswa tetap "Aktif", hanya dicatat)
                                    </label>
                                </div>
                            </div>
                        </div>
                        <hr>

                        <div class="form-group mb-3">
                            <label for="tgl_keluar" class="form-label">Tanggal Keluar / Mengundurkan Diri <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="tgl_keluar" id="tgl_keluar" required>
                        </div>

                        <div class="form-group mb-3" id="fieldSekolahTujuan" style="display: none;">
                            <label for="sekolah_tujuan" class="form-label">Sekolah Tujuan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="sekolah_tujuan" id="sekolah_tujuan" placeholder="Nama Sekolah Tujuan">
                        </div>

                        <div class="form-group mb-3">
                            <label for="alasan_keluar" class="form-label">Alasan <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="alasan_keluar" id="alasan_keluar" rows="3" required></textarea>
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-end">
                        <a href="<?= BASEURL; ?>/mutasi" class="btn btn-secondary me-2">Batal</a>
                        <button type="submit" class="btn btn-danger">Proses Mutasi</button>
                    </div>
                </form>
            </div>
        </section>
    </div>
</div>

<?php require_once '../app/views/templates/footer.php'; ?>
<script src="<?= BASEURL; ?>/assets/extensions/jquery/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function() {

        // Inisialisasi Select2 untuk pencarian siswa
        $('#selectSiswa').select2({
            theme: "bootstrap-5",
            placeholder: 'Ketik Nama / NIS / NISN...',
            allowClear: true,
            minimumInputLength: 3, // Wajib ketik 3 huruf agar tidak berat
            ajax: {
                url: "<?= BASEURL; ?>/siswa/searchSiswaAktif",
                type: "POST", // <--- PERUBAHAN PENTING: Ganti metode ke POST
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    // Log ke console untuk memastikan kita mengetik sesuatu
                    console.log("Mencari: " + params.term);
                    return {
                        searchTerm: params.term // Kirim parameter via POST
                    };
                },
                processResults: function(data) {
                    return {
                        results: data.results
                    };
                },
                cache: false // Matikan cache agar data selalu baru
            }
        });

        // JavaScript untuk menampilkan/menyembunyikan field Sekolah Tujuan
        $('input[type=radio][name=jenis_aksi]').change(function() {
            if (this.value == 'keluar') {
                $('#fieldSekolahTujuan').show();
                $('#sekolah_tujuan').prop('required', true); // Wajib diisi jika keluar
            } else if (this.value == 'undur_diri') {
                $('#fieldSekolahTujuan').hide();
                $('#sekolah_tujuan').prop('required', false); // Tidak wajib diisi
            }
        });
    });
</script>