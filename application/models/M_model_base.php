<?php

/*
 * By : Praditya Kurniawan
 * website : http://masiyak.com
 * email : aku@masiyak.com
 *
 */

class M_model_base extends CI_Model
{

    public $pesan_error = array(
        1451 => 'Data sedang digunakan oleh sistem.  Silakan periksa kembali penggunaan data.',
    );

    public function __construct()
    {
        parent::__construct();
    }

    public function tambah_data($tabel, $data)
    {
        if (empty($tabel) || empty($data)) {
            return false;
        }

        return $this->db->insert($tabel, $data);
    }

    public function tambah_data_batch($tabel, $data)
    {
        if (empty($tabel) || empty($data)) {
            return false;
        }

        return $this->db->insert_batch($tabel, $data);
    }

    public function ubah_data($tabel, $kolom, $id = null, $data)
    {
        if (empty($tabel) || empty($data) || empty($kolom)) {
            return false;
        }

        if (is_array($kolom)) {
            $this->db->update($tabel, $data, $kolom);
            return $this->db->affected_rows();
        }
        $this->db->where($kolom, $id);
        $this->db->update($tabel, $data);
        return $this->db->affected_rows();
    }

    public function hapus_data($tabel, $parameter)
    {
        if (empty($tabel) or empty($parameter)) {
            return false;
        }

        return $this->db->delete($tabel, $parameter);
    }

    public function get_error_message()
    {
        $get_error = $this->db->error();
        if (isset($this->pesan_error[$get_error['code']])) {
            return $this->pesan_error[$get_error['code']];
        } else {
            return $get_error['message'];
        }
    }

    public function get_db_error()
    {
        return $this->db->error();
    }

    public function id_terakhir()
    {
        return $this->db->insert_id();
    }

    public function get_list_select2($id, $kolom, $tabel, $where = null)
    {
        $dt     = $this->input->post();
        $term   = "%" . $dt['q'] . "%";
        $params = [$term];

        $sql = "SELECT " . $id . " AS id, " . $kolom . " AS text
        FROM " . $tabel . "
        ";

        if (!empty($where) and isset($dt['id'])) {
            if (empty($dt['id'])) {
                $dt['id'] = 0;
            }
            $sql .= ' WHERE ' . $where . ' = ' . $dt['id'];
        }

        $sql .= ' HAVING text LIKE ? ORDER BY '.$kolom;

        return $this->get_select2_data($sql, $params);
    }

    /*protected function get_select2($sql, $params)
    {
        $dt = $this->input->post();

        $data['total_record'] = $this->db->query($sql, $params)->num_rows();

        $start  = ($dt['page'] - 1) * $dt['page_limit'];
        $length = $dt['page_limit'];
        $sql .= " LIMIT {$start}, {$length}";

        $query = $this->db->query($sql, $params);

        $data['more'] = $data['total_record'] > $dt['page'] * $dt['page_limit'];

        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            $data['items'] = $result;
            return $data;
        } else {
            $data['items'] = [];
            return $data;
        }
    }*/

    // function get_list_select2($id, $kolom, $tabel)
    // {
    //     $dt     = $this->input->post();
    //     $term   = "%" . $dt['q'] . "%";
    //     $params = [$term];

    //     $sql    = "SELECT " . $id . " AS id, " . $kolom . " AS text
    //     FROM " . $tabel . "
    //     ";
    //     $sql .= ' HAVING text LIKE ?';

    //     return $this->get_select2_data($sql, $params);
    // }
    
    protected function get_select2_data($sql, $params)
    {
        $dt = $this->input->post();

        $data['total_record'] = $this->db->query($sql, $params)->num_rows();

        $start  = ($dt['page'] - 1) * $dt['page_limit'];
        $length = $dt['page_limit'];
        $sql .= " LIMIT {$start}, {$length}";

        $query = $this->db->query($sql, $params);

        $data['more'] = $data['total_record'] > $dt['page'] * $dt['page_limit'];

        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            $data['items'] = $result;
            return $data;
        } else {
            $data['items'] = [];
            return $data;
        }
    }

    public function get_token($user_id)
    {
        $sql = 'SELECT token
                FROM sys_user_token
                WHERE user_id = ?';

        $query = $this->db->query($sql, $user_id);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result['token'];
        } else {
            return null;
        }
    }
}
