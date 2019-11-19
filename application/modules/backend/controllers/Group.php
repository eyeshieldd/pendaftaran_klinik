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

class Group extends Backendbase {

    // config untuk form validasi
    var $form_conf = array(
        array('field' => 'group_name', 'label' => 'Nama Group', 'rules' => 'required'),
        array('field' => 'group_desc', 'label' => 'Deskripsi Group', 'rules' => 'required'),
        array('field' => 'group_portal', 'label' => 'Link Group', 'rules' => 'required'),
        array('field' => 'group_restricted', 'label' => 'Meta Title', 'rules' => 'trim')
    );

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        parent::display('group/index', NULL, 'group/footer_index');
    }

    public function get_list_group() {

        $this->load->library('cldatatable');

        $aksi_kolom = '<div class="pull-right"><button class="btn btn-xs btn-info edit-group" data-id="{{group_id}}"><i class="fa fa-pencil"></i> ubah</button>';
        $aksi_kolom .= ' <button class="btn btn-xs btn-danger hapus-group" data-id="{{group_id}}"><i class="fa fa-trash"></i> hapus</button></div>';

        return $this->output->set_output(
                        $this->cldatatable->set_tabel('sys_group')
                                ->set_kolom('group_id, group_name, group_desc, group_portal, group_restricted')
                                ->tambah_kolom('aksi', $aksi_kolom)
                                ->modif_data('group_restricted', function($data) {
                                    return $data['group_restricted'] == 1 ? '<i class="fa fa-check text-green"></i>' : '<i class="fa fa-close text-red"></i>';
                                })
                                ->get_datatable()
        );
    }

    public function proses_tambah() {
        if (!$this->input->is_ajax_request())
            return;

        $this->load->library('form_validation');
        $this->form_validation->set_rules($this->form_conf);
        $this->form_validation->set_error_delimiters('', '');
        if ($this->form_validation->run() == FALSE) {
            $data['pesan'] = validation_errors();
            $data['status'] = FALSE;
            return $this->output->set_output(json_encode($data));
        }

        // set data
        $simpan['group_id'] = uniqid('gr');
        $simpan['group_name'] = $this->input->post('group_name', TRUE);
        $simpan['group_desc'] = $this->input->post('group_desc', TRUE);
        $simpan['group_portal'] = $this->input->post('group_portal', TRUE);
        $simpan['group_restricted'] = $this->input->post('group_restricted', TRUE) == '' ? 0 : 1;
        $simpan['mdd'] = date('Y-m-d H:i:s');
        if ($this->m_backend->tambah_data('sys_group', $simpan)) {
            $result['status'] = true;
            $result['pesan'] = 'Group ' . $simpan['group_name'] . ' berhasil ditambahkan.';
        } else {
            $eror = $this->m_backend->get_db_error();
            $result['status'] = false;
            $result['pesan'] = 'Data group ' . $simpan['group_name'] . ' gagal ditambahkan. Eror kode : ' . $eror['code'];
        }
        return $this->output->set_output(json_encode($result));
    }

    public function get_detail_group() {
        if (!$this->input->is_ajax_request() || $this->input->post('data_id', TRUE) == '')
            return;

        // ambil detail portal berdasar ID
        $result = $this->m_backend->get_detail_group_id($this->input->post('data_id', TRUE));
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
        $simpan['group_name'] = $this->input->post('group_name', TRUE);
        $simpan['group_desc'] = $this->input->post('group_desc', TRUE);
        $simpan['group_portal'] = $this->input->post('group_portal', TRUE);
        $simpan['group_restricted'] = $this->input->post('group_restricted', TRUE) == '' ? 0 : 1;
        $simpan['mdd'] = date('Y-m-d H:i:s');
        if ($this->m_backend->ubah_data('sys_group', 'group_id', $this->input->post('group_id', TRUE), $simpan)) {
            $result['status'] = true;
            $result['pesan'] = 'Group ' . $simpan['group_name'] . ' berhasil diubah.';
        } else {
            $eror = $this->m_backend->get_db_error();
            $result['status'] = false;
            $result['pesan'] = 'Group ' . $simpan['group_name'] . ' gagal diubah. Eror kode : ' . $eror['code'];
        }
        return $this->output->set_output(json_encode($result));
    }

    /*
     * Hapus data
     */

    public function hapus_group() {
        if (!$this->input->is_ajax_request())
            return;

        if ($this->input->post('group_id', TRUE) == '') {
            $respon['status'] = FALSE;
            $respon['pesan'] = 'Data ID tidak tersedia.';
            return $this->output->set_output(json_encode($respon));
        }

        // parameter hapus
        $hapus['group_id'] = $this->input->post('group_id', TRUE);
        if ($this->m_backend->hapus_data('sys_group', $hapus)) {
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
