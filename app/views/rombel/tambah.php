<?php require_once '../app/views/templates/header.php'; ?>
<?php require_once '../app/views/templates/sidebar.php'; ?>
<div id="main">
    <div class="page-heading">
        <h3>Tambah Rombel Baru</h3>
    </div>
    <div class="page-content">
        <div class="card">
            <div class="card-body">
                <form action="<?= BASEURL; ?>/rombel/prosesTambah" method="post">
                    <div class="form-group mb-3"><label for="nama_rombel">Nama Rombel</label><input type="text" class="form-control" name="nama_rombel" required></div>
                    <div class="form-group mb-3"><label for="tingkat">Tingkat</label>
                        <select class="form-select" name="tingkat" required>
                            <option value="">Pilih Tingkat</option>
                            <option value="10">10</option>
                            <option value="11">11</option>
                            <option value="12">12</option>
                        </select>
                    </div>
                    </select>
            </div>
            <div class="form-group mb-3"><label for="jurusan">Konsentrasi Keahlian</label>
                <select class="form-select" name="jurusan" required>
                    <option value="">Pilih Jurusan</option>
                    <?php foreach ($data['jurusan'] as $jurusan) : ?>
                        <option value="<?= $jurusan->id_jurusan; ?>"><?= $jurusan->jurusan; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group mb-3"><label for="wali_kelas">Wali Kelas</label>
                <label for="wali_kelas_select">Wali Kelas</label> <select class="form-select" name="wali_kelas" id="wali_kelas_select" required style="width: 100%;">
                    <option value="">Pilih Guru</option>
                    <?php foreach ($data['guru'] as $guru) : ?>
                        <option value="<?= $guru->id_guru; ?>">
                            <?= htmlspecialchars($guru->nama_guru); ?> </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="<?= BASEURL; ?>/rombel" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</div>
</div>
<?php require_once '../app/views/templates/footer.php'; ?>