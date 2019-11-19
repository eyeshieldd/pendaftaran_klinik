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

class Cauth
{

  protected $CI;
  protected $message;

    /**
     * __get
     *
     * Enables the use of CI super-global without having to define an extra variable.
     *
     * I can't remember where I first saw this, so thank you if you are the original author. -Militis
     */
    public function __get($var)
    {
      return get_instance()->$var;
    }

    public function __construct($parameter = null)
    {
      // Assign the CodeIgniter super-object
      $this->CI = &get_instance();
      $this->load->library('email');
    }

    // fungsi untuk mengambil id jika token benar dan belum expired
    public function get_id($request = null)
    {
      $db = $this->CI->load->database('', true);

      //query
      $query =  $db->select('*')->from('sys_user')
      ->group_start()
      ->where('token',$request)
      ->group_end()
      ->get();

      // jika data token tidak di temukan
      if ($query->num_rows() < 1) {
        $this->set_message('Unknown Token !');
        $data['status'] = FALSE;

        return $data;
      }

      // memasukan data ke variable result
      $result = $query->row_array();

      //jika token telah expired
      if ($result['token_time'] > date('Y-m-d H:i:s')) {

        $data['status'] = TRUE;
        $data['user_id'] = $result['user_id'];

        return $data;
      }else{

        $this->set_message('Token expired !');
        $data['status'] = FALSE;

        return $data;
      }
    }

    // fungsi untuk mengirim email ke user
    public function send_email($request=NULL )
    { 

      $db = $this->CI->load->database('', true);

      if (empty($request)) {

        $this->set_message('invalid parameter');
        return false;
      }

      $query =  $db->select('*')->from('sys_user')
      ->group_start()
      ->where('email',$request)
      ->group_end()
      ->get();

      //jika email tidak ditemukan 
      if ($query->num_rows() < 1) {
        $this->set_message('email does not connect to any user');
        return false;
      }

      $result = $query->row_array();
      $query->free_result();

        // inserting token and time expired
      $insert_data = array(
        'token'       => uniqid(),
        'token_time'  => date('Y-m-d H:i:s', strtotime('+ 1 hours'))
      );

      $this->db->where('user_id', $result['user_id']);
      $this->db->update('sys_user', $insert_data);

      $this->email->set_newline("\r\n");

        // load tampilan yang akan di kirim ke email
      $content_email  = '';
      $content_email  = $this->load->view('email/email_reset',$insert_data,true);

        // konfigurasi pengiriman email
      $to      = $result['email']; 
      $this->email->clear();
      $this->email->from('support@circlelabs.id');
      $this->email->to($to);
      $this->email->subject('Reset Password');
      $this->email->message($content_email);

        // pengiriman email
      if (!$this->email->send()) {
        $this->set_message($this->email->print_debugger());
        return false;
      } else {
        $this->set_message('email successfully sent, please Check your inbox');
        return true;
      }
    }

    // set message
    public function set_message($request = null)
    {
      $this->message = $request;

    }

    // get message
    public function get_message()
    {
      return $this->message;

    }

    // fungsi reset password
    public function reset_password($data = NULL)
    {

      $this->db->trans_begin();

      $this->db->set('kata_sandi', password_hash($this->config->item('encryption_key') . $data['password'], PASSWORD_BCRYPT));
      $this->db->set('token', null);
      $this->db->set('token_time', null);
      $this->db->where('user_id',$data['user_id']);
      $this->db->update('sys_user');

      // proses hasil query setelah dijalankan
      if ($this->db->trans_status() === FALSE) {
        $this->db->trans_rollback();
        $this->set_message('Change password failed');
        return FALSE;
      } else {
        $this->set_message('Change password success');
        $this->db->trans_commit();
        return TRUE;
      }
    }

  }
