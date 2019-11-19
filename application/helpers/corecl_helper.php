<?php

/*
 * By : Praditya Kurniawan
 * website : http://masiyak.com
 * email : aku@masiyak.com
 *
 * - CIRCLE LABS - 
 * http://circlelabs.id
 *  
 * 
 * Berisi semua function yang dibutuhkan sejak pertama kali jika menggunakan 
 * core circlelabs
 */

defined('BASEPATH') or exit('No direct script access allowed');


/*
 * Helper untuk cek kondisi sesuai permission
 */

function cek_akses($objek = null, $hak = null) {
    // instance
    $ci = & get_instance();
    if (empty($hak))
        return;
    $a = strlen($hak) - 1;
    while ($a >= 0) {
        if (isset($ci->session->ROLE[substr($hak, $a, 1)]) &&
                $ci->session->ROLE[substr($hak, $a, 1)] == 1)
            return $objek;
        $a--;
    }
    return;
}
