<?php

defined('BASEPATH') or exit('No direct script access allowed');

/*
 * FUNGSI ANGKA
 */

function format_rupiah($nilai = 0, $presc = false)
{
    if ($presc)
        return 'Rp ' . number_format($nilai, 2, ',', '.');
    else
        return 'Rp ' . number_format($nilai, 0, ',', '.');
}

function convert_rupiah($val = 0)
{
    $result = str_replace(['Rp ', ',', ' '], '', $val);

    if ($result == 0) {
        return;
    } else {
        return $result;
    }
}

function format_rupiah_negatif_format($nilai = 0, $presc = false)
{
    $awal = '';
    $akhir = '';
    if ($nilai < 0) {
        $awal = '<span class="txt-color-red">- ';
        $akhir = '</span>';
        $nilai = -$nilai;
    }
    if ($presc)
        return $awal . 'Rp ' . number_format($nilai, 2, ',', '.') . $akhir;
    else
        return $awal . 'Rp ' . number_format($nilai, 0, ',', '.') . $akhir;
}

function format_cm($panjang = 0)
{
    return number_format($panjang, 2, ',', '.') . ' cm';
}

/*
 * FORMAT TANGGAL
 */

function format_tanggal_lengkap($tanggal)
{
    $hari = array(
        '1' => 'Senin',
        '2' => 'Selasa',
        '3' => 'Rabu',
        '4' => 'Kamis',
        '5' => 'Jumat',
        '6' => 'Sabtu',
        '7' => 'Minggu',
    );

    $hari_en = date('N', strtotime($tanggal));

    $bulan = array(
        '01' => 'Januari',
        '02' => 'Februari',
        '03' => 'Maret',
        '04' => 'April',
        '05' => 'Mei',
        '06' => 'Juni',
        '07' => 'Juli',
        '08' => 'Agustus',
        '09' => 'September',
        '10' => 'Oktober',
        '11' => 'November',
        '12' => 'Desember',
    );

    // pecah string
    $tanggalan = explode('-', $tanggal);
    return $hari[$hari_en] . ', ' . $tanggalan[2] . ' ' . $bulan[$tanggalan[1]] . ' ' . $tanggalan[0];
}

function format_tanggal_indonesia($tanggal)
{
    if (empty($tanggal) || $tanggal == '0000-00-00') {
        return;
    }

    $bulan = array(
        '01' => 'Januari',
        '02' => 'Februari',
        '03' => 'Maret',
        '04' => 'April',
        '05' => 'Mei',
        '06' => 'Juni',
        '07' => 'Juli',
        '08' => 'Agustus',
        '09' => 'September',
        '10' => 'Oktober',
        '11' => 'November',
        '12' => 'Desember',
    );

    // pecah string
    $tanggalan = explode('-', $tanggal);
    return $tanggalan[2] . ' ' . $bulan[$tanggalan[1]] . ' ' . $tanggalan[0];
}

function format_tanggal_en($tanggal)
{
    if (empty($tanggal) || $tanggal == '0000-00-00') {
        return;
    }

    $bulan = array(
        '01' => 'January',
        '02' => 'February',
        '03' => 'March',
        '04' => 'April',
        '05' => 'May',
        '06' => 'June',
        '07' => 'July',
        '08' => 'August',
        '09' => 'September',
        '10' => 'October',
        '11' => 'November',
        '12' => 'December',
    );

    // pecah string
    $tanggalan = explode('-', $tanggal);
    return $tanggalan[2] . ' ' . $bulan[$tanggalan[1]] . ' ' . $tanggalan[0];
}

function format_tanggal_indonesia_pendek($tanggal)
{
    if (empty($tanggal) || $tanggal == '0000-00-00') {
        return;
    }
    $bulan = array(
        '01' => 'Jan',
        '02' => 'Feb',
        '03' => 'Mar',
        '04' => 'Apr',
        '05' => 'Mei',
        '06' => 'Jun',
        '07' => 'Jul',
        '08' => 'Agu',
        '09' => 'Sep',
        '10' => 'Okt',
        '11' => 'Nov',
        '12' => 'Des',
    );

    // pecah string
    $tanggalan = explode('-', $tanggal);
    return $tanggalan[2] . ' ' . $bulan[$tanggalan[1]] . ' ' . $tanggalan[0];
}

function romanic_number($integer, $upcase = true)
{
    $table = array('M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400, 'C' => 100, 'XC' => 90, 'L' => 50, 'XL' => 40, 'X' => 10, 'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1);
    $return = '';
    while ($integer > 0) {
        foreach ($table as $rom => $arb) {
            if ($integer >= $arb) {
                $integer -= $arb;
                $return .= $rom;
                break;
            }
        }
    }

    return $return;
}

function format_bulan_indonesia($param)
{
    $bulan = array(
        '01' => 'Januari',
        '02' => 'Februari',
        '03' => 'Maret',
        '04' => 'April',
        '05' => 'Mei',
        '06' => 'Juni',
        '07' => 'Juli',
        '08' => 'Agustus',
        '09' => 'September',
        '10' => 'Oktober',
        '11' => 'November',
        '12' => 'Desember',
    );

    return $bulan[$param];
}

function daftar_nama_bulan($i = null)
{
    $bulan = array(
        '01' => 'Januari',
        '02' => 'Februari',
        '03' => 'Maret',
        '04' => 'April',
        '05' => 'Mei',
        '06' => 'Juni',
        '07' => 'Juli',
        '08' => 'Agustus',
        '09' => 'September',
        '10' => 'Oktober',
        '11' => 'November',
        '12' => 'Desember',
    );
    if ($i != null && $i > 0 && $i <= 12) {
        $i = '0' . $i;
        return $bulan[substr($i, -2)];
    }

    return $bulan;
}

// membaca angka
function eja_bilangan($x)
{
    $angka = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
    $temp = "";
    if ($x < 12) {
        $temp = " " . $angka[$x];
    } else if ($x < 20) {
        $temp = eja_bilangan($x - 10) . " belas";
    } else if ($x < 100) {
        $temp = eja_bilangan($x / 10) . " puluh" . eja_bilangan($x % 10);
    } else if ($x < 200) {
        $temp = " seratus" . eja_bilangan($x - 100);
    } else if ($x < 1000) {
        $temp = eja_bilangan($x / 100) . " ratus" . eja_bilangan($x % 100);
    } else if ($x < 2000) {
        $temp = " seribu" . eja_bilangan($x - 1000);
    } else if ($x < 1000000) {
        $temp = eja_bilangan($x / 1000) . " ribu" . eja_bilangan($x % 1000);
    } else if ($x < 1000000000) {
        $temp = eja_bilangan($x / 1000000) . " juta" . eja_bilangan($x % 1000000);
    } else if ($x < 1000000000000) {
        $temp = eja_bilangan($x / 1000000000) . " milyar" . eja_bilangan(fmod($x, 1000000000));
    } else if ($x < 1000000000000000) {
        $temp = eja_bilangan($x / 1000000000000) . " trilyun" . eja_bilangan(fmod($x, 1000000000000));
    }
    return $temp;
}

/*
 * Helper untuk menampilkan isi file dalam suatu direktori
 * 
 */

function tampilkan_isi_direktori($dir)
{
    // array to hold return value
    $retval = array();

    // add trailing slash if missing
    if (substr($dir, -1) != "/")
        $dir .= "/";

    // open pointer to directory and read list of files
    $d = @dir($dir) or die("getFileList: Failed opening directory $dir for reading");
    while (false !== ($entry = $d->read())) {
        // skip hidden files
        if ($entry[0] == ".")
            continue;
        if (is_dir("$dir$entry")) {
            $retval[] = array(
                "url" => "$dir$entry/",
                "name" => "$entry/",
                "type" => filetype("$dir$entry"),
                "size" => 0,
                "lastmod" => filemtime("$dir$entry")
            );
        } elseif (is_readable("$dir$entry")) {
            $retval[] = array(
                "url" => "$dir$entry",
                "name" => "$entry",
                "type" => mime_content_type($dir . $entry),
                "size" => filesize($dir . $entry),
                "lastmod" => filemtime($dir . $entry)
            );
        }
    }
    $d->close();

    return $retval;
}

/**
 * 
 * Helper untuk mengubah microsecond menjadi format date
 * 
 */
function microtime_to_date($micro, $par = null)
{
    if (empty($par))
        $par = 'Y-m-d H:i:s';

    //    $micro = sprintf("%06d", ($t - floor($micro)) * 1000000);
    $d = new DateTime(date('Y-m-d H:i:s.', $micro));

    return $d->format($par);
}
