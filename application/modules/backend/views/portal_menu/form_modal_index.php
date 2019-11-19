<div class="box-body">
    <div class="form-group">
        <label class="col-sm-3 control-label">Portal *</label>
        <div class="col-sm-8">
            <select name="portal_id" class="col-sm-4 form-control" id="form-modal-portal-id" data-validation="required">
                <?php
                if (isset($rs_portal) && !empty($rs_portal))
                    foreach ($rs_portal as $vportal) {
                        echo'<option value="' . $vportal['portal_id'] . '">' . $vportal['portal_name'] . '</option>';
                    }
                ?>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label">Nama Menu *</label>
        <div class="col-sm-8">
            <input class="form-control" placeholder="Nama Menu" data-validation="required" type="text" name="menu_name">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label">Deskripsi Menu *</label>
        <div class="col-sm-8">
            <input class="form-control" placeholder="Deskripsi Menu" data-validation="required" type="text" name="menu_desc">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label">Posisi *</label>
        <div class="col-sm-8">
            <select class="form-control" name="menu_position">
                <option></option>
                <?php
                foreach ($rs_posisi_menu as $ipos => $vpos) {
                    echo '<option value="' . $ipos . '">' . $vpos . '</option>';
                }
                ?>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label">Nomor Urut *</label>
        <div class="col-sm-8">
            <input class="form-control" placeholder="Nomor Urut" data-validation="required" type="text" name="menu_order">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label">Sub Menu Dari</label>
        <div class="col-sm-8">
            <select class="form-control pilihan-submenu-portal" id="select-portal" name="menu_parent">
                <option></option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label">Alamat *</label>
        <div class="col-sm-8">
            <input class="form-control" placeholder="Alamat" data-validation="required" type="text" name="menu_link">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label">icon</label>
        <div class="col-sm-8">
            <input class="form-control" placeholder="Icon" type="text" name="menu_icon">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label">Font Icon</label>
        <div class="col-sm-8">
            <input class="form-control" placeholder="Font Icon" type="text" name="menu_fonticon">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label">Ditampilkan</label>
        <div class="col-sm-8">
            <div class="checkbox">
                <label>
                    <input type="checkbox" value="1" name="menu_show">
                </label>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-5 control-label">*) Wajib Diisi</label>
    </div>
    <div class="form-group">
        <div class="box-footer">
            Diubah pada : <span id="text-mdd"></span>
            <button type="submit" class="btn btn-info pull-right tombol-simpan">SIMPAN</button>
        </div>
    </div>
</div>