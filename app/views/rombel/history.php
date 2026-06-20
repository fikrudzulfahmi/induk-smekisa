<?php require_once '../app/views/templates/header.php'; ?>
<?php require_once '../app/views/templates/sidebar.php'; ?>

<div id="main">
    <div class="page-heading">
        <h3>History Rombel</h3>
        <p class="text-subtitle text-muted">Rekam jejak kenaikan kelas dan kelulusan siswa.</p>
    </div>
    <div class="page-content">
        <div class="card">
            <div class="card-header">
                <a href="<?= BASEURL; ?>/rombel" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Kembali ke Data Rombel
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered" id="tabel-history">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">No.</th>
                                <th>Tanggal Proses</th>
                                <th>NIS</th>
                                <th>Nama Siswa</th>
                                <th>Status</th>
                                <th>Dari Rombel</th>
                                <th>Ke Rombel</th>
                                <th>Tahun Ajaran</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1;
                            foreach ($data['history'] as $h) : ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td><?= date('d-m-Y H:i', strtotime($h->created_at)); ?></td>
                                    <td><?= htmlspecialchars($h->no_induk ?? '-'); ?></td>
                                    <td><?= htmlspecialchars($h->nama_siswa ?? '-'); ?></td>
                                    <td>
                                        <?php if ($h->status == 'Naik') : ?>
                                            <span class="badge bg-warning text-dark"><i class="bi bi-arrow-up-circle"></i> Naik Kelas</span>
                                        <?php elseif ($h->status == 'Lulus') : ?>
                                            <span class="badge bg-info text-white"><i class="bi bi-mortarboard-fill"></i> Lulus</span>
                                        <?php else : ?>
                                            <span class="badge bg-secondary"><?= htmlspecialchars($h->status); ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= htmlspecialchars($h->kelas_asal ?? '-'); ?></td>
                                    <td>
                                        <?php
                                        // Jika statusnya Lulus, biasanya Ke Rombel akan NULL/Kosong
                                        echo $h->kelas_tujuan ? htmlspecialchars($h->kelas_tujuan) : '<i class="text-muted">Alumni</i>';
                                        ?>
                                    </td>
                                    <td><?= htmlspecialchars($h->tahun_ajaran); ?></td>
                                </tr>
                            <?php endforeach; ?>
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
        // Inisialisasi DataTables
        $('#tabel-history').DataTable({
            "order": [
                [1, "desc"]
            ], // Urutkan berdasarkan kolom Tanggal (index 1) secara Descending
            "language": {
                "search": "Cari (Nama/NIS/Tahun):",
                "lengthMenu": "Tampilkan _MENU_ data",
                "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                "infoEmpty": "Tidak ada data tersedia",
                "zeroRecords": "Tidak ada riwayat yang cocok"
            }
        });
    });
</script>