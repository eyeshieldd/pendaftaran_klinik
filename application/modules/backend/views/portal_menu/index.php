<!-- Main content -->
<section class="content">
    <!-- Default box -->
    <div class="box">
        <!-- /.box-header -->
        <div class="box-body">
            <div class="row">
                <div class="col-lg-4">
                    <form class="form-horizontal no-padding">
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
                    </form>
                </div>
                <div class="col-lg-6"></div>
                <div class="col-lg-2"><label class="control-label">&nbsp;</label><button class="btn btn-primary btn-sm pull-right" data-toggle="modal" data-target="#modal-tambah"><i class="fa fa-plus"></i> MENU BARU</button></div>
            </div>
            <table id="tmenu" class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th width="5%">No.</th>
                        <th width="23%">Menu</th>
                        <th width="5%">Urutan</th>
                        <th width="10%">Menu Posisi</th>
                        <th width="25%">Deskripsi</th>
                        <th width="15%">link</th>
                        <th width="7%">Tampil</th>
                        <th width="10%">Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</section>
<!--MODAL FORM TAMBAH-->
<div class="modal fade" id="modal-tambah" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><i class="fa fa-plus-circle"></i> TAMBAH MENU</h4>
            </div>
            <div class="modal-body">
                <!-- /.box-header -->
                <form class="form-horizontal" id="form-tambah" onsubmit="return false">
                    <?php $this->load->view('backend/portal_menu/form_modal_index') ?>
                </form>
                <!-- /.box-body -->
            </div>
        </div>
    </div>
</div>
<!--MODAL FORM UBAH-->
<div class="modal fade" id="modal-ubah" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><i class="fa fa-pencil"></i> UBAH MENU</h4>
            </div>
            <div class="modal-body">
                <!-- /.box-header -->
                <form class="form-horizontal" id="form-ubah" onsubmit="return false">
                    <input type="hidden" name="menu_id">
                    <?php $this->load->view('backend/portal_menu/form_modal_index') ?>
                </form>
                <!-- /.box-body -->
            </div>
        </div>
    </div>
</div>