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

defined('BASEPATH') or exit('No direct script access allowed');

class User_account
{
    public $error_msg = '';

    public $user_id = '';

    /**
     * __get
     *
     * Enables the use of CI super-global without having to define an extra variable.
     *
     * I can't remember where I first saw this, so thank you if you are the original author. -Militis
     *
     * @param    string $var
     *
     * @return    mixed
     */
    public function __get($var)
    {
        return get_instance()->$var;
    }

    // cek username atau email tersedia atau tidak
    public function user_email_is_exist($identifier = null, $value = null)
    {
        if (!empty($identifier) && $identifier == 'username') {
            // cek ke database
            $result = $this->db->where('username', $value)->count_all_results('sys_user');
            if ($result > 0) {
                return true;
            } else {
                return false;
            }
        }

        if (!empty($identifier) && $identifier == 'email') {
            // cek ke database
            $result = $this->db->where('email', $value)->count_all_results('sys_user');
            if ($result > 0) {
                return true;
            } else {
                return false;
            }
        }

        // function cuman untuk chek username udah ada atau belum saat edit data username !!
        if (!empty($identifier) && $identifier == 'update_username') {
            // cek ke database
            $result = $this->db->where('username', $value)->count_all_results('sys_user');
            if ($result  > 1) {
                return true;
            } else {
                return false;
            }
        }
        return false;
    }

    // user baru
    public function register($user = null, $password = null, $email = null, $data = array(), $group = array())
    {

        // create user id
        $this->_create_user_id();

        $data['user_id']    = $this->user_id;

        // cek username is exist
        $data['username']   = $user;

        // cek email is exist

        // build password
        $data['kata_sandi'] = password_hash($this->config->item('encryption_key'). $password, PASSWORD_BCRYPT);
        $data['status']     = isset($data['status']) ? $data['status'] : 1;
        $data['mdd']        = date('Y-m-d H:i:s');

        $this->db->trans_begin();
        $def = 1;
        foreach ($group as $i => $vgroup) {
            $guser[$i]['user_id']  = $data['user_id'];
            $guser[$i]['group_id'] = $vgroup;
            $guser[$i]['default']  = $def == 1 ? 1 : 0;
            $def++;
        }

        $this->db->insert('sys_user', $data);
        $this->db->insert_batch('sys_user_group', $guser);

        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            return FALSE;
        } else {
            $this->db->trans_commit();
            return TRUE;
        }

    }

    // user baru
    public function ubah_data($user_id = null, $data = array(), $username = null, $password = null)
    {
        //set username
        $data['username']   = $username;

        // build password
        if (isset($password) AND ! empty($password)) {
            $data['kata_sandi'] = password_hash($this->config->item('encryption_key'). $password, PASSWORD_BCRYPT);
        }

        $data['mdd']        = date('Y-m-d H:i:s');

        $this->db->trans_begin();

        $this->db->where('user_id', $user_id);
        $this->db->update('sys_user', $data);

        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            return FALSE;
        } else {
            $this->db->trans_commit();
            return TRUE;
        }

    }

    // hapus user
    public function delete_user($user_id)
    {
        $this->db->trans_begin();
        $this->db->delete('sys_user_group', array('user_id' => $user_id));
        $this->db->delete('sys_user', array('user_id' => $user_id));

        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
        }

    }

    // untuk mendapatkan pesan eror
    public function error_message()
    {
        return $this->error_msg;
    }

    // create user ID
    protected function _create_user_id()
    {
        $this->user_id = $this->_v4(TRUE);
    }

    // get user id
    public function get_user_id()
    {
        return $this->user_id != '' ? $this->user_id : null;
    }

    public function _v4($trim = false)
    {

        $format = ($trim == false) ? '%04x%04x-%04x-%04x-%04x-%04x%04x%04x' : '%04x%04x%04x%04x%04x%04x%04x%04x';

        return sprintf($format,
            // 32 bits for "time_low"
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),
            // 16 bits for "time_mid"
            mt_rand(0, 0xffff),
            // 16 bits for "time_hi_and_version",
            // four most significant bits holds version number 4
            mt_rand(0, 0x0fff) | 0x4000,
            // 16 bits, 8 bits for "clk_seq_hi_res",
            // 8 bits for "clk_seq_low",
            // two most significant bits holds zero and one for variant DCE1.1
            mt_rand(0, 0x3fff) | 0x8000,
            // 48 bits for "node"
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
    }

}
