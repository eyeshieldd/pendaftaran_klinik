<?php

/*
 * By : Praditya Kurniawan
 * website : http://masiyak.com
 * email : aku@masiyak.com
 *
 * - CIRCLE LABS - 
 * http://circlelabs.id
 *  
 */

require_once APPPATH . 'models/M_model_base.php';

class M_backend extends M_model_base {

    public function __construct() {
        parent::__construct();
    }

    /*
     * Menu portal
     */

    // ambil detail portal
    function get_detail_portal_id($params) {
        $sql = 'SELECT portal_id, portal_name, portal_number, portal_link, portal_desc, meta_title, meta_desc, meta_tag, mdd
                FROM sys_portal WHERE portal_id = ?';
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    function portal_number_is_exist($no, $portal_id) {
        $par[] = $no;
        $sql = "SELECT COUNT(portal_id) AS hasil FROM sys_portal WHERE portal_number = ?";
        if (!empty($portal_id)) {
            $par[] = $portal_id;
            $sql .= " AND portal_id != ?";
        }

        return $this->db->query($sql, $par)->row()->hasil > 0 ? TRUE : FALSE;
    }

    /*
     * Menu Grup
     */

    // ambil detail portal
    function get_detail_group_id($params) {
        $sql = 'SELECT group_id, group_name, group_desc, group_portal, group_restricted, mdd
                FROM sys_group WHERE group_id = ?';
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    /*
     * Menu User
     */

    function get_list_user_group() {
        $sql = 'SELECT group_id, group_name FROM sys_group';
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    // get all user and group
    function get_all_user_group() {
        $sql = 'SELECT sug.user_id, sug.group_id, sug.default, sg.group_name FROM sys_user_group sug LEFT JOIN sys_group sg USING(group_id)';
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    function get_list_user_dttabel() {
        // menentukan kolom pencarian
        $kolom_cari = 'nama_lengkap, username';
        // buat query
        $sql = "SELECT user_id, nama_lengkap, username, status FROM sys_user";

        // hitung jumlah data seluruh tabel
        $exesql = $this->db->query($sql);
        $option['recordsTotal'] = $exesql->num_rows();
        $exesql->free_result();

        // jika kolom pencarian diisi
        $cari = $this->input->post('search')['value'] != '' ? $this->input->post('search')['value'] : '';

        // jika kolom cari ada
        if (!empty($cari) && !empty($kolom_cari)) {
            $sql .= ' WHERE ';
            // get list colomn
            $lskolom = (!is_array($kolom_cari)) ? explode(',', $kolom_cari) : $kolom_cari;
            // var untuk identifikasi kolom awal
            $ikolom = 1;
            foreach ($lskolom as $vkolom) {
                $sql .= "{$vkolom} LIKE '%{$this->db->escape_like_str($cari)}%' ESCAPE '!'";
                if ($ikolom < count($lskolom)) {
                    $sql .= ' OR ';
                }
                $ikolom++;
            }
        }
        // hitung jumlah data sesuai pencarian
        $exesql = $this->db->query($sql);
        $option['recordsFiltered'] = $exesql->num_rows();
        $exesql->free_result();

        // limit data
        if ($this->input->post('lenght') > -1)
            $sql .= " LIMIT " . $this->input->post('start') . ', ' . $this->input->post('length');
        $option['draw'] = $this->input->post('draw');
        $option['data'] = $this->db->query($sql)->result_array();

        $sql2 = 'SELECT us.user_id, sug.`default`, sg.group_name, sug.group_id FROM sys_user_group sug
                LEFT JOIN (' . $sql . ') us USING(user_id)
                LEFT JOIN sys_group sg USING(group_id)';

        foreach ($this->db->query($sql2)->result_array() as $voptgrup) {
            $option['option_grup'][$voptgrup['user_id']][] = $voptgrup;
        }

        return $option;
    }

    function tambah_user($user_data = NULL, $user_group = array()) {
        $this->db->trans_begin();
        $def = 1;
        foreach ($user_group as $i => $vgroup) {
            $guser[$i]['user_id'] = $user_data['user_id'];
            $guser[$i]['group_id'] = $vgroup;
            $guser[$i]['default'] = $def == 1 ? 1 : 0;
            $def++;
        }

        $this->tambah_data('sys_user', $user_data);
        $this->tambah_data_batch('sys_user_group', $guser);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return FALSE;
        } else {
            $this->db->trans_commit();
            return TRUE;
        }
    }

    function ubah_user($user_data = NULL, $user_group = array()) {
        $this->db->trans_begin();

        // get default group
        $sql = 'SELECT sug.group_id FROM sys_user_group sug WHERE user_id = ? AND sug.default = 1';
        $kueri = $this->db->query($sql, array($this->input->post('user_id')))->row_array();

        foreach ($user_group as $i => $vgroup) {
            $guser[$i]['user_id'] = $this->input->post('user_id');
            $guser[$i]['group_id'] = $vgroup;
            if (isset($kueri['group_id']))
                $guser[$i]['default'] = $kueri['group_id'] == $vgroup ? 1 : 0;
        }

        $this->ubah_data('sys_user', 'user_id', $this->input->post('user_id'), $user_data);
        $this->hapus_data('sys_user_group', array('user_id' => $this->input->post('user_id')));
        $this->tambah_data_batch('sys_user_group', $guser);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return FALSE;
        } else {
            $this->db->trans_commit();
            return TRUE;
        }
    }

    /// detail user
    function get_detail_user_id($params) {
        $sql = 'SELECT user_id, username, nama_lengkap, telepon, email, jenis_kelamin, status, last_login, mdd FROM sys_user WHERE user_id = ?';
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    /// detail group by user id
    function get_group_by_user_id($params) {
        $sql = 'SELECT sg.group_name, sg.group_id
                FROM sys_user_group usg
                LEFT JOIN sys_group sg USING(group_id)
                WHERE usg.user_id = ?';
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    function ubah_default_grup($user_id, $grup_id) {
        $this->db->trans_begin();
        $kueri = 'UPDATE sys_user_group sug SET sug.default = 0 WHERE sug.user_id = ?';
        $this->db->query($kueri, $user_id);

        $kueri2 = 'UPDATE sys_user_group sug SET sug.default = 1 WHERE sug.user_id = ? AND sug.group_id = ?';
        $this->db->query($kueri2, array($user_id, $grup_id));

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return FALSE;
        } else {
            $this->db->trans_commit();
            return TRUE;
        }
    }

    // get list all portal
    function get_all_portal() {
        $sql = 'SELECT portal_id, portal_name FROM sys_portal';
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    // get list all portal
    function get_all_usergroup() {
        $sql = 'SELECT group_id, group_name FROM sys_group';
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    function get_list_config_dttabel() {
        // menentukan kolom pencarian
        $kolom_cari = 'cf.config_name, cf.config_group, cf.config_value, cf.config_desc, p.portal_name';
        // buat query
        $sql = "SELECT cf.config_id, cf.config_name, cf.config_group, cf.config_value, cf.config_desc,
                p.portal_name AS config_portal, cf.restricted
                FROM sys_config cf LEFT JOIN sys_portal p ON cf.config_portal_id = p.portal_id";

        // hitung jumlah data seluruh tabel
        $exesql = $this->db->query($sql);
        $option['recordsTotal'] = $exesql->num_rows();
        $exesql->free_result();

        // jika kolom pencarian diisi
        $cari = $this->input->post('search')['value'] != '' ? $this->input->post('search')['value'] : '';

        // jika kolom cari ada
        if (!empty($cari) && !empty($kolom_cari)) {
            $sql .= ' WHERE ';
            // get list colomn
            $lskolom = (!is_array($kolom_cari)) ? explode(',', $kolom_cari) : $kolom_cari;
            // var untuk identifikasi kolom awal
            $ikolom = 1;
            foreach ($lskolom as $vkolom) {
                $sql .= "{$vkolom} LIKE '%{$this->db->escape_like_str($cari)}%' ESCAPE '!'";
                if ($ikolom < count($lskolom)) {
                    $sql .= ' OR ';
                }
                $ikolom++;
            }
        }
        // hitung jumlah data sesuai pencarian
        $exesql = $this->db->query($sql);
        $option['recordsFiltered'] = $exesql->num_rows();
        $exesql->free_result();

        // limit data
        if ($this->input->post('lenght') > -1)
            $sql .= " LIMIT " . $this->input->post('start') . ', ' . $this->input->post('length');
        $option['draw'] = $this->input->post('draw');
        $option['data'] = $this->db->query($sql)->result_array();

        return $option;
    }

    /// detail user
    function get_detail_config_id($params) {
        $sql = 'SELECT config_id, config_name, config_group, config_value, config_desc,'
                . 'restricted, config_portal_id, restricted mdd FROM sys_config WHERE config_id = ?';
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    /*
     *  Fungsi dalam modul portal
     */

    // get list menu by portal id with indentation
    function get_list_menu_select_by_portal_id($params) {
        // variabel data
        $data = array();
        // get parent list menu by id
        foreach ($this->_get_parent_menu_by_portal_id($params) as $dt_menu) {
            $data[] = $dt_menu;
            // get child if exist
            $data = $this->_get_child($dt_menu['menu_id'], $data, 0);
        }
        return $data;
    }

    // ambil menu paling atas (parent)
    function _get_parent_menu_by_portal_id($params) {
        $sql = "SELECT * FROM sys_menu
                WHERE menu_parent = 0 AND portal_id = ? ORDER BY menu_order ASC";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    // fungsi rekursif untuk ambil data child
    function _get_child($menu_id, $data, $level) {
        // counter level
        $level = $level + 1;
        // get child menu
        $child_menu = $this->_get_child_menu_by_parent_id($menu_id);
        // jika tidak ada child menu, kembalikan nilai data.
        if (empty($child_menu))
            return $data;
        // jika terdapat child menu, cek child menu lagi (rekursif)
        foreach ($child_menu as $dt_child_menu) {
            $indent = '';
            for ($i = 0; $i < $level; $i++)
                $indent .= '--';
            $indent .= '&nbsp;';
            $dt_child_menu['menu_name'] = $indent . ' ' . $dt_child_menu['menu_name'];
            $data[] = $dt_child_menu;
            $data = $this->_get_child($dt_child_menu['menu_id'], $data, $level);
        }
        return $data;
    }

    function _get_child_menu_by_parent_id($params) {
        $sql = "SELECT * FROM sys_menu
                WHERE menu_parent = ? ORDER BY menu_order ASC";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    function get_detail_menu($params) {
        $sql = 'SELECT menu_id, portal_id, menu_name, menu_desc, menu_link, menu_position, menu_parent, menu_order,
                menu_show, menu_icon, menu_fonticon, mdb, mdd FROM sys_menu WHERE menu_id = ?';
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    // get list menu by portal id with indentation
    function get_list_menu_permission_by_portal_id($params) {
        // variabel data
        $data = array();
        // get parent list menu by id
        foreach ($this->_get_parent_menu_by_portal_id_permission($params) as $dt_menu) {
            $data[] = $dt_menu;
            // get child if exist
            $data = $this->_get_child_permission($params[0], $dt_menu['menu_id'], $data, 0);
        }
        return $data;
    }

    /*
     *  PRIVATE FUNCTION
     */

    // ambil menu paling atas (parent)
    function _get_parent_menu_by_portal_id_permission($params) {
        $sql = "SELECT a.menu_id, a.menu_name, a.menu_desc, SUBSTR(b.permission,1,1)'create', SUBSTR(b.permission,2,1)'read', 
                SUBSTR(b.permission,3,1)'update', SUBSTR(b.permission,4,1)'delete' 
                FROM sys_menu a
                LEFT JOIN (
			SELECT * FROM sys_permission WHERE group_id = ?
                )b ON b.menu_id = a.menu_id
                WHERE a.menu_parent = 0 AND a.portal_id = ? ORDER BY a.menu_order ASC";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    // fungsi rekursif untuk ambil data child
    function _get_child_permission($group_id, $menu_id, $data, $level) {
        // counter level
        $level = $level + 1;
        // get child menu
        $child_menu = $this->_get_child_menu_by_parent_id_permission(array($group_id, $menu_id));
        // jika tidak ada child menu, kembalikan nilai data.
        if (empty($child_menu))
            return $data;
        // jika terdapat child menu, cek child menu lagi (rekursif)
        foreach ($child_menu as $dt_child_menu) {
            $indent = '';
            for ($i = 0; $i < $level; $i++)
                $indent .= '-- ';
            $indent .= '&nbsp;';
            $dt_child_menu['menu_name'] = $indent . ' ' . $dt_child_menu['menu_name'];
            $data[] = $dt_child_menu;
            $data = $this->_get_child_permission($group_id, $dt_child_menu['menu_id'], $data, $level);
        }
        return $data;
    }

    function _get_child_menu_by_parent_id_permission($params) {
        $sql = "SELECT a.menu_id, a.menu_name, a.menu_desc, SUBSTR(b.permission,1,1)'create', SUBSTR(b.permission,2,1)'read', 
                SUBSTR(b.permission,3,1)'update', SUBSTR(b.permission,4,1)'delete' 
                FROM sys_menu a
                LEFT JOIN (
			SELECT * FROM sys_permission WHERE group_id = ?
                )b ON b.menu_id = a.menu_id
                WHERE a.menu_parent = ? ORDER BY a.menu_order ASC";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    // set permission
    function proses_hak_akses($data, $portal, $grup) {
        $this->db->trans_begin();
        $sql = "DELETE a FROM sys_permission a
                INNER JOIN sys_menu b USING (menu_id)
                WHERE b.portal_id = ? AND a.group_id = ?";
        $this->db->query($sql, array($portal, $grup));
        $this->tambah_data_batch('sys_permission', $data);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return FALSE;
        } else {
            $this->db->trans_commit();
            return TRUE;
        }
    }

    /*
     * Get total data
     */

    function get_total_portal() {
        $sql = 'SELECT COUNT(portal_id) AS jumlah FROM sys_portal';
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result['jumlah'];
        } else {
            return 0;
        }
    }

    function get_total_menu() {
        $sql = 'SELECT COUNT(menu_id) AS jumlah FROM sys_menu';
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result['jumlah'];
        } else {
            return 0;
        }
    }

    function get_total_grup() {
        $sql = 'SELECT COUNT(group_id) AS jumlah FROM sys_group';
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result['jumlah'];
        } else {
            return 0;
        }
    }

    function get_total_user() {
        $sql = 'SELECT COUNT(user_id) AS jumlah FROM sys_user';
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result['jumlah'];
        } else {
            return 0;
        }
    }

}
