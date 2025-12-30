<?php
class M_mahasiswa extends CI_Model {
    
    // UBAH 1: Tampilkan hanya yang is_active = 1
    public function tampil_data() {
        $this->db->where('is_active', 1); // Tambahkan filter ini
        return $this->db->get('tb_mahasiswa');
    }

    public function input_data($data) {
        $this->db->insert('tb_mahasiswa', $data);
    }

    // UBAH 2: Ubah fungsi hapus menjadi update
   public function hapus_data($where, $table)
    {
        // Set is_active jadi 0 (Soft Delete)
        $data = array('is_active' => 0);
        
        $this->db->where($where);
        $this->db->update($table, $data); 
        // Pastikan pakai UPDATE, bukan DELETE
    }

    public function edit_data($where) {
        return $this->db->get_where('tb_mahasiswa', $where);
    }

    public function update_data($where, $data) {
        $this->db->where($where);
        $this->db->update('tb_mahasiswa', $data);
    }

    // --- FUNGSI BARU UTK SEARCH & PAGINATION ---

    // 1. Ambil data dengan Limit (Paginasi) & Filter Keyword (Pencarian)
   // 1. Ambil data dengan Limit, Filter Keyword, DAN Filter Active
    public function get_data_page($limit, $start, $keyword = null) {
        // --- FILTER WAJIB: HANYA YANG AKTIF ---
        $this->db->where('is_active', 1);
        // --------------------------------------

        if ($keyword) {
            // Kita pakai group_start() dan group_end()
            // Ini fungsinya seperti tanda kurung di matematika: (a OR b OR c)
            // Supaya logika pencarian tidak merusak filter is_active
            $this->db->group_start(); 
                $this->db->like('nama', $keyword);
                $this->db->or_like('nim', $keyword);
                $this->db->or_like('jurusan', $keyword);
            $this->db->group_end();
        }
        
        return $this->db->get('tb_mahasiswa', $limit, $start)->result();
    }

    // 2. Hitung Total Data (Hanya yang aktif saja)
    public function count_all_data($keyword = null) {
        // --- FILTER WAJIB: HANYA YANG AKTIF ---
        $this->db->where('is_active', 1);
        // --------------------------------------

        if ($keyword) {
            $this->db->group_start();
                $this->db->like('nama', $keyword);
                $this->db->or_like('nim', $keyword);
                $this->db->or_like('jurusan', $keyword);
            $this->db->group_end();
        }
        
        return $this->db->from('tb_mahasiswa')->count_all_results();
    }

}