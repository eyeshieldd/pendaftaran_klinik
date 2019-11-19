<?php

/*
 *    CIRCLE LABS ID
 *    WWW.CIRCLELABS.ID
 */

defined('BASEPATH') or exit('No direct script access allowed');

class Dokter extends Portal
{

    public $form_conf = array(
        array('field' => 'nama_dokter', 'label' => 'Nama Barang', 'rules' => 'required|max_length[255]'),
        array('field' => 'jadwal_periksa', 'label' => 'Kategori', 'rules' => 'required|max_length[255]'),
        array('field' => 'jadwal_jam', 'label' => 'Harga', 'rules' => 'required|max_length[255]'),
        // array('field' => 'status', 'label' => 'Status', 'rules' => 'required'),
    );

    public function __construct()
    {
        parent::__construct();
        $this->load->model('m_dokter');
    }

    public function index()
    {
        $this->_set_page_role('r');

        // load select 2
        $this->load_js('assets/operator/js/plugins/select2/js/select2.full.min.js');
        $this->load_css('assets/operator/js/plugins/select2/css/select2.min.css');

        // load jquery validator
        // $this->load_css('assets/global/jquery-form-validator-net/form-validator/theme-default.min.css');
        // $this->load_js('assets/global/jquery-form-validator-net/form-validator/jquery.form-validator.min.js');

        parent::display('index', null, 'footer_index');
    }

    public function get_list_dokter()
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }

        $this->_set_page_role('r');

        // tombol
        $tombol = '<div class="text-center">
        <button type="button" class="btn btn-success mr-5 mb-5  ubah-data" data-id="{{id_dokter}}"><i class="fa fa-pencil"></i> </button>
        <button type="button" class="btn btn-danger mr-5 mb-5 hapus-data" data-id="{{id_dokter}}"><i class="fa fa-trash"></i> </button>
        </div>';

        
        $this->load->library('cldatatable');

        return $this->output->set_output(
            $this->cldatatable->set_tabel('dokter')
            ->set_kolom('id_dokter, nama_dokter, id_poli, jadwal_periksa, jadwal_jam')       
            ->tambah_kolom('aksi', $tombol)
            ->get_datatable()
        );
    }

    public function proses_tambah_data()
    {
        // set permission
        $this->_set_page_role('c');

        // validasi hanya request lewat ajax
        if (!$this->input->is_ajax_request()) {
            show_404();
        }

        // load form validation
        $this->load->library('form_validation');
        // $this->form_validation->set_error_delimiters('<p>', '');
        $this->form_validation->set_rules($this->form_conf);

        // run validation
        if ($this->form_validation->run($this) == false) {
            $data['pesan']  = validation_errors();
            $data['status'] = false;
            return $this->output->set_output(json_encode($data));
        }

        // library uuid
        $this->load->library('uuid');

        $data_simpan['id']           = $this->uuid->v4(true);
        $data_simpan['nama_barang']  = $this->input->post('nama_barang', true);
        $data_simpan['kategori']     = $this->input->post('kategori', true);
        $data_simpan['harga']        = $this->input->post('harga', true);
        $data_simpan['tanggal_beli'] = $this->input->post('tanggal_beli', true);
        $data_simpan['status']       = $this->input->post('status', true);
        $data_simpan['ctb']          = $this->com_user['user_id'];
        $data_simpan['ctd']          = date('Y-m-d H:i:s');
        $data_simpan['mdb']          = $this->com_user['user_id'];
        $data_simpan['mdd']          = date('Y-m-d H:i:s');

        if ($this->m_contoh_satu->tambah_data('contoh_tabel', $data_simpan)) {
            $result['status'] = true;
            $result['pesan']  = 'Data berhasil disimpan.';
        } else {
            $eror             = $this->m_pl_kejuruan->get_db_error();
            $result['status'] = false;
            $result['pesan']  = 'Data gagal disimpan. Eror kode : ' . $eror['code'];
        }
        return $this->output->set_output(json_encode($result));

    }

    public function get_detail_data()
    {

        // set permission
        $this->_set_page_role('c');

        // validasi hanya request lewat ajax
        if (!$this->input->is_ajax_request()) {
            show_404();
        }
        // get data
        $data['data'] = $this->m_contoh_satu->get_detail_data($this->input->post('data_id'));
        // validasi jika kosong
        if (empty($data)) {
            return $this->output->set_output(json_encode(array('pesan' => 'data tidak ditemukan!', 'data' => null)));
        }
        $data['status'] = true;
        return $this->output->set_output(json_encode($data));

    }

    public function proses_ubah_data()
    {
        // set permission
        $this->_set_page_role('u');

        // validasi hanya request lewat ajax
        if (!$this->input->is_ajax_request()) {
            show_404();
        }

        // load form validation
        $this->load->library('form_validation');
        // $this->form_validation->set_error_delimiters('<p>', '');
        $this->form_validation->set_rules($this->form_conf);

        // run validation
        if ($this->form_validation->run($this) == false) {
            $data['pesan']  = validation_errors();
            $data['status'] = false;
            return $this->output->set_output(json_encode($data));
        }

        $data_simpan['nama_barang']  = $this->input->post('nama_barang', true);
        $data_simpan['kategori']     = $this->input->post('kategori', true);
        $data_simpan['harga']        = $this->input->post('harga', true);
        $data_simpan['tanggal_beli'] = $this->input->post('tanggal_beli', true);
        $data_simpan['status']       = $this->input->post('status', true);
        $data_simpan['ctb']          = $this->com_user['user_id'];
        $data_simpan['ctd']          = date('Y-m-d H:i:s');
        $data_simpan['mdb']          = $this->com_user['user_id'];
        $data_simpan['mdd']          = date('Y-m-d H:i:s');

        if ($this->m_contoh_satu->ubah_data('contoh_tabel', 'id', $this->input->post('id'), $data_simpan)) {
            $result['status'] = true;
            $result['pesan']  = 'Data berhasil diubah.';
        } else {
            $eror             = $this->m_contoh_satu->get_db_error();
            $result['status'] = false;
            $result['pesan']  = 'Data gagal diubah. Eror kode : ' . $eror['code'];
        }
        return $this->output->set_output(json_encode($result));
    }

    public function proses_hapus_data(){
         // set permission
        $this->_set_page_role('d');

        // validasi hanya request lewat ajax
        if (!$this->input->is_ajax_request()) {
            show_404();
        }

        // parameter
        $hapus['id'] = $this->input->post('data_id');
        if ($this->m_contoh_satu->hapus_data('contoh_tabel', $hapus)) {
            $result['status'] = true;
            $result['pesan']  = 'Data berhasil dihapus.';
        } else {
            $eror             = $this->m_contoh_satu->get_db_error();
            $result['status'] = false;
            $result['pesan']  = 'Data gagal dihapus. Eror kode : ' . $eror['code'];
        }
        return $this->output->set_output(json_encode($result));


    }

}
