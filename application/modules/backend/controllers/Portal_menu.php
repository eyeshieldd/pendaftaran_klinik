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

require_once 'application/core/MY_Backendbase.php';

class Portal_menu extends Backendbase {

    // config untuk form validasi
    var $form_conf = array(
        array('field' => 'portal_id', 'label' => 'Portal', 'rules' => 'required'),
        array('field' => 'menu_name', 'label' => 'Nama Menu', 'rules' => 'required'),
        array('field' => 'menu_desc', 'label' => 'Deskripsi', 'rules' => 'required'),
        array('field' => 'menu_position', 'label' => 'Posisi', 'rules' => 'required'),
        array('field' => 'menu_order', 'label' => 'Urutan', 'rules' => 'required'),
        array('field' => 'menu_parent', 'label' => 'Sub Menu', 'rules' => 'trim'),
        array('field' => 'menu_link', 'label' => 'Alamat', 'rules' => 'required'),
        array('field' => 'menu_show', 'label' => 'Ditampilkan', 'rules' => 'trim'),
        array('field' => 'menu_icon', 'label' => 'Icon', 'rules' => 'trim'),
        array('field' => 'menu_fonticon', 'label' => 'font Icon', 'rules' => 'trim'),
    );

    public function __construct() {
        parent::__construct();
        $this->load->model('m_backend');
    }

    public function index() {

        $data['rs_portal'] = $this->m_backend->get_all_portal();
        $data['rs_posisi_menu'] = $this->config->item('posisi_menu');
        parent::display('portal_menu/index', $data, 'portal_menu/footer_index');
    }

    function cek_menu($portal_id = null) {
        echo '<pre>';
        print_r($this->m_backend->get_list_menu_select_by_portal_id($portal_id));
        exit();
    }

    function list_menu_by_portal() {
        if (!$this->input->is_ajax_request())
            return;

        if ($this->input->post() == '')
            return;

        $data['rs_menu'] = $this->m_backend->get_list_menu_select_by_portal_id($this->input->post('portal_id', TRUE));
        return $this->output->set_output(json_encode($data));
    }

    function proses_tambah() {
        if (!$this->input->is_ajax_request())
            return;

        if ($this->input->post() == '')
            return;

        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules($this->form_conf);
        if ($this->form_validation->run() == FALSE) {
            $data['pesan'] = validation_errors();
            $data['status'] = FALSE;
            return $this->output->set_output(json_encode($data));
        }

        // set data
        $simpan['portal_id'] = $this->input->post('portal_id', TRUE);
        $simpan['menu_name'] = $this->input->post('menu_name', TRUE);
        $simpan['menu_desc'] = $this->input->post('menu_desc', TRUE);
        $simpan['menu_position'] = $this->input->post('menu_position', TRUE);
        $simpan['menu_order'] = $this->input->post('menu_order', TRUE);
        $simpan['menu_parent'] = $this->input->post('menu_parent', TRUE) == '' ? 0 : $this->input->post('menu_parent', TRUE);
        $simpan['menu_link'] = $this->input->post('menu_link', TRUE);
        $simpan['menu_show'] = $this->input->post('menu_show', TRUE) == '' ? 0 : 1;
        $simpan['menu_icon'] = $this->input->post('menu_icon', TRUE);
        $simpan['menu_fonticon'] = $this->input->post('menu_fonticon', TRUE);
        $simpan['mdb'] = 'cidev';
        $simpan['mdd'] = date('Y-m-d H:i:s');
        if ($this->m_backend->tambah_data('sys_menu', $simpan)) {
            $result['status'] = true;
            $result['pesan'] = 'Menu ' . $simpan['menu_name'] . ' berhasil ditambahkan.';
        } else {
            $eror = $this->m_backend->get_db_error();
            $result['status'] = false;
            $result['pesan'] = 'Menu ' . $simpan['menu_name'] . ' gagal ditambahkan. Eror kode : ' . $eror['code'];
        }
        return $this->output->set_output(json_encode($result));
    }

    function get_list_menu() {
        if (!$this->input->is_ajax_request())
            return;

        $rs_menu = $this->m_backend->get_list_menu_select_by_portal_id($this->input->post('portal_id'));

        $option['recordsTotal'] = count($rs_menu);
        $option['recordsFiltered'] = count($rs_menu);
        $option['draw'] = $this->input->post('draw');
        $option['data'] = $rs_menu;
        $no = 1;
        foreach ($rs_menu as $imenu => $vmenu) {
            $aksi_kolom = '<div class="pull-right"><button class="btn btn-xs btn-info edit-menu" data-id="' . $vmenu['menu_id'] . '"><i class="fa fa-pencil"></i></button>';
            $aksi_kolom .= ' <button class="btn btn-xs btn-danger hapus-menu" data-id="' . $vmenu['menu_id'] . '"><i class="fa fa-trash"></i></button></div>';

            $option['data'][$imenu]['no'] = $no++;
            $option['data'][$imenu]['menu_name'] = $vmenu['menu_name'] . ' &nbsp; <i class="fa ' . $vmenu['menu_fonticon'] . '"></i> ';
            $option['data'][$imenu]['menu_position'] = $this->config->item('posisi_menu')[$vmenu['menu_position']];
            $option['data'][$imenu]['menu_show'] = $vmenu['menu_show'] == 1 ? '<i class="fa fa-check text-green"></i>' : '<i class="fa fa-close text-red"></i>';
            $option['data'][$imenu]['aksi'] = $aksi_kolom;
        }

        return $this->output->set_output(json_encode($option));
    }

    // proses hapus
    function hapus_menu() {
        if (!$this->input->is_ajax_request())
            return;

        if ($this->input->post('menu_id') == '') {
            $respon['status'] = FALSE;
            $respon['pesan'] = 'Data ID tidak tersedia.';
            return $this->output->set_output(json_encode($respon));
        }

        // parameter hapus
        $hapus['menu_id'] = $this->input->post('menu_id', TRUE);
        if ($this->m_backend->hapus_data('sys_menu', $hapus)) {
            $data['status'] = TRUE;
            $data['pesan'] = 'Data berhasil dihapus.';
        } else {
            $error = $this->m_backend->get_db_error();
            $data['status'] = FALSE;
            $data['pesan'] = 'Data gagal dihapus.  Error kode : ' . $error['code'];
        }
        return $this->output->set_output(json_encode($data));
    }

    public function get_detail_menu() {
        if (!$this->input->is_ajax_request() || $this->input->post('data_id', TRUE) == '')
            return;

        // ambil detail portal berdasar ID
        $result = $this->m_backend->get_detail_menu($this->input->post('data_id', TRUE));
        if (!empty($result)) {
            $data['status'] = true;
            $data['data'] = $result;
            // ambil data list menu tanpa menu ini
            foreach ($this->m_backend->get_list_menu_select_by_portal_id($result['portal_id']) as $vmenu) {
                if ($vmenu['menu_id'] == $this->input->post('data_id'))
                    continue;
                $data['menu'][] = $vmenu;
            }
        } else {
            $data['status'] = false;
            $data['data'] = null;
            $data['pesan'] = $this->m_backend->get_error_message();
        }
        return $this->output->set_output(json_encode($data));
    }

    // portal ubah
    function proses_ubah() {
        if (!$this->input->is_ajax_request())
            return;

        if ($this->input->post() == '')
            return;
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules($this->form_conf);
        if ($this->form_validation->run() == FALSE) {
            $data['pesan'] = validation_errors();
            $data['status'] = FALSE;
            return $this->output->set_output(json_encode($data));
        }

        // set data
        $simpan['menu_name'] = $this->input->post('menu_name', TRUE);
        $simpan['menu_desc'] = $this->input->post('menu_desc', TRUE);
        $simpan['menu_position'] = $this->input->post('menu_position', TRUE);
        $simpan['menu_order'] = $this->input->post('menu_order', TRUE);
        $simpan['menu_parent'] = $this->input->post('menu_parent', TRUE) == '' ? 0 : $this->input->post('menu_parent', TRUE);
        $simpan['menu_link'] = $this->input->post('menu_link', TRUE);
        $simpan['menu_show'] = $this->input->post('menu_show', TRUE) == '' ? 0 : 1;
        $simpan['menu_icon'] = $this->input->post('menu_icon', TRUE);
        $simpan['menu_fonticon'] = $this->input->post('menu_fonticon', TRUE);
        $simpan['mdb'] = 'cidev';
        $simpan['mdd'] = date('Y-m-d H:i:s');
        if ($this->m_backend->ubah_data('sys_menu', 'menu_id', $this->input->post('menu_id', TRUE), $simpan)) {
            $result['status'] = true;
            $result['pesan'] = 'Group ' . $simpan['menu_name'] . ' berhasil diubah.';
        } else {
            $eror = $this->m_backend->get_db_error();
            $result['status'] = false;
            $result['pesan'] = 'Group ' . $simpan['menu_name'] . ' gagal diubah. Eror kode : ' . $eror['code'];
        }
        return $this->output->set_output(json_encode($result));
    }

}
