<?php

/*
 * By : Praditya Kurniawan
 * website : http://masiyak.com
 * email : aku@masiyak.com
 * 
 */

class Auth extends MX_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('user_agent');
        $this->load->model('m_site');
    }

    function index() {
        //load captcha helper
        $this->load->helper('captcha');
        if ($this->is_login())
            redirect($this->session->userdata('SESI_USER_LOGIN')['group_portal']);
        else
            $this->_login();
    }

    function _login() {
        $this->load->helper('captcha');
        // $data['captcha'] = $this->_create_captcha();
        $data['WEB_CONFIG'] = $this->_get_web_config();
        // $this->session->set_tempdata('CAPTCHA_TEXT', $data['captcha']['word']);
        $this->load->view('login', $data);
    }

    function do_login() {
        // if (!$this->input->is_ajax_request())
        //     redirect('auth');
        //load form validation
        $this->load->library('form_validation');
        // load model
        $this->load->model('m_auth');
        //set rule
        $this->form_validation->set_rules('username', 'Username', 'required|trim');
        $this->form_validation->set_rules('password', 'Password', 'required|trim');
        // $this->form_validation->set_rules('captcha_text', 'Captcha', 'required|trim');
        // validasi captcha
        // $captcha = $this->session->tempdata('CAPTCHA_TEXT');
        // if ($captcha !== $this->input->post('captcha_text')) {
        //     $status['captcha'] = $this->_create_captcha();
        //     $this->session->set_tempdata('CAPTCHA_TEXT', $status['captcha']['word']);
        //     $status['pesan'] = 'Teks CAPTCHA tidak sesuai ';
        //     $status['type'] = 'error';
        //     $status['status'] = false;
        //     $this->output->set_output(json_encode($status));
        //     return;
        // }

        // validasi
        if ($this->form_validation->run() === false) {
            $status['captcha'] = $this->_create_captcha();
            // $this->session->set_tempdata('CAPTCHA_TEXT', $status['captcha']['word']);
            $status['pesan'] = validation_errors();
            $status['type'] = 'error';
            $status['status'] = false;
            $this->output->set_output(json_encode($status));
            return;
        }


        if ($this->_check_login_attempts($this->input->post('username'))) {
            $status['captcha'] = $this->_create_captcha();
            // $this->session->set_tempdata('CAPTCHA_TEXT', $status['captcha']['word']);
            $status['pesan']  = 'Anda melebihi batas login (3 kali).  Silakan tunggu 5 menit untuk mencoba login lagi.';
            $status['type']   = 'error';
            $status['status'] = false;
            $this->output->set_output(json_encode($status));
            return;
        }


        //get username and password
        $username = $this->input->post('username', true);
        $password = $this->config->item('encryption_key') . $this->input->post('password');
        $key_pass = $this->m_auth->get_password_by_user_email(array($username, $username));
        //do autentification
        if (empty($key_pass) OR ! (password_verify($password, $key_pass['kata_sandi']))) {
            // jika user tidak ditemukan
            $status['captcha'] = $this->_create_captcha();
            // $this->session->set_tempdata('CAPTCHA_TEXT', $status['captcha']['word']);
            $status['pesan'] = 'Pengguna atau kata sandi tidak ditemukan.';
            $status['type'] = 'error';
            $status['status'] = false;
            // write attempts
            $this->_set_login_attempts($this->input->post('username'));
            // write log
            $this->_write_log($this->input->post('username'), 'Password salah.', 'warning');
            return $this->output->set_output(json_encode($status));
        } else {
            // validasi user active
            if ($key_pass['status'] != 1) {
                // jika user tidak ditemukan
                $status['captcha'] = $this->_create_captcha();
                // $this->session->set_tempdata('CAPTCHA_TEXT', $status['captcha']['word'], 60);
                $status['pesan'] = 'Akun Anda tidak aktif.  Silakan hubungi Administrator.';
                $status['type'] = 'error';
                $status['status'] = false;
                $this->_write_log($this->input->post('username'), 'Akun tidak aktif.', 'warning');
                $this->output->set_output(json_encode($status));
                return;
            }
            $this->session->unset_tempdata('CAPTCHA_TEXT');
            // get data login
            $data_autentifikasi = $this->m_auth->get_detail_data_login($key_pass['user_id']);
            $data_autentifikasi['group'] = $this->m_auth->get_user_group($key_pass['user_id']);
            $this->session->set_userdata('SESI_USER_LOGIN', $data_autentifikasi);
            // write log
            $this->_write_log($this->input->post('username'), 'Login berhasil.', 'success');
            // remove attempts
            $this->m_auth->delete_login_attempts($this->input->post('username'));
            $this->m_auth->delete_login_attempts_after_day(time() - 84600);
            $this->m_auth->update_last_login(array(date('Y-m-d H:i:s'), $key_pass['user_id']));
            $this->session->unset_tempdata('CAPTCHA_TEXT');
            return $this->output->set_output(json_encode(array('status' => true, 'portal' => base_url($data_autentifikasi['group_portal']))));
        }
    }

    function get_password() {
//        echo password_hash($this->config->item('encryption_key') . 'admin1', PASSWORD_BCRYPT);
//        echo $this->config->item('encryption_key') . 'admin1';
//        var_dump(password_verify($this->config->item('encryption_key') . 'admin', '$2y$10$9MLgd4hLBZtuKPzZ/10cP.opexvsEFX2LTY0Takf43qfk2Q6kaq5K'));
    }

    function _write_log($user = null, $keterangan = null, $status = 'warning') {
        // load model
        $this->load->model('m_auth');
        $this->load->library('uuid');
        $data_agent['log_id'] = $this->uuid->v4(TRUE);
        $data_agent['user'] = $user;
        $data_agent['waktu_login'] = date('Y-m-d H:i:s');
        $data_agent['ip'] = $this->input->ip_address();
        $data_agent['user_agent'] = $this->agent->agent_string();
        $data_agent['keterangan'] = $keterangan;
        $data_agent['status'] = $status;
        //insert data
        $this->m_auth->insert_auth($data_agent);
        // hapus data jika lebih dari waktu log
        $par = date('Y-m-d H:i:s', strtotime(date('Y-m-d') . ' -1 Month'));
        $this->m_auth->delete_auth($par);
    }

    function _create_captcha() {
        $this->load->helper('captcha');
        $random_number = substr(number_format(time() * rand(), 0, '', ''), 0, 6);
        $vals = array(
            'word' => $random_number,
            'img_path' => 'assets/images/captcha/',
            'img_url' => base_url('assets/images/captcha'),
            'img_width' => '150',
            'font_path' => 'assets/operator/fonts/BEBAS___.ttf',
            'img_height' => 50,
            'expiration' => 30,
            'word_length' => 10,
            'font_size' => 20,
            'img_id' => 'Imageid',
            // White background and border, black text and red grid
            'colors' => array(
                'background' => array(255, 255, 255),
                'border' => array(255, 255, 255),
                'text' => array(0, 0, 0),
                'grid' => array(255, 40, 40)
            )
        );
        return create_captcha($vals);
    }

    function logout() {
        $data_login = $this->session->userdata('SESI_USER_LOGIN');
        $this->session->unset_userdata('SESI_USER_LOGIN');
        $data_login['username'] = isset($data_login['username']) && !empty($data_login['username']) ? $data_login['username'] : $this->input->ip_address();
        $this->_write_log($data_login['username'], 'Pengguna keluar dari sistem.', 'success');
        redirect('auth');
    }

    private function _get_web_config() {
        $result = null;
        foreach ($this->m_site->get_web_config() as $dt_webconf) {
            $result[$dt_webconf['config_name']] = $dt_webconf['config_value'];
        }
        return $result;
    }

    public function is_login() {
        $data_login = $this->session->userdata('SESI_USER_LOGIN');
        if (empty($data_login)) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    private function _set_login_attempts($username, $ip = null)
    {
        if (empty($ip)) {
            $ip = $this->input->ip_address();
        }
        $this->load->library('uuid');

        $attempts['login_id']   = $this->uuid->v4(true);
        $attempts['ip_address'] = $ip;
        $attempts['login']      = $username;
        $attempts['time']       = time();

        $this->m_auth->insert_login_attempts($attempts);

    }

    private function _remove_login_attempts($username)
    {
        // $t
    }

    private function _check_login_attempts($username, $max_attempts = 3, $time_exceed = 300)
    {
        $count = $this->m_auth->get_user_attempts([$username, time() - $time_exceed]);

        if ($count >= $max_attempts) {
            return true;
        } else {
            return false;
        }
    }

}
