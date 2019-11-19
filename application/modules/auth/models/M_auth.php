<?php

/*
 * By : Praditya Kurniawan
 * website : http://masiyak.com
 * email : aku@masiyak.com
 * 
 */

class M_auth extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    function get_password_by_user_email($params) {
        $sql = "SELECT user_id, kata_sandi, status FROM sys_user WHERE username = ? OR email = ?";
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    // get detail data login
    function get_detail_data_login($params) {
        $sql = "SELECT su.user_id, su.username, su.nama_lengkap, su.telepon, su.email, su.jenis_kelamin, su.foto, su.`status` , sug.* 
                FROM sys_user su
                LEFT JOIN (
                        SELECT sug.user_id, sug.group_id, sg.group_portal, sg.group_name FROM sys_user_group sug
                    LEFT JOIN sys_group sg USING(group_id)
                    WHERE sug.user_id = ? AND sug.`default` = 1 
                )sug USING (user_id)
                WHERE su.user_id = ?";
        $query = $this->db->query($sql, array($params, $params));
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    function get_user_group($params) {
        $sql = 'SELECT sg.group_id, sg.group_name, sg.group_portal, sg.group_restricted 
                FROM sys_group sg
                LEFT JOIN sys_user_group sug USING(group_id)
                WHERE sug.user_id = ?';
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $query->free_result();
            return $result;
        } else {
            return array();
        }
    }

    // check attempts
    function get_user_attempts($params) {
        $sql = 'SELECT COUNT(login_id) AS total
                FROM sys_login_attempts
                WHERE login = ? AND `time` > ?';
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $query->free_result();
            return $result['total'];
        } else {
            return NULL;
        }
    }


    function delete_login_attempts($params){
        $sql = "DELETE FROM sys_login_attempts WHERE login = ?";
        return $this->db->query($sql, $params);
    }

    function delete_login_attempts_after_day($params){
        $sql = "DELETE FROM sys_login_attempts WHERE `time` < ?";
        return $this->db->query($sql, $params);
    }

    // insert login attempts
    function insert_login_attempts($params) {
        return $this->db->insert('sys_login_attempts', $params);
    }

    // insert auth log
    function insert_auth($params) {
        return $this->db->insert('sys_auth_log', $params);
    }

    function delete_auth($params) {
        $sql = "DELETE FROM sys_auth_log WHERE waktu_login < ?";
        return $this->db->query($sql, $params);
    }

    function update_last_login($params) {
        $sql = "UPDATE sys_user SET last_login = ? WHERE user_id = ?";
        return $this->db->query($sql, $params);
    }

}
