<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        // Load model yang barusan kita buat
        $this->load->model('m_login');
    }

    public function index()
    {
        $this->load->view('v_login');
    }

    public function proses_login()
    {
        // 1. Tangkap inputan dari form
        $username = $this->input->post('username');
        $password = $this->input->post('password');
        
        // 2. Bungkus data untuk dicek ke database
        // PENTING: Password diinput user harus di-MD5 juga biar cocok sama database
        $where = array(
            'username' => $username,
            'password' => md5($password) 
        );

        // 3. Cek ke database lewat Model
        $cek = $this->m_login->cek_login("tb_user", $where)->num_rows();

        if($cek > 0){
            // == JIKA BENAR (User Ditemukan) ==
            
            // Buat "Tiket Masuk" (Session)
            $data_session = array(
                'nama' => $username,
                'status' => "login"
            );
            
            // Simpan tiket ini di penyimpanan browser/server
            $this->session->set_userdata($data_session);
 
            // Tendang user ke halaman Mahasiswa (Dashboard)
            redirect(base_url("mahasiswa"));
 
        }else{
            // == JIKA SALAH ==
            echo "Username atau Password salah! <a href='".base_url('auth')."'>Coba lagi</a>";
        }
    }
    
    // Fungsi untuk keluar (Logout)
    public function logout()
    {
        $this->session->sess_destroy(); // Hancurkan tiket
        redirect(base_url('auth')); // Balik ke halaman login
    }
}