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
        $sql = 'SELECT * FROM dokter';

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
