<!--<input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">-->
<div class="box-body">
    <div class="form-group">
        <label class="col-sm-3 control-label">Nama Group *</label>
        <div class="col-sm-8">
            <input class="form-control" placeholder="Nama Group" data-validation="required" type="text" name="group_name">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label">Deskripsi Group *</label>
        <div class="col-sm-8">
            <input class="form-control" placeholder="Deskripsi Group" data-validation="required" type="text" name="group_desc">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label">Tautan Awal *</label>
        <div class="col-sm-8">
            <input class="form-control" placeholder="Link Awal" data-validation="required" type="text" name="group_portal">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label">Terbatas</label>
        <div class="col-sm-8">
            <div class="checkbox">
                <label>
                    <input type="checkbox" value="1" name="group_restricted">
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