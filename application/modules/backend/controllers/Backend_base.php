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

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Backend_base extends MX_Controller {

    public function __construct() {
        parent::__construct();
        //  load model
        $this->load->model('m_backend');
        // cek login
        $this->_is_login();
    }

    private $list_menu = array(
        array(
            'nama_menu' => 'Depan',
            'link' => 'depan',
            'deskripsi' => 'Home',
            'icon' => 'fa fa-dashboard fa-fw',
        ),
        array(
            'nama_menu' => 'Portal Aplikasi',
            'link' => 'portal_app',
            'deskripsi' => 'Daftar Portal Pada Aplikasi',
            'icon' => 'fa fa-bars fa-fw',
        ),
        array(
            'nama_menu' => 'Portal Menu',
            'link' => 'portal_menu',
            'deskripsi' => 'Portal Menu',
            'icon' => 'fa fa-server fa-fw',
        ),
        array(
            'nama_menu' => 'Grup Pengguna',
            'link' => 'group',
            'deskripsi' => 'Olah Grup Pengguna',
            'icon' => 'fa fa-users fa-fw',
        ),
        array(
            'nama_menu' => 'Pengguna',
            'link' => 'user',
            'deskripsi' => 'Olah data pengguna',
            'icon' => 'fa fa-user fa-fw',
        ),
        array(
            'nama_menu' => 'Hak Akses',
            'link' => 'permission',
            'deskripsi' => 'Olah hak akses setiap grup',
            'icon' => 'fa fa-key fa-fw',
        ),
        array(
            'nama_menu' => 'Histori Masuk',
            'link' => 'log_auth',
            'deskripsi' => 'Rekaman percobaan otentikasi sistem',
            'icon' => 'fa fa-unlock-alt fa-fw',
        ),
        array(
            'nama_menu' => 'Pengaturan',
            'link' => 'config',
            'deskripsi' => 'Olah data pengaturan yang ada di sistem',
            'icon' => 'fa fa-wrench fa-fw',
        ),
        array(
            'nama_menu' => 'Keluar',
            'link' => 'backauth/logout',
            'deskripsi' => 'Log Out',
            'icon' => 'fa fa-sign-out fa-fw',
        )
    );
    //deklarasi variabel
    private $file_js = '';
    private $file_css = '';

    //fungsi untuk meload javascript
    protected function load_js($alamat) {
        if (is_file($alamat)) {
            $this->file_js .= '<script src="' . base_url($alamat) . '" type="text/javascript"></script>';
            $this->file_js .= "\n";
        } else {
            $this->file_js .= 'File javascript ' . $alamat . ' tidak ditemukan! <br>';
        }
    }

    //fungsi untuk meload css
    protected function load_css($alamat) {
        if (is_file($alamat)) {
            $this->file_css .= '<link href="' . base_url($alamat) . '" rel="stylesheet" type="text/css" />';
        } else {
            $this->file_css .= 'File css ' . $alamat . ' tidak ditemukan! <br>';
        }
    }

    //fungsi untuk buat menu samping
    protected function load_menu_sidebar() {
        $html = '';
        foreach ($this->list_menu as $dt_menu) {
            $html .= $this->uri->segment(2) === $dt_menu['link'] ? '<li class="active">' : '<li>';
            $html .= '<a href="' . base_url('backend/' . $dt_menu['link']) . '" title="' . $dt_menu['deskripsi'] . '"><i class="' . $dt_menu['icon'] . '"></i> ' . $dt_menu['nama_menu'] . '</a>';
            $html .= '</li>';
        }
        return $html;
    }

    //fungsi untuk menampilkan halaman
    protected function display($tpl_content = 'default.php', $data = array(), $tpl_footer = null) {
//        $data['DATA_LOGIN'] = $this->sesi->get_all_data_login();
        $data['FILE_JS'] = $this->file_js;
        $data['FILE_CSS'] = $this->file_css;
        $data['TPL_SIDE_MENU'] = $this->load_menu_sidebar();
        $data['TPL_ISI'] = 'backend/' . $tpl_content;
        $data['PORTAL_INFO'] = $this->_get_portal_info();
        $data['TPL_FOOTER'] = empty($tpl_footer) ? null : 'backend/' . $tpl_footer;
//        $data['CSRF_CONFIG'] = array('name' => $this->security->get_csrf_token_name(), 'hash' => $this->security->get_csrf_hash());
        $this->load->view('backend/theme', $data);
    }

    private function cek_login() {
        if (!$this->sesi->sudah_login())
            redirect('autentifikasi');
    }

    function _is_login() {
        $data_login = $this->session->userdata('SESI_DEVELOPER_LOGIN');
        echo '<pre>';
        print_r($data_login);
        exit();
        if (empty($data_login)) {
            if ($this->input->is_ajax_request()) {
                return $this->output->set_output(json_encode(array('result' => FALSE, 'pesan' => 'Sesi Anda telah habis.')));
                exit();
            }
            redirect('backend/backauth');
        }
    }

    protected function _get_portal_info() {
        $index_menu = null;
        foreach ($this->list_menu as $i => $vmenu) {
            if ($vmenu['link'] == $this->uri->segment(2)) {
                $index_menu = $i;
                break;
            }
        }
        return $this->list_menu[$index_menu];
    }

    protected function _get_breadcrumb($list = null) {
        
    }

}
