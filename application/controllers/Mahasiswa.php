<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mahasiswa extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('M_mahasiswa');
        $this->load->helper('url');

        // === TAMBAHKAN KODE INI (SATPAM) ===
        // Cek apakah status session-nya BUKAN "login"?
        if ($this->session->userdata('status') != "login") {
            // Kalau belum login, tendang balik ke halaman login
            redirect(base_url("auth"));
        }
        // ===================================
    }

    // R - READ
    public function index()
    {
        // 1. Load Library Pagination
        $this->load->library('pagination');

        // 2. Ambil Keyword Pencarian (Simpan di Session biar gak hilang saat pindah halaman)
        if ($this->input->post('submit')) {
            $data['keyword'] = $this->input->post('keyword');
            $this->session->set_userdata('keyword', $data['keyword']);
        } else {
            // Kalau tombol cari tidak ditekan, cek apakah ada simpanan keyword di session?
            $data['keyword'] = $this->session->userdata('keyword');
        }

        // 3. Konfigurasi Pagination
        // Hitung dulu jumlah data yang sesuai keyword
        $config['total_rows'] = $this->M_mahasiswa->count_all_data($data['keyword']);
        $config['per_page'] = 5; // Kita tampilkan 5 data per halaman (Bisa diubah)

        // Setting URL (Penting!)
        $config['base_url'] = base_url('mahasiswa/index');

        // === STYLING BOOTSTRAP (Biar tombolnya cantik) ===
        $config['full_tag_open']    = '<nav><ul class="pagination justify-content-center">';
        $config['full_tag_close']   = '</ul></nav>';

        $config['first_link']       = 'First';
        $config['first_tag_open']   = '<li class="page-item"><span class="page-link">';
        $config['first_tag_close']  = '</span></li>';

        $config['last_link']        = 'Last';
        $config['last_tag_open']    = '<li class="page-item"><span class="page-link">';
        $config['last_tag_close']   = '</span></li>';

        $config['next_link']        = '&raquo';
        $config['next_tag_open']    = '<li class="page-item"><span class="page-link">';
        $config['next_tag_close']   = '</span></li>';

        $config['prev_link']        = '&laquo';
        $config['prev_tag_open']    = '<li class="page-item"><span class="page-link">';
        $config['prev_tag_close']   = '</span></li>';

        $config['cur_tag_open']     = '<li class="page-item active"><span class="page-link">';
        $config['cur_tag_close']    = '</span></li>';

        $config['num_tag_open']     = '<li class="page-item"><span class="page-link">';
        $config['num_tag_close']    = '</span></li>';
        // =================================================

        $this->pagination->initialize($config);

        // 4. Ambil Data Sesuai Halaman
        $data['start'] = $this->uri->segment(3); // Mengambil angka halaman dari URL
        $data['mahasiswa'] = $this->M_mahasiswa->get_data_page($config['per_page'], $data['start'], $data['keyword']);

        $this->load->view('v_tampil', $data);
    }
    // C - CREATE (Menampilkan Form)
    public function tambah()
    {
        $this->load->view('v_input');
    }

    // C - CREATE (Aksi Simpan)
    public function tambah_aksi()
    {
        // 1. Buat Aturan (Rules)
        $this->form_validation->set_rules('nama', 'Nama Mahasiswa', 'required');
        // is_unique[nama_tabel.nama_kolom]
        $this->form_validation->set_rules(
            'nim',
            'NIM',
            'required|numeric|is_unique[tb_mahasiswa.nim]',
            array('is_unique' => 'NIM ini sudah terdaftar, pakai yang lain!') // Pesan error custom
        );

        $this->form_validation->set_rules('jurusan', 'Jurusan', 'required');

        // --- JANGAN LUPA RULE TANGGAL LAHIR ---
        $this->form_validation->set_rules('tgl_lahir', 'Tanggal Lahir', 'required');

        // 2. Jalankan Pengecekan
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('v_input');
        } else {
            // == JIKA LOLOS VALIDASI ==

            $nama = $this->input->post('nama');
            $nim = $this->input->post('nim');
            $tgl_lahir = $this->input->post('tgl_lahir');
            $jurusan = $this->input->post('jurusan');

            // --- LOGIKA UPLOAD FOTO DI SINI ---
            $foto = 'default.jpg'; // 1. Siapkan foto cadangan (kalo user gak upload)

            // 2. Cek apakah user memilih file?
            if (!empty($_FILES['foto']['name'])) {

                // Settingan Upload
                $config['upload_path']   = './uploads/';         // Mau ditaruh mana?
                $config['allowed_types'] = 'jpg|jpeg|png|gif';   // File apa yang boleh?
                $config['max_size']      = '2048';               // Maksimal 2MB (2048 KB)

                // Panggil Library Upload bawaan CI3
                $this->load->library('upload', $config);

                // Lakukan Upload!
                if ($this->upload->do_upload('foto')) {
                    // JIKA BERHASIL: Ambil nama file asli yang baru diupload
                    $upload_data = $this->upload->data();
                    $foto = $upload_data['file_name'];
                } else {
                    // JIKA GAGAL (File kegedean/Format salah):
                    echo "Upload Gagal!! <br>";
                    echo $this->upload->display_errors(); // Tampilkan errornya biar tau
                    die(); // Matikan proses biar gak lanjut simpan data
                }
            }

            // 3. Masukkan ke Array Data
            $data = array(
                'nama' => $nama,
                'nim' => $nim,
                'tgl_lahir' => $tgl_lahir,
                'jurusan' => $jurusan,
                'foto' => $foto // Masukkan nama foto (entah itu default.jpg atau hasil upload)
            );

            $this->M_mahasiswa->input_data($data, 'tb_mahasiswa');
            redirect('mahasiswa/index');
        }
    }

    // D - DELETE
    public function hapus($id)
    {
        $where = array('id' => $id);

        // KITA UBAH LOGIKANYA:
        // Jangan hapus foto, karena ini Soft Delete.
        // Biarkan fotonya tetap ada di server, siapa tau nanti datanya mau direstore.

        // Langsung panggil model untuk set is_active = 0
        $this->M_mahasiswa->hapus_data($where, 'tb_mahasiswa');

        // Balik ke halaman utama
        redirect('mahasiswa/index');
    }


    // U - UPDATE (Menampilkan Form Edit)
    public function edit($id)
    {
        $where = array('id' => $id);
        // INI BENAR (Ada 'tb_mahasiswa')
        $data['mahasiswa'] = $this->M_mahasiswa->edit_data($where, 'tb_mahasiswa')->result();
        $this->load->view('v_edit', $data);
    }

    // U - UPDATE (Aksi Update)
    public function update()
    {
        // 1. Tangkap ID dan NIM dari form
        $id = $this->input->post('id');
        $nim_input = $this->input->post('nim');

        // 2. Ambil Data Lama dari Database (Buat perbandingan)
        $where = array('id' => $id);
        $data_lama = $this->M_mahasiswa->edit_data($where, 'tb_mahasiswa')->row();
        $nim_asli = $data_lama->nim;

        // 3. ATURAN VALIDASI NIM (Logika Cerdik)
        // Jika NIM yang diinput BEDA dengan NIM Asli di database...
        if ($nim_input != $nim_asli) {
            // ...Maka Wajib Unik (Cek ke database)
            $rule_nim = 'required|numeric|is_unique[tb_mahasiswa.nim]';
        } else {
            // ...Jika sama, cukup cek wajib isi dan angka saja (Gak perlu cek unik)
            $rule_nim = 'required|numeric';
        }

        // Pasang Rules
        $this->form_validation->set_rules('nim', 'NIM', $rule_nim);
        $this->form_validation->set_rules('nama', 'Nama', 'required');
        $this->form_validation->set_rules('jurusan', 'Jurusan', 'required');
        $this->form_validation->set_rules('tgl_lahir', 'Tanggal Lahir', 'required');

        // 4. JALANKAN VALIDASI
        if ($this->form_validation->run() == FALSE) {
            // JIKA GAGAL VALIDASI:
            // Kembalikan ke halaman edit (panggil fungsi edit lagi)
            $this->edit($id); 
        } else {
            // JIKA LOLOS VALIDASI:
            // (Baru jalankan proses simpan seperti kemarin)

            $nama = $this->input->post('nama');
            $tgl_lahir = $this->input->post('tgl_lahir');
            $jurusan = $this->input->post('jurusan');

            $data = array(
                'nama' => $nama,
                'nim' => $nim_input, // Pakai nim yang baru divalidasi
                'tgl_lahir' => $tgl_lahir,
                'jurusan' => $jurusan
            );

            // --- PROSES UPLOAD FOTO (Copy yang kemarin) ---
            if (!empty($_FILES['foto']['name'])) {
                $config['upload_path']   = './uploads/';
                $config['allowed_types'] = 'jpg|jpeg|png|gif';
                $config['max_size']      = '2048';
                
                // Tambahkan encrypt biar aman
                // $config['encrypt_name'] = TRUE; 

                $this->load->library('upload', $config);

                if ($this->upload->do_upload('foto')) {
                    $upload_data = $this->upload->data();
                    $foto_baru = $upload_data['file_name'];
                    $data['foto'] = $foto_baru;

                    // Hapus Foto Lama
                    $foto_lama = $data_lama->foto; // Kita udah ambil $data_lama di atas
                    if ($foto_lama != 'default.jpg') {
                        $path_lama = './uploads/' . $foto_lama;
                        if (file_exists($path_lama)) {
                            unlink($path_lama);
                        }
                    }
                } else {
                    echo "Gagal Upload: " . $this->upload->display_errors();
                    die();
                }
            }

            // Update Database
            $this->M_mahasiswa->update_data($where, $data, 'tb_mahasiswa');
            redirect('mahasiswa/index');
        }
    }

    public function reset()
    {
        $this->session->unset_userdata('keyword'); // Hapus ingatan pencarian
        redirect('mahasiswa'); // Balik ke halaman utama
    }
}
