<!-- Main content -->
<section class="content">
    <!-- Default box -->
    <div class="box">
        <div class="box">
            <!-- /.box-header -->
            <div class="box-body">
                <div class="row">
                    <div class="col-lg-10"></div>
                    <div class="col-lg-2"><button class="btn btn-primary btn-sm pull-right" data-toggle="modal" data-target="#modal-tambah"><i class="fa fa-plus"></i> GROUP BARU</button></div>
                </div>
                <table id="tgroup" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th width="5%">No.</th>
                            <th width="22%">Nama Group</th>
                            <th width="25%">Deskripsi</th>
                            <th width="25%">Portal Awal (link)</th>
                            <th width="8%">Terbatas</th>
                            <th width="15%"Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
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
                <h4 class="modal-title"><i class="fa fa-plus-circle"></i> TAMBAH GROUP</h4>
            </div>
            <div class="modal-body">
                <!-- /.box-header -->
                <form class="form-horizontal" id="form-tambah" onsubmit="return false">
                    <?php $this->load->view('backend/group/form_modal_index') ?>
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
                <h4 class="modal-title"><i class="fa fa-pencil"></i> UBAH GROUP</h4>
            </div>
            <div class="modal-body">
                <!-- /.box-header -->
                <form class="form-horizontal" id="form-ubah" onsubmit="return false">
                    <input type="hidden" name="group_id">
                    <?php $this->load->view('backend/group/form_modal_index') ?>
                </form>
                <!-- /.box-body -->
            </div>
        </div>
    </div>
</div>