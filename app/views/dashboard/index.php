<?php require_once '../app/views/templates/header.php'; ?>
<?php require_once '../app/views/templates/sidebar.php'; ?>

<div id="main">
    <div class="page-heading">
        <h3>Dashboard</h3>
        <p class="text-subtitle text-muted">Selamat datang, <?= $data['guru']->nama_guru; ?>.</p>
    </div>

    <div class="page-content">
        <section class="row">
            <div class="col-6 col-lg-3 col-md-6">
                <div class="card">
                    <div class="card-body px-4 py-4-5">
                        <div class="row">
                            <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                                <div class="stats-icon purple mb-2"><i class="iconly-boldShow"></i></div>
                            </div>
                            <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                <h6 class="text-muted font-semibold">Siswa Aktif</h6>
                                <h6 class="font-extrabold mb-0"><?= $data['total_siswa']; ?></h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3 col-md-6">
                <div class="card">
                    <div class="card-body px-4 py-4-5">
                        <div class="row">
                            <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                                <div class="stats-icon blue mb-2"><i class="iconly-boldProfile"></i></div>
                            </div>
                            <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                <h6 class="text-muted font-semibold">Jumlah Rombel</h6>
                                <h6 class="font-extrabold mb-0"><?= $data['total_rombel']; ?></h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3 col-md-6">
                <div class="card">
                    <div class="card-body px-4 py-4-5">
                        <div class="row">
                            <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                                <div class="stats-icon green mb-2"><i class="iconly-boldBookmark"></i></div>
                            </div>
                            <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                <h6 class="text-muted font-semibold">Jumlah Jurusan</h6>
                                <h6 class="font-extrabold mb-0"><?= $data['total_jurusan']; ?></h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3 col-md-6">
                <div class="card">
                    <div class="card-body px-4 py-4-5">
                        <div class="row">
                            <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                                <div class="stats-icon red mb-2"><i class="iconly-boldAdd-User"></i></div>
                            </div>
                            <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                <h6 class="text-muted font-semibold">Jumlah User</h6>
                                <h6 class="font-extrabold mb-0"><?= $data['total_user']; ?></h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <h5 class="mb-3 mt-4">Statistik Per Tingkat</h5>
        <section class="row">
            <?php foreach ($data['rekap_tingkat'] as $tingkat => $rekap) : ?>
                <div class="col-12 col-md-4">
                    <div class="card">
                        <div class="card-header pb-0 text-center">
                            <h4 class="card-title">Tingkat <?= $tingkat ?></h4>
                        </div>
                        <div class="card-body mt-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-muted">Laki-laki</span>
                                <span class="badge bg-primary"><?= $rekap['laki'] ?></span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-muted">Perempuan</span>
                                <span class="badge bg-info"><?= $rekap['perempuan'] ?></span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="font-bold">Total Siswa</span>
                                <span class="font-extrabold text-primary"><?= $rekap['total'] ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </section>

        <section class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Rekapitulasi Anggota Rombel</h4>
                        <a href="<?= BASEURL; ?>/rombel/cetakRekap" class="btn btn-danger btn-sm" target="_blank">
                            <i class="bi bi-file-earmark-pdf"></i> Cetak Rekap Rombel
                        </a>
                    </div>
                    <div class="card-body pt-4">
                        <?php foreach ($data['groupedRombel'] as $tingkat => $rombels) : ?>
                            <?php if (!empty($rombels)) : ?>
                                <div class="mt-2 mb-2">
                                    <h5 class="text-secondary border-bottom pb-2">TINGKAT <?= $tingkat ?></h5>
                                </div>
                                <div class="table-responsive mb-4">
                                    <table class="table table-bordered table-striped table-sm">
                                        <thead class="bg-light text-center">
                                            <tr>
                                                <th width="50">NO</th>
                                                <th>KELAS</th>
                                                <th>KONSENTRASI KEAHLIAN</th>
                                                <th width="60">L</th>
                                                <th width="60">P</th>
                                                <th width="80">TOTAL</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $no = 1;
                                            foreach ($rombels as $r) : ?>
                                                <tr>
                                                    <td class="text-center"><?= $no++ ?></td>
                                                    <td class="font-bold"><?= htmlspecialchars($r->nama_rombel) ?></td>
                                                    <td><?= htmlspecialchars($r->nama_jurusan ?? '-') ?></td>
                                                    <td class="text-center"><?= $r->jumlah_laki ?></td>
                                                    <td class="text-center"><?= $r->jumlah_perempuan ?></td>
                                                    <td class="text-center font-bold"><?= $r->total_siswa ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                        <tfoot class="table-light">
                                            <tr class="font-bold">
                                                <td colspan="3" class="text-end">JUMLAH TINGKAT <?= $tingkat ?></td>
                                                <td class="text-center"><?= $data['rekap_tingkat'][$tingkat]['laki'] ?></td>
                                                <td class="text-center"><?= $data['rekap_tingkat'][$tingkat]['perempuan'] ?></td>
                                                <td class="text-center"><?= $data['rekap_tingkat'][$tingkat]['total'] ?></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </section>

        <?php if (Auth::checkRole('admin')) : ?>
            <section class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Baru Saja Diperbarui</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Nama Siswa</th>
                                            <th>Rombel</th>
                                            <th>Waktu</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($data['siswa_diedit'] as $s) : ?>
                                            <tr>
                                                <td><?= htmlspecialchars($s->nama_siswa) ?></td>
                                                <td><?= $s->nama_rombel ?></td>
                                                <td><span class="badge bg-light-secondary text-dark"><?= $s->updated_at_induk ?></span></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        <?php endif; ?>

    </div>
</div>

<?php require_once '../app/views/templates/footer.php'; ?>