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

class Config extends Backendbase {

    // config untuk form validasi
    var $form_conf = array(
        array('field' => 'config_name', 'label' => 'Nama Group', 'rules' => 'required'),
        array('field' => 'config_group', 'label' => 'Grup Pengaturan', 'rules' => 'required'),
        array('field' => 'config_value', 'label' => 'Nilai', 'rules' => 'required'),
        array('field' => 'config_desc', 'label' => 'Deskripsi Pengaturan', 'rules' => 'required'),
        array('field' => 'config_portal_id', 'label' => 'Portal', 'rules' => 'trim'),
        array('field' => 'restricted', 'label' => 'Meta Title', 'rules' => 'trim')
    );

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $data['rs_portal'] = $this->m_backend->get_all_portal();
        parent::display('config/index', $data, 'config/footer_index');
    }

    public function get_list_config() {
        $rs_config = $this->m_backend->get_list_config_dttabel();
        if (!empty($rs_config['data'])) {
            $no = 1;
            foreach ($rs_config['data'] as $i => $vconf) {
                //tombol
                $aksi_kolom = '<div class="pull-right"><button class="btn btn-xs btn-info edit-config" data-id="' . $vconf['config_id'] . '"><i class="fa fa-pencil"></i></button>';
                $aksi_kolom .= ' <button class="btn btn-xs btn-danger hapus-config" data-id="' . $vconf['config_id'] . '"><i class="fa fa-trash"></i></button></div>';
                // data
                $rs_config['data'][$i]['no'] = $no++;
                $rs_config['data'][$i]['restricted'] = $vconf['restricted'] == 1 ? '<i class="fa fa-check-circle text-green"></i>' : '<i class="fa fa-close text-red"></i>';
                $rs_config['data'][$i]['aksi'] = $aksi_kolom;
            }
        }
        return $this->output->set_output(json_encode($rs_config));
    }

    public function proses_tambah() {
        if (!$this->input->is_ajax_request())
            return;

        $this->load->library('form_validation');
        $this->form_validation->set_rules($this->form_conf);
        if ($this->form_validation->run() == FALSE) {
            $data['pesan'] = validation_errors();
            $data['status'] = FALSE;
            return $this->output->set_output(json_encode($data));
        }
        // set data
        $simpan['config_id'] = uniqid('cf');
        $simpan['config_name'] = $this->input->post('config_name', TRUE);
        $simpan['config_value'] = $this->input->post('config_value', TRUE);
        $simpan['config_group'] = $this->input->post('config_group', TRUE);
        $simpan['config_desc'] = $this->input->post('config_desc', TRUE);
        $simpan['config_portal_id'] = $this->input->post('config_portal_id');
        $simpan['restricted'] = $this->input->post('restricted', TRUE) == '' ? 0 : 1;
        $simpan['mdd'] = date('Y-m-d H:i:s');
        if ($this->m_backend->tambah_data('sys_config', $simpan)) {
            $result['status'] = true;
            $result['pesan'] = 'Group ' . $simpan['config_name'] . ' berhasil ditambahkan.';
        } else {
            $eror = $this->m_backend->get_db_error();
            $result['status'] = false;
            $result['pesan'] = 'Data config ' . $simpan['config_name'] . ' gagal ditambahkan. Eror kode : ' . $eror['code'];
        }
        return $this->output->set_output(json_encode($result));
    }

    public function get_detail_config() {
        if (!$this->input->is_ajax_request() || $this->input->post('data_id', TRUE) == '')
            return;

        // ambil detail portal berdasar ID
        $result = $this->m_backend->get_detail_config_id($this->input->post('data_id', TRUE));
        if (!empty($result)) {
            $data['status'] = true;
            $data['data'] = $result;
        } else {
            $data['status'] = false;
            $data['data'] = null;
            $data['pesan'] = $this->m_backend->get_error_message();
        }
        return $this->output->set_output(json_encode($data));
    }

    public function proses_ubah() {
        if (!$this->input->is_ajax_request())
            return;

        $this->load->library('form_validation');
        $this->form_validation->set_rules($this->form_conf);
        if ($this->form_validation->run() == FALSE) {
            $data['pesan'] = validation_errors();
            $data['status'] = FALSE;
            return $this->output->set_output(json_encode($data));
        }

        // set data
        $simpan['config_name'] = $this->input->post('config_name', TRUE);
        $simpan['config_value'] = $this->input->post('config_value', TRUE);
        $simpan['config_group'] = $this->input->post('config_group', TRUE);
        $simpan['config_desc'] = $this->input->post('config_desc', TRUE);
        $simpan['config_portal_id'] = $this->input->post('config_portal_id', TRUE);
        $simpan['restricted'] = $this->input->post('restricted', TRUE) == '' ? 0 : 1;
        $simpan['mdd'] = date('Y-m-d H:i:s');
        if ($this->m_backend->ubah_data('sys_config', 'config_id', $this->input->post('config_id', TRUE), $simpan)) {
            $result['status'] = true;
            $result['pesan'] = 'Pengaturan ' . $simpan['config_name'] . ' berhasil diubah.';
        } else {
            $eror = $this->m_backend->get_db_error();
            $result['status'] = false;
            $result['pesan'] = 'Pengaturan ' . $simpan['config_name'] . ' gagal diubah. Eror kode : ' . $eror['code'];
        }
        return $this->output->set_output(json_encode($result));
    }

    /*
     * Hapus data
     */

    public function hapus_config() {
        if (!$this->input->is_ajax_request())
            return;

        if ($this->input->post('config_id', TRUE) == '') {
            $respon['status'] = FALSE;
            $respon['pesan'] = 'Data ID tidak tersedia.';
            return $this->output->set_output(json_encode($respon));
        }

        // parameter hapus
        $hapus['config_id'] = $this->input->post('config_id', TRUE);
        if ($this->m_backend->hapus_data('sys_config', $hapus)) {
            $data['status'] = TRUE;
            $data['pesan'] = 'Data berhasil dihapus.';
        } else {
            $error = $this->m_backend->get_db_error();
            $data['status'] = FALSE;
            $data['pesan'] = 'Data gagal dihapus.  Error kode : ' . $error['code'];
        }
        return $this->output->set_output(json_encode($data));
    }

}
