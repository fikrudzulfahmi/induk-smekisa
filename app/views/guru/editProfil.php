<?php require_once '../app/views/templates/header.php'; ?>
<?php require_once '../app/views/templates/sidebar.php'; ?>

<div id="main">
    <div class="page-heading">
        <h3>Edit Profil</h3>
    </div>
    <div class="page-content">
        <div class="card">
            <div class="card-body">
                <form action="<?= BASEURL; ?>/guru/prosesUpdateProfil" method="post">
                    <input type="hidden" name="id_guru" value="<?= $data['guru']->id_guru; ?>">

                    <div class="form-group mb-3">
                        <label for="nama_guru">Nama Lengkap</label>
                        <input type="text" class="form-control" id="nama_guru" name="nama_guru" value="<?= $data['guru']->nama_guru; ?>" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="nik">NIK</label>
                        <input type="text" class="form-control" id="nik" name="nik" value="<?= $data['guru']->nik; ?>">
                    </div>
                    <div class="form-group mb-3">
                        <label for="nuptk">NUPTK</label>
                        <input type="text" class="form-control" id="nuptk" name="nuptk" value="<?= $data['guru']->nuptk; ?>">
                    </div>
                    <div class="form-group mb-3">
                        <label for="alamat">Alamat</label>
                        <textarea class="form-control" id="alamat" name="alamat"><?= $data['guru']->alamat; ?></textarea>
                    </div>
                    <div class="form-group mb-3">
                        <label for="no_hp_guru">No. HP</label>
                        <input type="text" class="form-control" id="no_hp_guru" name="no_hp_guru" value="<?= $data['guru']->no_hp_guru; ?>">
                    </div>
                    <div class="form-group mb-3">
                        <label for="username">Username</label>
                        <input type="text" class="form-control" id="username" name="username" value="<?= $data['guru']->username; ?>" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="password">Password Baru</label>
                        <input type="password" class="form-control" id="password" name="password">
                        <small class="form-text text-muted">Kosongkan jika tidak ingin mengubah password.</small>
                    </div>

                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    <a href="<?= BASEURL; ?>/guru/profil" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once '../app/views/templates/footer.php'; ?>