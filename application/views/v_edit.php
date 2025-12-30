<!DOCTYPE html>
<html>

<head>
    <title>Edit Data</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5" style="max-width: 500px;">
        <h3>Edit Data Mahasiswa</h3>
        <?php foreach ($mahasiswa as $mhs) { ?>
            <?php echo form_open_multipart('mahasiswa/update'); ?>

            <input type="hidden" name="id" value="<?php echo $mhs->id ?>">

            <div class="mb-3">
                <label>Nama</label>
                <input type="text" name="nama" class="form-control" value="<?php echo $mhs->nama ?>">
            </div>
            <div class="form-group">
                <label>NIM</label>
                <input type="text" name="nim" class="form-control" value="<?php echo $mhs->nim ?>">

                <small class="text-danger"><?php echo form_error('nim'); ?></small>
            </div>
            <div class="mb-3">
                <label>Tanggal Lahir</label>
                <input type="date" name="tgl_lahir" class="form-control" value="<?php echo $mhs->tgl_lahir ?>">
            </div>
            <div class="mb-3">
                <label>Jurusan</label>
                <select name="jurusan" class="form-control">
                    <option value="<?php echo $mhs->jurusan ?>" selected hidden><?php echo $mhs->jurusan ?></option>
                    <option value="Teknik Informatika">Teknik Informatika</option>
                    <option value="Sistem Informasi">Sistem Informasi</option>
                    <option value="Manajemen Informatika">Manajemen Informatika</option>
                </select>
            </div>
            <div class="form-group">
                <label>Foto Saat Ini:</label><br>
                <img src="<?php echo base_url('uploads/' . $mhs->foto); ?>" width="100px" style="margin-bottom: 10px; border: 1px solid #ccc;">

                <br>
                <label>Ganti Foto (Biarkan kosong jika tidak ingin mengganti):</label>
                <input type="file" name="foto" class="form-control">
                <small style="color: grey;">Format: JPG/PNG, Maksimal 2MB</small>
            </div>


            <button type="submit" class="btn btn-success">Update</button>
            <a href="<?php echo base_url('mahasiswa'); ?>" class="btn btn-secondary">Batal</a>
            <?php echo form_close(); ?>
        <?php } ?>
    </div>
</body>

</html>