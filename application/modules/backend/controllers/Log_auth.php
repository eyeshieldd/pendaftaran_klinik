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

class Log_auth extends Backendbase {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        parent::display('log_auth/index', NULL, 'log_auth/footer_index');
    }

    public function get_list_auth_log() {

        $this->load->library('cldatatable');

        return $this->output->set_output(
                        $this->cldatatable->set_tabel('sys_auth_log')
                                ->set_kolom('log_id, user, waktu_login, ip, user_agent, keterangan, status')
                                ->get_datatable()
        );
    }

}
