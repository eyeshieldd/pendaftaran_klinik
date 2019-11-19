<?php

/*
 * By : Praditya Kurniawan
 * website : http://masiyak.com
 * email : aku@masiyak.com
 *
 */

class M_site extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    /*
     *  GET SIDEBAR PARENT MENU
     */

    public function get_parent_menu($params)
    {
        $query = $this->db->select('DISTINCT(a.menu_id), a.portal_id, a.menu_name, a.menu_desc, a.menu_position, a.menu_order, a.menu_parent,
                a.menu_link, a.menu_icon, a.menu_fonticon , b.permission')
            ->from('sys_menu a')
            ->join('sys_permission b', 'b.menu_id=a.menu_id', 'inner')
            ->join('sys_portal c', 'c.portal_id=a.portal_id', 'inner')
            ->where_in('b.group_id', $params[0])
            ->where('a.menu_position', 'lfm')
            ->where('a.menu_parent', $params[1])
            ->where('SUBSTRING(b.permission,2,1)', 1)
            ->where('c.portal_number', $params[2])
            ->where('a.menu_show', 1)
//                ->group_by('a.menu_id')
            ->order_by('a.menu_order', 'ASC')
            ->get();
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    public function get_child_menu_by_parent($params)
    {
        $query = $this->db->select('a.*, b.permission')
            ->from('sys_menu a')
            ->join('sys_permission b', 'b.menu_id = a.menu_id', 'inner')
            ->where(array(
                'b.group_id'                  => $params[0],
                'b.menu_position'             => 'left_sidebar',
                'b.menu_parent'               => $params[1],
                'SUBSTRING(b.permission,2,1)' => 1,
                'a.menu_portal'               => $params[2],
                'a.menu_show'                 => 1,
            ))->order_by('a.menu_order', 'ASC')->get();
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    // fungsi ambil permission
    public function get_page_role_level($params)
    {
        $query = $this->db->select('a.menu_id, b.permission')
            ->from('sys_menu a')
            ->join('sys_permission b', 'a.menu_id = b.menu_id')
            ->where('a.menu_link', $params[0])
            ->where_in('b.group_id', $params[1])
            ->get();
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    // fungsi ambil permission
    public function get_id_menu($params)
    {
        $query = $this->db->select('a.menu_id, b.permission')
            ->from('sys_menu a')
            ->join('sys_permission b', 'b.menu_id = a.menu_id', 'inner')
            ->where('a.menu_link', $params[0])
            ->where('b.group_id', $params[1])
            ->get();
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    public function get_detail_link_menu_by_name($params)
    {
        $query = $this->db->select('menu_name, menu_desc, menu_parent, menu_link, menu_icon, menu_fonticon')
            ->from('sys_menu')
            ->where('menu_link', $params)
            ->get();
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    public function get_detail_link_menu_by_id($params)
    {
        $query = $this->db->select('menu_name, menu_desc, menu_parent, menu_link, menu_icon, menu_fonticon ')
            ->from('sys_menu')
            ->where('menu_id', $params)
            ->get();
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    public function get_web_config()
    {
        $query = $this->db->select('config_name, config_value')
            ->from('sys_config')
            ->where('config_group', 'web_config')
            ->get();
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    /*
     * UNTUK MENU PUBLIC
     *
     */

    public function get_menu_public($params)
    {
        $sql = "SELECT sm.menu_id, sm.menu_parent, sm.menu_name, sm.menu_desc, sm.menu_link
                FROM sys_menu sm
                INNER JOIN sys_portal sp ON sp.portal_id = sm.menu_portal
                WHERE sp.portal_id = ? AND sm.menu_parent = ? AND sm.menu_position = 'top_menu'
                AND sm.menu_show = 1
                ORDER BY sm.menu_order";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    public function get_list_profil()
    {
        $sql = "SELECT judul, slug, deskripsi
                FROM post p
                WHERE p.katslug = 'profil' AND DATE(p.published) <= DATE(NOW()) AND p.status = 1
                ORDER BY published DESC
                LIMIT 10";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    public function get_list_layanan()
    {
        $sql = "SELECT judul, slug, deskripsi
                FROM post p
                WHERE p.katslug = 'layanan' AND DATE(p.published) <= DATE(NOW()) AND p.status = 1
                ORDER BY published DESC
                LIMIT 10";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    public function get_list_produk()
    {
        $sql = "SELECT judul, slug, deskripsi
                FROM post p
                WHERE p.katslug = 'produk' AND DATE(p.published) <= DATE(NOW()) AND p.status = 1
                ORDER BY published DESC
                LIMIT 10";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    public function get_list_info()
    {
        $sql = "SELECT kategori, katslug
                FROM post p
                WHERE p.katslug != 'profil' AND p.katslug != 'produk' AND p.katslug != 'layanan' AND p.katslug != 'karir'
                AND DATE(p.published) <= DATE(NOW()) AND p.status = 1
                GROUP BY katslug
                ORDER BY published DESC";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    public function get_list_widget()
    {
        $sql   = "SELECT nama_widget, gambar, keterangan, telepon FROM widget";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    public function get_slider()
    {
        $sql   = "SELECT slider_id, original FROM slider WHERE status = 1 ORDER BY ordering ASC";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    public function get_link_terkait()
    {
        $this->db->cache_on();
        $query = $this->db->select('nama_link, link, tipe')
            ->from('link_terkait')
            ->get();
        $this->db->cache_off();
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

}
