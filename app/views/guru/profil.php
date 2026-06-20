<?php require_once '../app/views/templates/header.php'; ?>
<?php require_once '../app/views/templates/sidebar.php'; ?>

<div id="main">
    <div class="page-heading">
        <h3>Profil Saya</h3>
    </div>
    <div class="page-content">
        <?php Flasher::flash(); ?>
        <div class="card">
            <div class="card-header">
                <h4 class="card-title"><?= $data['guru']->nama_guru; ?></h4>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <td width="200">Nama Lengkap</td>
                        <td>: <?= $data['guru']->nama_guru; ?></td>
                    </tr>
                    <tr>
                        <td>NIK</td>
                        <td>: <?= $data['guru']->nik; ?></td>
                    </tr>
                    <tr>
                        <td>NUPTK</td>
                        <td>: <?= $data['guru']->nuptk; ?></td>
                    </tr>
                    <tr>
                        <td>Alamat</td>
                        <td>: <?= $data['guru']->alamat; ?></td>
                    </tr>
                    <tr>
                        <td>No. HP</td>
                        <td>: <?= $data['guru']->no_hp_guru; ?></td>
                    </tr>
                    <tr>
                        <td>Username</td>
                        <td>: <?= $data['guru']->username; ?></td>
                    </tr>
                </table>
                <a href="<?= BASEURL; ?>/guru/editProfil/<?= $data['guru']->id_guru; ?>" class="btn btn-primary mt-3">Edit Profil</a>
            </div>
        </div>
    </div>
</div>

<?php require_once '../app/views/templates/footer.php'; ?>