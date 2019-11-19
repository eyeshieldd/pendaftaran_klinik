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

/*
 * Controller dasar untuk semua portal aplikasi
 */

class Base_controller extends MX_Controller
{

    protected $portal_id     = 1; // nomor portal sesuaikan dengan backend
    protected $portal_judul  = 'Control Panel :: Borobudur Conservation Archive';
    private $group_id        = array();
    private $file_js         = '';
    private $file_css        = '';
    private $web_config      = array();
    private $theme_base      = 'operator/theme';
    private $current_id_menu = '';
    public $page_role        = array('c' => 0, 'r' => 0, 'u' => 0, 'd' => 0);
    protected $com_user      = array();

    public function __construct()
    {
        parent::__construct();
        $this->load->model('m_site');
        $this->cek_login();
        $this->_get_user_group();
        $this->get_current_id_menu();
        $this->get_all_role();
    }

    //fungsi untuk meload javascript
    protected function load_js($alamat)
    {
        if (is_file($alamat)) {
            $this->file_js .= '<script src="' . base_url($alamat) . '" type="text/javascript"></script>';
            $this->file_js .= "\n";
        } else {
            $this->file_js .= 'File javascript ' . $alamat . ' tidak ditemukan! <br>';
        }
    }

    //fungsi untuk meload css
    protected function load_css($alamat)
    {
        if (is_file($alamat)) {
            $this->file_css .= '<link href="' . base_url($alamat) . '" rel="stylesheet" type="text/css" />';
        } else {
            $this->file_css .= 'File css ' . $alamat . ' tidak ditemukan! <br>';
        }
    }

    // validasi login
    private function cek_login()
    {
        $this->com_user = $this->session->userdata('SESI_USER_LOGIN');
        if (empty($this->com_user)) {
            if ($this->input->is_ajax_request()) {
                echo 'login_required 123';
                exit();
            }
            redirect('auth');
        }
    }

// ambil menu sidebar
    public function _get_left_sidebar()
    {
        $html = '';
        // get parent menu
        foreach ($this->m_site->get_parent_menu(array($this->group_id, 0, $this->portal_id)) as $dt_parent_menu) {
            $child_menu = $this->_get_child_menu_sidebar($dt_parent_menu['menu_id']);
            $icon_menu  = empty($dt_parent_menu['menu_fonticon']) ? 'si si-cup' : $dt_parent_menu['menu_fonticon'];
            if (!empty($child_menu)) {
                $html .= $child_menu['active'] == 'class="nav-submenu active"' ? '<li class="open">':'<li>';
                $html .= '<a ' . $child_menu['active'] . ' href="javascript:void(0);" title="' . $dt_parent_menu['menu_desc'] . '" class="nav-submenu" data-toggle="nav-submenu">';
                $html .= '<i class="mdi ' . $icon_menu . '"></i> <span class="hide-menu">' . $dt_parent_menu['menu_name'] . '<span class="fa arrow"></span></span>';
                $html .= '</a>';
                $html .= $child_menu['html'];
                $html .= '</li>';
            } else {
                if ($this->uri->segment(1) == $dt_parent_menu['menu_link'] || $this->uri->segment(1) . '/' . $this->uri->segment(2) == $dt_parent_menu['menu_link']) {
                    $class_stats    = 'class="active"';
                } else {
                    $class_stats = 'class=""';
                }
                $html .= '<li><a '.$class_stats.' href="' . base_url($dt_parent_menu['menu_link']) . '"><i class="' . $icon_menu . '"></i><span class="hide-menu"> ' . $dt_parent_menu['menu_name'] . '</span></a></li>';
            }
        }
        return $html;
    }

// ambil child menu
    public function _get_child_menu_sidebar($parent_id)
    {
        $stat       = array();
        $list_child = $this->m_site->get_parent_menu(array($this->group_id, $parent_id, $this->portal_id));
        if (!empty($list_child)) {
            $stat['html']   = '<ul>';
            $stat['active'] = $class_stats = '';
            foreach ($list_child as $dt_child_menu) {
                $icon_menu = empty($dt_child_menu['menu_fonticon']) ? '' : $dt_child_menu['menu_fonticon'];
                if ($this->uri->segment(1) == $dt_child_menu['menu_link'] || $this->uri->segment(1) . '/' . $this->uri->segment(2) == $dt_child_menu['menu_link']) {
                    $stat['active'] = 'class="nav-submenu active"';
                    $class_stats    = 'class="active"';
                } else {
                    $class_stats = 'class=""';
                }
                $stat['html'] .= '<li><a ' . $class_stats . ' href="' . site_url($dt_child_menu['menu_link']) . '" title="' . $dt_child_menu['menu_desc'] . '"><i class="' . $icon_menu . '"></i> ' . $dt_child_menu['menu_name'] . '</a></li>';
            }
            $stat['html'] .= '</ul>';
        }
        return $stat;
    }

// membuat breadcrumb
    public function _build_breadcrumb($par = null)
    {
        $html = '<ol class="breadcrumb">';
        // get link
        $dt_menu = $this->m_site->get_detail_link_menu_by_name($this->uri->segment(1));
        if (empty($dt_menu)) {
            $dt_menu = $this->m_site->get_detail_link_menu_by_name($this->uri->segment(1) . '/' . $this->uri->segment(2));
        }

        if (empty($dt_menu)) {
            $dt_menu = $this->m_site->get_detail_link_menu_by_name(array($this->uri->segment(1) . '/index'));
        }

        if ($dt_menu['menu_parent'] != 0) {
            $dt_parent = $this->m_site->get_detail_link_menu_by_id($dt_menu['menu_parent']);
            $icon_menu = empty($dt_parent['menu_fonticon']) ? 'mdi mdi-chevron-right' : $dt_parent['menu_fonticon'];
            $html .= '<li><a href="javascript:void(0);"><i class="mdi ' . $icon_menu . '"></i> ' . $dt_parent['menu_name'] . '</a></li>';
            $html .= '<li><a href="' . $dt_menu['menu_link'] . '">' . $dt_menu['menu_name'] . '</a></li>';
            if (!empty($par)) {
                foreach ($par as $key => $vpar) {
                    $html .= '<li class=""><a href="' . $vpar . '">' . $key . '</a></li>';
                }
            }
        } else {
            $icon_menu = empty($dt_menu['menu_fonticon']) ? 'mdi' : $dt_menu['menu_fonticon'];
            $html .= '<li><a href="javascript:void(0);"><i class="' . $icon_menu . '"></i> ' . $dt_menu['menu_name'] . '</a></li>';
            if (!empty($par)) {
                foreach ($par as $key => $vpar) {
                    $html .= '<li class=""><a href="' . $vpar . '">' . $key . '</a></li>';
                }
            }
        }
        $html .= '</ol>';
        return $html;
    }

// detail halaman
    public function _get_detail_link_page()
    {
        return $this->m_site->get_detail_link_menu_by_name($this->uri->segment(1));
    }

// set konfigurasi web dasar
    public function _get_web_config()
    {
        foreach ($this->m_site->get_web_config() as $dt_webconf) {
            $this->web_config[$dt_webconf['config_name']] = $dt_webconf['config_value'];
        }
    }

// get user group
    public function _get_user_group()
    {
        foreach ($this->com_user['group'] as $vgroup) {
            $this->group_id[] = $vgroup['group_id'];
        }
    }

    public function get_current_id_menu()
    {
        $link    = $this->uri->segment(1);
        $current = $this->m_site->get_id_menu(array($link, $this->com_user['group_id']));
        if (empty($current)) {
            $current = $this->m_site->get_id_menu(array($link . '/' . $this->uri->segment(2), $this->com_user['group_id']));
        }

        $this->current_id_menu = $current;
    }

//mendapatkan hak akses pengguna pada modul
    public function get_all_role()
    {
        $rs_role = $this->m_site->get_page_role_level(array($this->uri->segment(1), $this->group_id));
        if (empty($rs_role)) {
            $rs_role = $this->m_site->get_page_role_level(array($this->uri->segment(1) . '/' . $this->uri->segment(2), $this->group_id));
        }

        $a = 0;
        foreach ($rs_role as $vrole) {
            $this->page_role['c'] = substr($vrole['permission'], 0, 1) == 1 ? 1 : $this->page_role['c'];
            $this->page_role['r'] = substr($vrole['permission'], 1, 1) == 1 ? 1 : $this->page_role['r'];
            $this->page_role['u'] = substr($vrole['permission'], 2, 1) == 1 ? 1 : $this->page_role['u'];
            $this->page_role['d'] = substr($vrole['permission'], 3, 1) == 1 ? 1 : $this->page_role['d'];
        }
        $this->session->set_userdata('ROLE', $this->page_role);
    }

// avatar
    public function get_avatar()
    {
        // get session
        $USER_LOGIN = $this->session->userdata('SESI_USER_LOGIN');
        $filefoto   = isset($USER_LOGIN['foto']) ? $USER_LOGIN['foto'] : 'user.png';
        // cek file
        $link_foto = base_url('assets/images/avatar/');
        if (file_exists('assets/images/avatar/' . $this->com_user['user_id'] . '.png')) {
            $url_poto_profil = $link_foto . '/' . $this->com_user['user_id'] . '.png';
        } else {
            $url_poto_profil = $link_foto . '/ava.png';
        }

        return $url_poto_profil;
    }

// set judul web dinamis
    public function _set_web_title($title)
    {
        $this->web_config['web_title'] = $title;
    }

    public function _cek_login()
    {
        $this->com_user = $this->session->userdata('SESI_USER_LOGIN');
        if (empty($this->com_user)) {
            if ($this->input->is_ajax_request()) {
                echo json_encode(array('result' => false, 'pesan' => 'Sesi Anda telah habis.  Silakan melakukan otentikasi ulang dengan memuat ulang halaman.'));
                exit();
            }
            redirect('auth');
        }
    }

//memberikan hak pada suatu function
    public function _set_page_role($role)
    {
        $role = strtolower($role);
        if (empty($role) || $this->page_role[$role] != 1) {
            if ($this->input->is_ajax_request()) {
                $data['pesan']  = 'Anda tidak mempunyai hak mengakses halaman ini.';
                $data['status'] = false;
                return $this->output->set_output(json_encode($data));
                exit();
            } else {
                redirect('errors/restricted');
            }
        }
    }

//fungsi untuk menampilkan halaman
    public function display($tpl_content = 'operator/default.php', $data = array(), $tpl_footer = null)
    {
        $data['FILE_JS']      = $this->file_js;
        $data['FILE_CSS']     = $this->file_css;
        $data['SYS_TITLE']    = $this->portal_judul;
        $data['ROLE']         = $this->page_role;
        $data['USER_LOGIN']   = $this->com_user;
        $data['TPL_ISI']      = $tpl_content;
        $data['TPL_SIDEMENU'] = $this->_get_left_sidebar();
        $data['TPL_FOOTER']   = $tpl_footer;
        // $data['SESI']         = $this->session->userdata('SESI_USER_LOGIN');
        // $data['AVATAR']       = $this->get_avatar();
        // $data['BREADCRUMB']   = isset($data['breadcrumb']) ? $this->_build_breadcrumb($data['breadcrumb']) : $this->_build_breadcrumb();
        // $data['DETAIL_PAGE']  = $this->_get_detail_link_page();
        // $data['WEB_CONFIG']   = $this->web_config;
        // $data['CSRF_CONFIG']  = array('name' => $this->security->get_csrf_token_name(), 'hash' => $this->security->get_csrf_hash());
        // echo "<pre>";
        // print_r($data);
        // exit();
        $this->load->view($this->theme_base, $data);
    }

}

/*
 * Controller untuk portal pegawai
 */

class Portal extends Base_controller
{

    public function __construct()
    {
        parent::__construct();
        $this->portal_id = 1;
    }

    public function cek_portal()
    {
        return 'ini portal pelatihan';
    }

}
