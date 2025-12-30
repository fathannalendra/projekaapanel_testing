<!DOCTYPE html>
<html>

<head>
    <title>Tambah Data</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5" style="max-width: 500px;">
        <h3>Tambah Data Mahasiswa</h3>
        <div style="color: red; font-style: italic;">
            <?php echo validation_errors(); ?>
        </div>
        <?php echo form_open_multipart('mahasiswa/tambah_aksi'); ?>
        <div class="mb-3">
            <label>Nama</label>
            <input type="text" name="nama" class="form-control" value="<?php echo set_value('nama'); ?>">
        </div>
        <div class="mb-3">
            <label>NIM</label>
            <input type="text" name="nim" class="form-control" value="<?php echo set_value('nim'); ?>">
            <small class="text-danger"><?php echo form_error('nim'); ?></small>
        </div>
        <div class="mb-3">
            <label>Tanggal Lahir</label>
            <input type="date" name="tgl_lahir" class="form-control" value="<?php echo set_value('tgl_lahir'); ?>">
        </div>
        <div class="mb-3">
            <label>Jurusan</label>
            <select name="jurusan" class="form-control">
                <option value="Teknik Informatika">Teknik Informatika</option>
                <option value="Sistem Informasi">Sistem Informasi</option>
                <option value="Manajemen Informatika">Manajemen Informatika</option>
            </select>
        </div>
        <div class="form-group">
            <label>Upload Foto</label>
            <input type="file" name="foto" class="form-control">
            <small style="color: grey;">Format: JPG/PNG, Maksimal 2MB</small>
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="<?php echo base_url('mahasiswa'); ?>" class="btn btn-secondary">Kembali</a>
        <?php echo form_close(); ?>
    </div>
</body>

</html>