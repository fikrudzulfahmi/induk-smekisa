<?php require_once '../app/views/templates/header.php'; ?>
<?php require_once '../app/views/templates/sidebar.php'; ?>
<div id="main">
    <div class="page-heading">
        <h3>Edit Jurusan</h3>
    </div>
    <div class="page-content">
        <div class="card">
            <div class="card-body">
                <form action="<?= BASEURL; ?>/jurusan/prosesUpdate" method="post">
                    <input type="hidden" name="id_jurusan" value="<?= $data['jurusan']->id_jurusan; ?>">
                    <div class="form-group mb-3"><label for="jurusan">Nama Jurusan</label><input type="text" class="form-control" name="jurusan" value="<?= $data['jurusan']->jurusan; ?>" required></div>
                    <div class="form-group mb-3"><label for="prog_keahlian">Program Keahlian</label><input type="text" class="form-control" name="prog_keahlian" value="<?= $data['jurusan']->prog_keahlian; ?>" required></div>
                    <div class="form-group mb-3"><label for="bid_keahlian">Bidang Keahlian</label><input type="text" class="form-control" name="bid_keahlian" value="<?= $data['jurusan']->bid_keahlian; ?>" required></div>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    <a href="<?= BASEURL; ?>/jurusan" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>
</div>
<?php require_once '../app/views/templates/footer.php'; ?>