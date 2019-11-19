<div class="box-body">
    <div class="form-group">
        <label class="col-sm-4 control-label">Nama Lengkap *</label>
        <div class="col-sm-7">
            <input class="form-control" placeholder="Nama Lengkap" data-validation="required" type="text" name="nama_lengkap">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-4 control-label">Nama Pengguna *</label>
        <div class="col-sm-7">
            <input class="form-control" placeholder="Nama Pengguna" data-validation="required" type="text" name="nama_pengguna">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-4 control-label">Kata Sandi *</label>
        <div class="col-sm-7">
            <input class="form-control" placeholder="Kata Sandi" type="password" name="kata_sandi">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-4 control-label">Ulangi Kata Sandi *</label>
        <div class="col-sm-7">
            <input class="form-control" placeholder="Ulangi Kata Sandi" type="password" name="ulangi_kata_sandi">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-4 control-label">Group Pengguna *</label>
        <div class="col-sm-7">
            <select class="form-control select2" name="grup[]" data-validation="required" data-elemen="grup_select" multiple="multiple" style="width: 100%;" data-placeholder="Grup Pengguna">
                <option></option>
                <?php
                if (isset($rs_grup) && !empty($rs_grup)) {
                    foreach ($rs_grup as $vgroup) {
                        echo '<option value="' . $vgroup['group_id'] . '">' . $vgroup['group_name'] . '</option>';
                    }
                }
                ?>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-4 control-label">Telepon *</label>
        <div class="col-sm-7">
            <input class="form-control" placeholder="Telepon" data-validation="required number" type="text" name="telepon">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-4 control-label">Email *</label>
        <div class="col-sm-7">
            <input class="form-control" placeholder="Email" data-validation="required email" type="email" name="email">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-4 control-label">Jenis Kelamin *
        </label>
        <div class="col-sm-7">
            <div class="radio">
                <label class="col-sm-4">
                    <input type="radio" name="jk" value="L" data-value="l" data-validation="required" data-validation-optional-if-answered="jk">
                    Laki Laki
                </label>
                <label class="col-sm-4">
                    <input type="radio" name="jk" value="P" data-value="p" data-validation="required" data-validation-optional-if-answered="jk">
                    Perempuan
                </label>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-4 control-label">Aktif</label>
        <div class="col-sm-7">
            <div class="checkbox">
                <label>
                    <input type="checkbox" value="1" name="status">
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

