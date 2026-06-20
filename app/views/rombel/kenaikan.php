<?php require_once '../app/views/templates/header.php'; ?>
<?php require_once '../app/views/templates/sidebar.php'; ?>

<div id="main">
    <div class="page-heading">
        <h3>Proses Kenaikan Kelas</h3>
        <p class="text-subtitle text-muted">Pindahkan siswa ke tingkat kelas selanjutnya secara massal.</p>
    </div>
    <div class="page-content">
        <?php Flasher::flash(); ?>

        <div class="card">
            <form action="<?= BASEURL; ?>/rombel/aksiNaikKelas" method="POST" id="formKenaikan">
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-5">
                            <label for="dari_rombel" class="form-label fw-bold">Dari Rombel (Asal)</label>
                            <select name="dari_rombel" id="dari_rombel" class="form-select" required>
                                <option value="" selected disabled>-- Pilih Rombel Asal --</option>
                                <?php foreach ($data['rombel'] as $r) : ?>
                                    <option value="<?= $r->id_rombel; ?>"><?= $r->tingkat . ' ' . $r->nama_rombel; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-2 d-flex align-items-end justify-content-center">
                            <i class="bi bi-arrow-right-circle-fill text-primary" style="font-size: 2rem;"></i>
                        </div>

                        <div class="col-md-5">
                            <label for="ke_rombel" class="form-label fw-bold">Ke Rombel (Tujuan)</label>
                            <select name="ke_rombel" id="ke_rombel" class="form-select" required>
                                <option value="" selected disabled>-- Pilih Rombel Tujuan --</option>
                                <?php foreach ($data['rombel'] as $r) : ?>
                                    <option value="<?= $r->id_rombel; ?>"><?= $r->tingkat . ' ' . $r->nama_rombel; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <hr>

                    <h5 class="mb-3">Daftar Siswa</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="tabel-siswa-kenaikan">
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
                                    <td colspan="4" class="text-center text-muted">Silakan pilih Rombel Asal terlebih dahulu</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer text-end">
                    <a href="<?= BASEURL; ?>/rombel" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary" id="btnProses" disabled>
                        <i class="bi bi-box-arrow-in-right"></i> Proses Kenaikan Kelas
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once '../app/views/templates/footer.php'; ?>

<script>
    $(document).ready(function() {
        // Event ketika Rombel Asal dipilih
        $('#dari_rombel').on('change', function() {
            let idRombel = $(this).val();
            let tbody = $('#data-siswa');
            let btnProses = $('#btnProses');

            // Kosongkan tabel dan beri loading
            tbody.html('<tr><td colspan="4" class="text-center"><div class="spinner-border text-primary" role="status"></div> Mengambil data...</td></tr>');
            btnProses.prop('disabled', true);
            $('#checkAll').prop('checked', false); // Reset check all

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
                            // Asumsi field dari database: id_induk, no_induk, nama_siswa
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

        // Fitur Check All / Uncheck All
        $('#checkAll').on('click', function() {
            $('.check-siswa').prop('checked', this.checked);
        });

        // Jika salah satu checkbox siswa di-uncheck, uncheck juga "Check All"
        $(document).on('click', '.check-siswa', function() {
            if ($('.check-siswa:checked').length === $('.check-siswa').length) {
                $('#checkAll').prop('checked', true);
            } else {
                $('#checkAll').prop('checked', false);
            }
        });

        // Validasi form sebelum submit (pastikan minimal 1 siswa dipilih)
        $('#formKenaikan').on('submit', function(e) {
            e.preventDefault(); // Hentikan submit bawaan untuk menjalankan SweetAlert

            if ($('.check-siswa:checked').length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Oops...',
                    text: 'Pilih minimal 1 siswa untuk dinaikkan kelasnya!'
                });
            } else if ($('#dari_rombel').val() === $('#ke_rombel').val()) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Oops...',
                    text: 'Rombel Asal dan Rombel Tujuan tidak boleh sama!'
                });
            } else {
                // Konfirmasi dengan SweetAlert
                Swal.fire({
                    title: 'Konfirmasi Proses',
                    text: "Yakin ingin memproses kenaikan kelas untuk siswa yang dipilih? Pastikan Rombel Tujuan sudah benar.",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Proses!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Lanjutkan submit form jika user klik "Ya, Proses!"
                        this.submit();
                    }
                });
            }
        });
    });
</script>