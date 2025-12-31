<!DOCTYPE html>
<html>

<head>
    <title>Aplikasi CRUD Keren</title>

    <link rel="shortcut icon" href="<?php echo base_url('assets/img/logo.png'); ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h1>Daftar Mahasiswa (Versi Develop)</h1>
        <p>Halo, <b><?php echo $this->session->userdata("nama"); ?></b></p>

        <a href="<?php echo base_url('auth/logout'); ?>">Logout (Keluar)</a>

        <hr>
        <a href="<?php echo base_url('mahasiswa/tambah'); ?>" class="btn btn-success mb-3">+ Tambah Data</a>
        <div class="row" style="margin-bottom: 20px;">
            <div class="col-md-6">
                <form action="<?php echo base_url('mahasiswa'); ?>" method="post">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Cari Nama/NIM..." name="keyword" autocomplete="off" autofocus>
                        <div class="input-group-append">
                            <input class="btn btn-primary" type="submit" name="submit" value="Cari">

                            <a class="btn btn-secondary" href="<?php echo base_url('mahasiswa/reset'); ?>">Reset</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>NIM</th>
                    <th>Jurusan</th>
                    <th>Foto</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = $this->uri->segment(3) + 1;
                foreach ($mahasiswa as $mhs): ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><?php echo $mhs->nama; ?></td>
                        <td><?php echo $mhs->nim; ?></td>
                        <td><?php echo $mhs->jurusan; ?></td>
                        <td>
                            <img src="<?php echo base_url('uploads/' . $mhs->foto); ?>" width="40px" height="40px" style="object-fit: cover; border-radius: 50%;">
                        </td>
                        <td>
                            <a href="<?php echo base_url('mahasiswa/edit/' . $mhs->id); ?>" class="btn btn-sm btn-warning">Edit</a>
                            <a href="<?php echo base_url('mahasiswa/hapus/' . $mhs->id); ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus?');">Hapus</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div class="row">
            <div class="col">
                <?php echo $this->pagination->create_links(); ?>
            </div>
        </div>
    </div>
</body>

</html>