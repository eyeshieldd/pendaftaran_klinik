<!-- Main content -->
<section class="content">
    <!-- Default box -->
    <div class="box">
        <!-- /.box-header -->
        <div class="box-body">
            <form class="form-horizontal no-padding">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Portal</label>
                            <div class="col-sm-8">
                                <select name="portal_id" id="form-pilih-portal" class="col-sm-4 form-control">
                                    <option></option>
                                    <?php
                                    if (isset($rs_portal) && !empty($rs_portal))
                                        foreach ($rs_portal as $vportal) {
                                            echo'<option value="' . $vportal['portal_id'] . '">' . $vportal['portal_name'] . '</option>';
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label class="col-sm-4 control-label">User Group</label>
                            <div class="col-sm-8">
                                <select name="group_id" id="form-pilih-group" class="col-sm-4 form-control">
                                    <option></option>
                                    <?php
                                    if (isset($rs_user_group) && !empty($rs_user_group))
                                        foreach ($rs_user_group as $vgroup) {
                                            echo'<option value="' . $vgroup['group_id'] . '">' . $vgroup['group_name'] . '</option>';
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <button class="btn btn-primary btn-sm" id="tombol-cari" onclick="return false"><i class="fa fa-search"></i> CARI</button>
                    </div>
                    <div class="col-lg-2">
                        <button class="btn btn-success btn-sm pull-right" id="tombol-simpan" onclick="return false"><i class="fa fa-save"></i> SIMPAN</button>
                    </div>
                </div>
            </form>
            <form class="form-horizontal no-padding" id="form-permission">
                <input type="hidden" name="portal_id">
                <input type="hidden" name="group_id">
                <table id="tmenu" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th width="5%">No.</th>
                            <th width="5%"><input type="checkbox" class="checkall-header"></th>
                            <th width="25%">Nama Menu</th>
                            <th width="10%">Portal</th>
                            <th width="15%">User</th>
                            <th width="10%">read</th>
                            <th width="10%">create</th>
                            <th width="10%">update</th>
                            <th width="10%">delete</th>
                        </tr>
                    </thead>
                </table>
            </form>
        </div>
    </div>
</section>