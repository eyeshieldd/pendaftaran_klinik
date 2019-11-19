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

class Permission extends Backendbase {

    public function __construct() {
        parent::__construct();
    }

    function index() {
        // list portal
        $data['rs_portal'] = $this->m_backend->get_all_portal();
        // list user group
        $data['rs_user_group'] = $this->m_backend->get_all_usergroup();
        parent::display('permission/index', $data, 'permission/footer_index');
    }

    function get_list_menu_permission() {
        if (!$this->input->is_ajax_request())
            return;
        $rs_menu = $this->m_backend->get_list_menu_permission_by_portal_id(array($this->input->post('group_id', TRUE), $this->input->post('portal_id', TRUE)));
        $option['recordsTotal'] = count($rs_menu);
        $option['recordsFiltered'] = count($rs_menu);
        $option['draw'] = $this->input->post('draw', TRUE);
        $option['data'] = $rs_menu;
        $no = 1;
        foreach ($rs_menu as $imenu => $vmenu) {
            $option['data'][$imenu]['no'] = $no++ . '.';
            $checkedall = $vmenu['create'] . $vmenu['read'] . $vmenu['update'] . $vmenu['delete'] == '1111' ? 'checked="checked"' : '';
            $option['data'][$imenu]['checkpermission'] = '<input type="checkbox" class="checked-all ca' . $vmenu['menu_id'] . '" ' . $checkedall . ' value="' . $vmenu['menu_id'] . '" onclick="cek_sub_all()">';
            $option['data'][$imenu]['menu_name'] = $vmenu['menu_name'] . ' &nbsp; <i class="fa"></i> ';
            $option['data'][$imenu]['portal'] = 'portal';
            $option['data'][$imenu]['user'] = 'user';
            $option['data'][$imenu]['create'] = $vmenu['create'] == 1 ? '<input type="checkbox" class="ch' . $vmenu['menu_id'] . '" name="c' . $vmenu['menu_id'] . '" value=1 checked="checked" onclick="cek_sub_all(' . $vmenu['menu_id'] . ')">' : '<input type="checkbox" class="ch' . $vmenu['menu_id'] . '" name="c' . $vmenu['menu_id'] . '" value=1 onclick="cek_sub_all(' . $vmenu['menu_id'] . ')">';
            $option['data'][$imenu]['update'] = $vmenu['update'] == 1 ? '<input type="checkbox" class="ch' . $vmenu['menu_id'] . '" name="u' . $vmenu['menu_id'] . '" value=1 checked="checked" onclick="cek_sub_all(' . $vmenu['menu_id'] . ')">' : '<input type="checkbox" class="ch' . $vmenu['menu_id'] . '" name="u' . $vmenu['menu_id'] . '" value=1 onclick="cek_sub_all(' . $vmenu['menu_id'] . ')">';
            $option['data'][$imenu]['read'] = $vmenu['read'] == 1 ? '<input type="checkbox" class="ch' . $vmenu['menu_id'] . '" name="r' . $vmenu['menu_id'] . '" value=1 checked="checked" onclick="cek_sub_all(' . $vmenu['menu_id'] . ')">' : '<input type="checkbox" class="ch' . $vmenu['menu_id'] . '" name="r' . $vmenu['menu_id'] . '" value=1 onclick="cek_sub_all(' . $vmenu['menu_id'] . ')">';
            $option['data'][$imenu]['delete'] = $vmenu['delete'] == 1 ? '<input type="checkbox" class="ch' . $vmenu['menu_id'] . '" name="d' . $vmenu['menu_id'] . '" value=1 checked="checked" onclick="cek_sub_all(' . $vmenu['menu_id'] . ')">' : '<input type="checkbox" class="ch' . $vmenu['menu_id'] . '" name="d' . $vmenu['menu_id'] . '" value=1 onclick="cek_sub_all(' . $vmenu['menu_id'] . ')">';
        }

        return $this->output->set_output(json_encode($option));
    }

    function proses_set_permission() {
        if (!$this->input->is_ajax_request())
            show_404();

        $permission = array();
        // get menu by portal and user group
        foreach ($this->m_backend->get_list_menu_permission_by_portal_id(array($this->input->post('group_id', TRUE), $this->input->post('portal_id'))) as $imenu => $dt_menu) {
            $user_permission = '';
            $user_permission .= $this->input->post('c' . $dt_menu['menu_id'], TRUE) != '' ? '1' : 0;
            $user_permission .= $this->input->post('r' . $dt_menu['menu_id'], TRUE) != '' ? '1' : 0;
            $user_permission .= $this->input->post('u' . $dt_menu['menu_id'], TRUE) != '' ? '1' : 0;
            $user_permission .= $this->input->post('d' . $dt_menu['menu_id'], TRUE) != '' ? '1' : 0;
            // jika semua 0 maka tidak perlu set permission
            if ($user_permission == '0000')
                continue;

            $permission[$imenu]['group_id'] = $this->input->post('group_id', TRUE);
            $permission[$imenu]['menu_id'] = $dt_menu['menu_id'];
            $permission[$imenu]['permission'] = $user_permission;
        }

        if ($this->m_backend->proses_hak_akses($permission, $this->input->post('portal_id', TRUE), $this->input->post('group_id', TRUE))) {
            $result['status'] = true;
            $result['pesan'] = 'Hak akses berhasil diubah.';
        } else {
            $eror = $this->m_backend->get_db_error();
            $result['status'] = false;
            $result['pesan'] = 'Hak akses gagal diubah. Eror kode : ' . $eror['code'];
        }
        return $this->output->set_output(json_encode($result));
    }

}
