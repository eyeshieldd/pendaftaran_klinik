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

class Backauth extends MX_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $userlogin = $this->session->userdata('SESI_DEVELOPER_LOGIN');
        if (!empty($userlogin))
            redirect('backend/depan');
        else
            $this->_login();
    }

    function _login() {
        $this->load->library('form_validation');
        $this->load->view('backend/backauth/login');
//        $this->load->view('backend/backauth/footer_login');
    }

    function logout() {
        $this->session->unset_userdata('SESI_DEVELOPER_LOGIN');
        redirect('backend/backauth');
    }

    function do_auth() {
        if ($this->input->post() == '')
            redirect('backend/backauth');
        //load form validation
        $this->load->library('form_validation');
        //set rule
        $this->form_validation->set_rules('username', 'Pengguna', 'required|trim');
        $this->form_validation->set_rules('password', 'Kata Kunci', 'required|trim');
        $this->form_validation->set_error_delimiters('', '');
        if ($this->form_validation->run() === false) {
            $data['status'] = FALSE;
            $data['pesan'] = validation_errors();
            return $this->output->set_output(json_encode($data));
        }
        //get username and password
        $data_autentifikasi = $this->config->item('backend_autentification');
        //get data login encrypt password
        $username = $this->input->post('username', true);
        $password = $this->input->post('password', true);
        //do autentification
        if ($username == $data_autentifikasi[0] && password_verify($password, $data_autentifikasi[1])) {
            $data['username'] = $this->input->post('username');
            $data['login'] = true;
            $data['login_time'] = date('Y-m-d H:i:s');
            $this->session->set_userdata('SESI_DEVELOPER_LOGIN', $data);
            $data['status'] = TRUE;
        } else {
            $this->session->set_flashdata('form_msg', 'Pengguna atau kata sandi tidak sesuai.');
            $data['pesan'] = 'Pengguna atau kata sandi tidak sesuai.';
            $data['status'] = FALSE;
        }
        return $this->output->set_output(json_encode($data));
    }

    function get_data_login() {
        print_r($this->config->item('backend_autentification'));
    }
}
