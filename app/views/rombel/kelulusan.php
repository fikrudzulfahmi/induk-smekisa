<?php require_once '../app/views/templates/header.php'; ?>
<?php require_once '../app/views/templates/sidebar.php'; ?>

<div id="main">
    <div class="page-heading">
        <h3>Proses Kelulusan Siswa</h3>
        <p class="text-subtitle text-muted">Ubah status siswa menjadi Lulus dan catat ke riwayat akademik.</p>
    </div>
    <div class="page-content">
        <?php Flasher::flash(); ?>

        <div class="card">
            <form action="<?= BASEURL; ?>/rombel/aksiLulus" method="POST" id="formKelulusan">
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="dari_rombel" class="form-label fw-bold">Pilih Rombel (Kelas Akhir)</label>
                            <select name="dari_rombel" id="dari_rombel" class="form-select" required>
                                <option value="" selected disabled>-- Pilih Rombel --</option>
                                <?php foreach ($data['rombel'] as $r) : ?>
                                    <option value="<?= $r->id_rombel; ?>"><?= $r->tingkat . ' ' . $r->nama_rombel; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <hr>

                    <h5 class="mb-3">Daftar Siswa Aktif</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="table-light">
                                <tr>
                                    <th width="5%" class="text-center">
                                        <input class="form-check-input" type="checkbox" id="checkAll">
                                    </th>
                                    <th width="10%">No.</th>
                                    <th width="30%">NIS</th>
                                    <th>Nama Siswa</th>
                                </tr>
                            </thead>
                            <tbody id="data-siswa">
                                <tr>
                                    <td colspan="4" class="text-center text-muted">Silakan pilih Rombel terlebih dahulu</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer text-end">
                    <a href="<?= BASEURL; ?>/rombel" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-info text-white" id="btnProses" disabled>
                        <i class="bi bi-mortarboard-fill"></i> Proses Luluskan Siswa
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once '../app/views/templates/footer.php'; ?>

<script>
    $(document).ready(function() {
        // Event ketika Rombel dipilih (Kita pakai ulang fungsi getSiswaByRombel yang sudah ada!)
        $('#dari_rombel').on('change', function() {
            let idRombel = $(this).val();
            let tbody = $('#data-siswa');
            let btnProses = $('#btnProses');

            // Kosongkan tabel dan beri loading
            tbody.html('<tr><td colspan="4" class="text-center"><div class="spinner-border text-info" role="status"></div> Mengambil data...</td></tr>');
            btnProses.prop('disabled', true);
            $('#checkAll').prop('checked', false);

            // Panggil data via AJAX
            $.ajax({
                url: '<?= BASEURL; ?>/rombel/getSiswaByRombel/' + idRombel,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    tbody.empty();

                    if (data.length > 0) {
                        let no = 1;
                        $.each(data, function(index, siswa) {
                            tbody.append(`
                                <tr>
                                    <td class="text-center">
                                        <input class="form-check-input check-siswa" type="checkbox" name="id_siswa[]" value="${siswa.id_induk}">
                                    </td>
                                    <td>${no++}</td>
                                    <td>${siswa.no_induk}</td>
                                    <td>${siswa.nama_siswa}</td>
                                </tr>
                            `);
                        });
                        btnProses.prop('disabled', false); // Aktifkan tombol
                    } else {
                        tbody.html('<tr><td colspan="4" class="text-center text-danger">Tidak ada siswa aktif di rombel ini.</td></tr>');
                    }
                },
                error: function() {
                    tbody.html('<tr><td colspan="4" class="text-center text-danger">Terjadi kesalahan saat mengambil data.</td></tr>');
                }
            });
        });

        // Fitur Check All
        $('#checkAll').on('click', function() {
            $('.check-siswa').prop('checked', this.checked);
        });

        $(document).on('click', '.check-siswa', function() {
            if ($('.check-siswa:checked').length === $('.check-siswa').length) {
                $('#checkAll').prop('checked', true);
            } else {
                $('#checkAll').prop('checked', false);
            }
        });

        // Validasi form dengan SweetAlert2
        $('#formKelulusan').on('submit', function(e) {
            e.preventDefault();

            if ($('.check-siswa:checked').length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Oops...',
                    text: 'Pilih minimal 1 siswa untuk diluluskan!'
                });
            } else {
                Swal.fire({
                    title: 'Konfirmasi Kelulusan',
                    text: "Yakin ingin meluluskan siswa yang dipilih? Status mereka akan berubah menjadi 'Lulus' (Tidak Aktif).",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#17a2b8', // Warna tombol info
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Luluskan!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.submit();
                    }
                });
            }
        });
    });
</script>