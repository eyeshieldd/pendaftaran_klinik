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

class User extends Backendbase
{

    // config untuk form validasi
    public $form_conf = array(
        array('field' => 'nama_pengguna', 'label' => 'Nama User', 'rules' => 'required'),
        array('field' => 'nama_lengkap', 'label' => 'Deskripsi User', 'rules' => 'required'),
        array('field' => 'grup', 'label' => 'Grup Pengguna', 'rules' => ''),
        array('field' => 'telepon', 'label' => 'Telepon', 'rules' => 'required'),
        array('field' => 'email', 'label' => 'Email', 'rules' => 'required|valid_email'),
        array('field' => 'jk', 'label' => 'Meta Title', 'rules' => 'trim'),
        array('field' => 'status', 'label' => 'Meta Title', 'rules' => 'trim'),
    );

    public function __construct()
    {
        parent::__construct();
        // load model
        $this->load->model('m_backend');
    }

    public function index()
    {
        // load css
        $this->load_css('assets/backend/js/select2/dist/css/select2.min.css');
        $this->load_js('assets/backend/js/select2/dist/js/select2.full.min.js');
        $data['rs_grup'] = $this->m_backend->get_list_user_group();
        parent::display('user/index', $data, 'user/footer_index');
    }

    public function get_list_user()
    {
        $rs_user = $this->m_backend->get_list_user_dttabel();
        if (!empty($rs_user['data'])) {
            // get data user group
            $rs_grup = array();
            foreach ($this->m_backend->get_all_user_group() as $i => $vusgrup) {
                $rs_grup[$vusgrup['user_id']][] = $vusgrup['group_name'];
            }
            $no = 1;
            foreach ($rs_user['data'] as $i => $vuser) {
                //tombol
                $aksi_kolom = '<div class="pull-right"><button class="btn btn-xs btn-info edit-user" data-id="' . $vuser['user_id'] . '"><i class="fa fa-pencil"></i> ubah</button>';
                $aksi_kolom .= ' <button class="btn btn-xs btn-danger hapus-user" data-id="' . $vuser['user_id'] . '"><i class="fa fa-trash"></i> hapus</button></div>';
                // data
                $rs_user['data'][$i]['no']     = $no++;
                $rs_user['data'][$i]['status'] = $vuser['status'] == 1 ? '<i class="fa fa-check-circle text-green" title="Aktif"></i> Aktif' : '<i class="fa fa-close text-red" title="Tidak Aktif"></i> Tidak';
                $rs_user['data'][$i]['grup']   = isset($rs_grup[$vuser['user_id']]) ? implode(',  ', $rs_grup[$vuser['user_id']]) : '-';
                // membuat opsi pemilihan default group
                $pilihan = '<select class="form-control input-sm pilih-grup" data-id="' . $vuser['user_id'] . '" style="width:100%">';
                foreach ($rs_user['option_grup'][$vuser['user_id']] as $vdasar) {
                    $selected = $vdasar['default'] == 1 ? 'selected="selected"' : '';
                    $pilihan .= '<option value="' . $vdasar['group_id'] . '" ' . $selected . '>' . $vdasar['group_name'] . '</option>';
                }
                $pilihan .= '</select>';
                $rs_user['data'][$i]['dasar'] = $pilihan;
                $rs_user['data'][$i]['aksi']  = $aksi_kolom;
            }
        }
        unset($rs_user['option_grup']);
        return $this->output->set_output(json_encode($rs_user));
    }

    public function proses_tambah()
    {
        if (!$this->input->is_ajax_request()) {
            return;
        }

        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules($this->form_conf);
        $this->form_validation->set_rules('kata_sandi', 'Kata Sandi', 'required');
        $this->form_validation->set_rules('ulangi_kata_sandi', 'Ulangi Kata Sandi', 'required|matches[kata_sandi]');
        if ($this->form_validation->run($this) == false) {
            $data['pesan']  = validation_errors();
            $data['status'] = false;
            return $this->output->set_output(json_encode($data));
        }

        // set data
        $userid                  = str_replace(',', '.', microtime(true));
        $userid                  = explode('.', $userid);
        $simpan['user_id']       = $userid[0];
        $simpan['username']      = $this->input->post('nama_pengguna', true);
        $simpan['kata_sandi']    = password_hash($this->config->item('encryption_key') . $this->input->post('kata_sandi', true), PASSWORD_BCRYPT);
        $simpan['nama_lengkap']  = $this->input->post('nama_lengkap', true);
        $simpan['telepon']       = $this->input->post('telepon', true);
        $simpan['email']         = $this->input->post('email', true);
        $simpan['jenis_kelamin'] = $this->input->post('jk', true);
        $simpan['status']        = $this->input->post('status', true) == '' ? 0 : 1;
        $simpan['registered_by'] = 'developer';
        $simpan['mdd']           = date('Y-m-d H:i:s');
        if ($this->m_backend->tambah_user($simpan, $this->input->post('grup'))) {
            $result['status'] = true;
            $result['pesan']  = 'User ' . $simpan['username'] . ' berhasil ditambahkan.';
        } else {
            $eror             = $this->m_backend->get_db_error();
            $result['status'] = false;
            $result['pesan']  = 'Data user ' . $simpan['username'] . ' gagal ditambahkan. Eror kode : ' . $eror['code'];
        }
        return $this->output->set_output(json_encode($result));
    }

    public function get_detail_user()
    {
        if (!$this->input->is_ajax_request() || $this->input->post('data_id', true) == '') {
            return;
        }

        // ambil detail portal berdasar ID
        $result = $this->m_backend->get_detail_user_id($this->input->post('data_id', true));
        // ambil data grup berdasar user id
        $rs_grup = array();
        foreach ($this->m_backend->get_group_by_user_id($this->input->post('data_id', true)) as $vgroupname) {
            $rs_grup[] = $vgroupname['group_id'];
        };
        if (!empty($result)) {
            $data['status'] = true;
            $data['data']   = $result;
            $data['grup']   = $rs_grup;
        } else {
            $data['status'] = false;
            $data['data']   = null;
            $data['pesan']  = $this->m_backend->get_error_message();
        }
        return $this->output->set_output(json_encode($data));
    }

    public function proses_ubah()
    {
        if (!$this->input->is_ajax_request()) {
            return;
        }

        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules($this->form_conf);
        if ($this->input->post('kata_sandi') != '') {
            $this->form_validation->set_rules('kata_sandi', 'Kata Sandi', 'required');
            $this->form_validation->set_rules('ulangi_kata_sandi', 'Kata Sandi', 'required|matches[kata_sandi]');
        }
        if ($this->form_validation->run($this) == false) {
            $data['pesan']  = validation_errors();
            $data['status'] = false;
            return $this->output->set_output(json_encode($data));
        }

        // set data
        if ($this->input->post('kata_sandi') != '' && ($this->input->post('kata_sandi') == $this->input->post('ulangi_kata_sandi'))) {
            $simpan['kata_sandi'] = password_hash($this->config->item('encryption_key') . $this->input->post('kata_sandi'), PASSWORD_BCRYPT);
        }

        $simpan['username']      = $this->input->post('nama_pengguna', true);
        $simpan['nama_lengkap']  = $this->input->post('nama_lengkap', true);
        $simpan['telepon']       = $this->input->post('telepon', true);
        $simpan['email']         = $this->input->post('email', true);
        $simpan['jenis_kelamin'] = $this->input->post('jk', true);
        $simpan['status']        = $this->input->post('status', true) == '' ? 0 : 1;
        $simpan['registered_by'] = 'developer';
        $simpan['mdd']           = date('Y-m-d H:i:s');
        if ($this->m_backend->ubah_user($simpan, $this->input->post('grup', true))) {
            $result['status'] = true;
            $result['pesan']  = 'User ' . $simpan['nama_lengkap'] . ' berhasil diubah.';
        } else {
            $eror             = $this->m_backend->get_db_error();
            $result['status'] = false;
            $result['pesan']  = 'User ' . $simpan['nama_lengkap'] . ' gagal diubah. Eror kode : ' . $eror['code'];
        }
        return $this->output->set_output(json_encode($result));
    }

    /*
     * Hapus data
     */

    public function hapus_user()
    {
        if (!$this->input->is_ajax_request()) {
            return;
        }

        if ($this->input->post('user_id') == '') {
            $respon['status'] = false;
            $respon['pesan']  = 'Data ID tidak tersedia.';
            return $this->output->set_output(json_encode($respon));
        }

        // parameter hapus
        $hapus['user_id'] = $this->input->post('user_id');
        if ($this->m_backend->hapus_data('sys_user', $hapus)) {
            $data['status'] = true;
            $data['pesan']  = 'Data berhasil dihapus.';
        } else {
            $error          = $this->m_backend->get_db_error();
            $data['status'] = false;
            $data['pesan']  = 'Data gagal dihapus.  Error kode : ' . $error['code'];
        }
        return $this->output->set_output(json_encode($data));
    }

    public function ubah_grup_dasar()
    {
        if (!$this->input->is_ajax_request()) {
            return;
        }

        if ($this->input->post('user_id') == '' || $this->input->post('grup_id') == '') {
            return;
        }

        if ($this->m_backend->ubah_default_grup($this->input->post('user_id'), $this->input->post('grup_id'))) {
            $result['status'] = true;
            $result['pesan']  = 'Data berhasil diubah.';
        } else {
            $eror             = $this->m_backend->get_db_error();
            $result['status'] = false;
            $result['pesan']  = 'Data gagal diubah. Eror kode : ' . $eror['code'];
        }
        return $this->output->set_output(json_encode($result));
    }

}
