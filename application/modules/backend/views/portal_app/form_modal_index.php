<!--<input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">-->
<div class="box-body">
    <div class="form-group">
        <label class="col-sm-3 control-label">Nama Portal *</label>
        <div class="col-sm-8">
            <input class="form-control" placeholder="Nama Portal" data-validation="required" type="text" name="portal_name">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label">Nomor Portal *</label>
        <div class="col-sm-8">
            <input class="form-control" placeholder="Nomor Portal" data-validation="required number" type="text" name="portal_number">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label">Deskripsi Portal *</label>
        <div class="col-sm-8">
            <input class="form-control" placeholder="Deskripsi Portal" data-validation="required" type="text" name="portal_desc">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label">URL</label>
        <div class="col-sm-8">
            <input class="form-control" placeholder="URL" type="text" name="portal_link">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label">Meta Title *</label>
        <div class="col-sm-8">
            <input class="form-control" placeholder="Meta Name" data-validation="required" type="text" name="portal_title">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label">Meta Tags *</label>
        <div class="col-sm-8">
            <input class="form-control" placeholder="Meta Tags" data-validation="required" type="text" name="portal_tag">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label">Meta Description *</label>
        <div class="col-sm-8">
            <input class="form-control" placeholder="Meta Description" data-validation="required" type="text" name="portal_meta_desc">
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