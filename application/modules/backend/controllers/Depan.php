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

class Depan extends Backendbase {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        // hitung jumlah portal
        $data['total_portal'] = $this->m_backend->get_total_portal();
        $data['total_menu'] = $this->m_backend->get_total_menu();
        $data['total_grup'] = $this->m_backend->get_total_grup();
        $data['total_user'] = $this->m_backend->get_total_user();
        parent::display('depan/index', $data);
    }

}
