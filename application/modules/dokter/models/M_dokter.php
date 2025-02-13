<?php
defined('BASEPATH') or exit('No direct script access allowed');

require_once APPPATH . 'models/M_model_base.php';

class M_dokter extends M_model_base
{

    public function __construct()
    {
        parent::__construct();
    }

    public function get_detail_data($par)
    {
        $sql = 'SELECT id_dokter, nama_dokter, kategori, harga, tanggal_beli, ct.status, ct.mdd, u.username
				FROM dokter ct
				LEFT JOIN sys_user u ON u.user_id = ct.mdb 
				WHERE id = ?';

        $query = $this->db->query($sql, $par);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result;
        } else {
            return null;
        }

    }

}
