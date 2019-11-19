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

class MY_Form_validation extends CI_Form_validation {

    function run($module = '', $group = '') {
        (is_object($module)) AND $this->CI = &$module;
        return parent::run($group);
    }

    function set_error_message($kol, $msg) {
        $this->_error_messages[$kol] = $msg;
        $this->_error_array[$kol] = $msg;
    }

}
