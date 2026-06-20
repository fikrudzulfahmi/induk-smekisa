<?php require_once '../app/views/templates/header.php'; ?>
<?php require_once '../app/views/templates/sidebar.php'; ?>

<div id="main">
    <div class="page-heading">
        <h3>Tambah Guru Baru</h3>
    </div>
    <div class="page-content">
        <div class="card">
            <div class="card-body">
                <form action="<?= BASEURL; ?>/guru/prosesTambah" method="post">
                    <div class="form-group"><label for="nama_guru">Nama Lengkap</label><input type="text" class="form-control" id="nama_guru" name="nama_guru" required></div>
                    <div class="form-group"><label for="nik">NIK</label><input type="text" class="form-control" id="nik" name="nik"></div>
                    <div class="form-group"><label for="nuptk">NUPTK</label><input type="text" class="form-control" id="nuptk" name="nuptk"></div>
                    <div class="form-group"><label for="alamat">Alamat</label><textarea class="form-control" id="alamat" name="alamat"></textarea></div>
                    <div class="form-group"><label for="no_hp_guru">No. HP</label><input type="text" class="form-control" id="no_hp_guru" name="no_hp_guru"></div>
                    <div class="form-group"><label for="username">Username</label><input type="text" class="form-control" id="username" name="username" required></div>
                    <div class="form-group"><label for="password">Password</label><input type="password" class="form-control" id="password" name="password" required></div>
                    <button type="submit" class="btn btn-primary mt-3">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once '../app/views/templates/footer.php'; ?>