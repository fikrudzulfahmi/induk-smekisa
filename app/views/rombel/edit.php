<?php require_once '../app/views/templates/header.php'; ?>
<?php require_once '../app/views/templates/sidebar.php'; ?>

<div id="main">
    <div class="page-heading">
        <h3>Edit Rombel</h3>
    </div>
    <div class="page-content">
        <div class="card">
            <div class="card-body">
                <form action="<?= BASEURL; ?>/rombel/prosesUpdate" method="post">
                    <input type="hidden" name="id_rombel" value="<?= $data['rombel']->id_rombel; ?>">

                    <div class="form-group mb-3">
                        <label for="nama_rombel">Nama Rombel</label>
                        <input type="text" class="form-control" name="nama_rombel" value="<?= $data['rombel']->nama_rombel; ?>" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="tingkat">Tingkat</label>
                        <select class="form-select" name="tingkat" required>
                            <option value="">Pilih Tingkat</option>
                            <option value="10" <?= ($data['rombel']->tingkat == 10) ? 'selected' : ''; ?>>10</option>
                            <option value="11" <?= ($data['rombel']->tingkat == 11) ? 'selected' : ''; ?>>11</option>
                            <option value="12" <?= ($data['rombel']->tingkat == 12) ? 'selected' : ''; ?>>12</option>
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label for="jurusan">Konsentrasi Keahlian</label>
                        <select class="form-select" name="jurusan" required>
                            <option value="">Pilih Jurusan</option>
                            <?php foreach ($data['jurusan'] as $jurusan) : ?>
                                <option
                                    value="<?= $jurusan->id_jurusan; ?>"
                                    <?= ($jurusan->id_jurusan == $data['rombel']->id_jurusan) ? 'selected' : ''; ?>>
                                    <?= $jurusan->jurusan; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label for="wali_kelas_select">Wali Kelas</label> <select class="form-select" name="wali_kelas" id="wali_kelas_select" required style="width: 100%;">
                            <?php foreach ($data['guru'] as $guru) : ?>
                                <option
                                    value="<?= $guru->id_guru; ?>"
                                    <?= ($guru->id_guru == $data['rombel']->id_walas) ? 'selected' : ''; ?>>
                                    <?= htmlspecialchars($guru->nama_guru); ?> </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    <a href="<?= BASEURL; ?>/rombel" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once '../app/views/templates/footer.php'; ?>