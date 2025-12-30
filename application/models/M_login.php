<?php 
class M_login extends CI_Model{	
    // Fungsi untuk mengecek apakah username & password ada di tabel
	function cek_login($table, $where){		
		return $this->db->get_where($table, $where);
	}
}