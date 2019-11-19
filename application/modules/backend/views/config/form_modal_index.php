<!--<input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">-->
<div class="box-body">
    <div class="form-group">
        <label class="col-sm-4 control-label">Nama Pengaturan *</label>
        <div class="col-sm-7">
            <input class="form-control" placeholder="Nama Pengaturan" data-validation="required" type="text" name="config_name">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-4 control-label">Grup Pengaturan *</label>
        <div class="col-sm-7">
            <input class="form-control" placeholder="Grup Pengaturan" data-validation="required" type="text" name="config_group">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-4 control-label">Nilai *</label>
        <div class="col-sm-7">
            <input class="form-control" placeholder="Nilai" data-validation="required" type="text" name="config_value">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-4 control-label">Deskripsi *</label>
        <div class="col-sm-7">
            <input class="form-control" placeholder="Deskripsi" data-validation="required" type="text" name="config_desc">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-4 control-label">Portal</label>
        <div class="col-sm-7">
            <select name="config_portal_id" class="col-sm-4 form-control" placeholder="portal">
                <option value=""></option>
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
        <label class="col-sm-4 control-label">Terbatas</label>
        <div class="col-sm-7">
            <div class="checkbox">
                <label>
                    <input type="checkbox" value="1" name="restricted">
                </label>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-5 control-label">*) Wajib Diisi</label>
    </div>
    <div class="form-group">
        <div class="box-footer">
            <button type="submit" class="btn btn-info pull-right tombol-simpan">SIMPAN</button>
        </div>
    </div>
</div>