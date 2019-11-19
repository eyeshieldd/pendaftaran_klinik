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

class Portal_app extends Backendbase {

    // config untuk form validasi
    var $form_conf = array(
        array('field' => 'portal_name', 'label' => 'Nama Portal', 'rules' => 'required'),
        array('field' => 'portal_number', 'label' => 'Nomor Portal', 'rules' => 'callback_cek_nomor_portal'),
        array('field' => 'portal_desc', 'label' => 'Deskripsi Portal', 'rules' => 'required'),
        array('field' => 'portal_link', 'label' => 'Link Portal', 'rules' => 'trim'),
        array('field' => 'portal_title', 'label' => 'Meta Title', 'rules' => 'required'),
        array('field' => 'portal_tag', 'label' => 'Meta Tag', 'rules' => 'required'),
        array('field' => 'portal_desc', 'label' => 'Meta Description', 'rules' => 'required')
    );

    public function __construct() {
        parent::__construct();
        
        if($this->_is_login())
            return;
        // load model
        $this->load->model('m_backend');
    }

    public function index() {
        parent::display('portal_app/index', null, 'portal_app/footer_index');
    }

    function get_list_portal() {

        $this->load->library('cldatatable');

        $aksi_kolom = '<div class="pull-right"><button class="btn btn-xs btn-info edit-portal" data-id="{{portal_id}}"><i class="fa fa-pencil"></i> ubah</button>';
        $aksi_kolom .= ' <button class="btn btn-xs btn-danger hapus-portal" data-id="{{portal_id}}"><i class="fa fa-trash"></i> hapus</button></div>';

        return $this->output->set_output(
                        $this->cldatatable->set_tabel('sys_portal')
                                ->set_kolom('portal_id, portal_name, portal_number, portal_desc, portal_link')
                                ->tambah_kolom('aksi', $aksi_kolom)
                                ->get_datatable()
        );
    }

    public function get_detail_portal() {
        if (!$this->input->is_ajax_request() || $this->input->post('data_id', TRUE) == '')
            return;
        

        // ambil detail portal berdasar ID
        $result = $this->m_backend->get_detail_portal_id($this->input->post('data_id', TRUE));
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

    public function proses_tambah() {
        if (!$this->input->is_ajax_request())
            return;

        $this->load->library('form_validation');
        $this->form_validation->set_rules($this->form_conf);
        $this->form_validation->set_rules('portal_number', 'Nomor Portal', 'callback_cek_nomor_portal');
        if ($this->form_validation->run($this) == FALSE) {
            $data['pesan'] = validation_errors();
            $data['status'] = FALSE;
            return $this->output->set_output(json_encode($data));
        }

        // set data
        $simpan['portal_name'] = $this->input->post('portal_name', TRUE);
        $simpan['portal_number'] = $this->input->post('portal_number', TRUE);
        $simpan['portal_desc'] = $this->input->post('portal_desc', TRUE);
        $simpan['portal_link'] = $this->input->post('portal_link', TRUE);
        $simpan['meta_title'] = $this->input->post('portal_title', TRUE);
        $simpan['meta_desc'] = $this->input->post('portal_meta_desc', TRUE);
        $simpan['meta_tag'] = $this->input->post('portal_tag', TRUE);
        $simpan['mdd'] = date('Y-m-d H:i:s');
        if ($this->m_backend->tambah_data('sys_portal', $simpan)) {
            $result['status'] = true;
            $result['pesan'] = 'Portal ' . $simpan['portal_name'] . ' berhasil ditambahkan.';
        } else {
            $eror = $this->m_backend->get_db_error();
            $result['status'] = false;
            $result['pesan'] = 'Data pengaturan ' . $simpan['portal_name'] . ' gagal ditambahkan. Eror kode : ' . $eror['code'];
        }
        return $this->output->set_output(json_encode($result));
    }

    public function proses_ubah() {
        if (!$this->input->is_ajax_request())
            return;

        $this->load->library('form_validation');
        $this->form_validation->set_rules($this->form_conf);
        $this->form_validation->set_rules('portal_number', 'Nomor Portal', 'callback_cek_nomor_portal[' . $this->input->post('portal_id') . ']');
        if ($this->form_validation->run($this) == FALSE) {
            $data['pesan'] = validation_errors();
            $data['status'] = FALSE;
            return $this->output->set_output(json_encode($data));
        }

        // set data
        $simpan['portal_name'] = $this->input->post('portal_name', TRUE);
        $simpan['portal_number'] = $this->input->post('portal_number', TRUE);
        $simpan['portal_desc'] = $this->input->post('portal_desc', TRUE);
        $simpan['portal_link'] = $this->input->post('portal_link', TRUE);
        $simpan['meta_title'] = $this->input->post('portal_title', TRUE);
        $simpan['meta_desc'] = $this->input->post('portal_meta_desc', TRUE);
        $simpan['meta_tag'] = $this->input->post('portal_tag', TRUE);
        $simpan['mdd'] = date('Y-m-d H:i:s');
        if ($this->m_backend->ubah_data('sys_portal', 'portal_id', $this->input->post('portal_id', TRUE), $simpan)) {
            $result['status'] = true;
            $result['pesan'] = 'Portal ' . $simpan['portal_name'] . ' berhasil diubah.';
        } else {
            $eror = $this->m_backend->get_db_error();
            $result['status'] = false;
            $result['pesan'] = 'Portal ' . $simpan['portal_name'] . ' gagal diubah. Eror kode : ' . $eror['code'];
        }
        return $this->output->set_output(json_encode($result));
    }

    /*
     * Hapus data
     */

    public function hapus_portal() {
        if (!$this->input->is_ajax_request())
            return;

        if ($this->input->post('portal_id', TRUE) == '') {
            $respon['status'] = FALSE;
            $respon['pesan'] = 'Data ID tidak tersedia.';
            return $this->output->set_output(json_encode($respon));
        }

        // parameter hapus
        $hapus['portal_id'] = $this->input->post('portal_id', TRUE);
        if ($this->m_backend->hapus_data('sys_portal', $hapus)) {
            $data['status'] = TRUE;
            $data['pesan'] = 'Data berhasil dihapus.';
        } else {
            $error = $this->m_backend->get_db_error();
            $data['status'] = FALSE;
            $data['pesan'] = 'Data gagal dihapus.  Error kode : ' . $error['code'];
        }
        return $this->output->set_output(json_encode($data));
    }

    /*
     * Fungsi untuk mengecek apakah nomor portal sudah digunakan atau belum
     */

    public function cek_nomor_portal($no, $portal_id = NULL) {
        if ($this->m_backend->portal_number_is_exist($no, $portal_id)) {
            $this->form_validation->set_message('cek_nomor_portal', 'Nomor Portal sudah digunakan pada portal lain.');
            return FALSE;
        } else {
            return TRUE;
        }
    }

}
