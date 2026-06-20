<?php require_once '../app/views/templates/header.php'; ?>
<?php require_once '../app/views/templates/sidebar.php'; ?>

<div id="main">
    <div class="page-heading">
        <h3>Edit Data Guru: <?= htmlspecialchars($data['guru']->nama_guru); // Gunakan htmlspecialchars 
                            ?></h3>
    </div>
    <div class="page-content">
        <?php Flasher::flash(); // Tampilkan flash message 
        ?>
        <div class="card">
            <div class="card-body">
                <form action="<?= BASEURL; ?>/guru/prosesUpdate" method="post">
                    <input type="hidden" name="id_guru" value="<?= $data['guru']->id_guru; ?>">

                    <div class="form-group mb-3">
                        <label for="nama_guru" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nama_guru" name="nama_guru" value="<?= htmlspecialchars($data['guru']->nama_guru); ?>" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="nik" class="form-label">NIK</label>
                        <input type="text" class="form-control" id="nik" name="nik" value="<?= htmlspecialchars($data['guru']->nik ?? ''); ?>">
                    </div>
                    <div class="form-group mb-3">
                        <label for="nuptk" class="form-label">NUPTK</label>
                        <input type="text" class="form-control" id="nuptk" name="nuptk" value="<?= htmlspecialchars($data['guru']->nuptk ?? ''); ?>">
                    </div>
                    <div class="form-group mb-3">
                        <label for="alamat" class="form-label">Alamat</label>
                        <textarea class="form-control" id="alamat" name="alamat"><?= htmlspecialchars($data['guru']->alamat ?? ''); ?></textarea>
                    </div>
                    <div class="form-group mb-3">
                        <label for="no_hp_guru" class="form-label">No. HP</label>
                        <input type="text" class="form-control" id="no_hp_guru" name="no_hp_guru" value="<?= htmlspecialchars($data['guru']->no_hp_guru ?? ''); ?>">
                    </div>
                    <div class="form-group mb-3">
                        <label for="username" class="form-label">Username <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="username" name="username" value="<?= htmlspecialchars($data['guru']->username); ?>" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="password" class="form-label">Password Baru</label>
                        <input type="password" class="form-control" id="password" name="password">
                        <small class="form-text text-muted">Kosongkan jika tidak ingin mengubah password.</small>
                    </div>

                    <div class="form-group mb-3 border p-3 rounded">
                        <label class="form-label fw-bold">Level / Hak Akses</label>
                        <div class="mt-2">
                            <?php if (isset($data['all_levels']) && !empty($data['all_levels'])) : ?>
                                <?php foreach ($data['all_levels'] as $level) : ?>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input"
                                            type="checkbox"
                                            name="levels[]"
                                            value="<?= $level->id_level; ?>"
                                            id="level_<?= $level->id_level; ?>"
                                            <?php
                                            // Cek apakah ID level ini ada di array level guru saat ini
                                            if (isset($data['current_level_ids']) && in_array($level->id_level, $data['current_level_ids'])) {
                                                echo 'checked';
                                            }
                                            ?>>
                                        <label class="form-check-label" for="level_<?= $level->id_level; ?>">
                                            <?= htmlspecialchars(ucfirst($level->level)); // Tampilkan nama level 
                                            ?>
                                        </label>
                                    </div>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <p class="text-muted">Tidak ada data level ditemukan.</p>
                            <?php endif; ?>
                        </div>
                        <small class="form-text text-muted d-block mt-2">Pilih satu atau lebih level untuk guru ini.</small>
                    </div>
                    <button type="submit" class="btn btn-primary mt-3">Simpan Perubahan</button>
                    <a href="<?= BASEURL; ?>/guru/daftar" class="btn btn-secondary mt-3">Batal</a>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once '../app/views/templates/footer.php'; ?>