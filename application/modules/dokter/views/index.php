<div class="block">
    <div class="block-content block-content-full">
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                    <div class="row">
                        <div class="col-md-10">
                            <form class="form-inline" action="" method="post" onsubmit="return false;">
                                <label class="sr-only" for="example-if-email">Nama Dokter</label>
                                <input type="email" class="form-control form-control-lg mb-2 mr-sm-2 mb-sm-0" id="example-if-email" name="nama_barang" placeholder="Nama Dokter">
                                <label class="sr-only" for="example-if-password">Spesialis</label>
                                <input type="password" class="form-control form-control-lg mb-2 mr-sm-2 mb-sm-0" id="example-if-password" name="kategori" placeholder="Spesialis">
                                <button type="submit" class="btn btn-primary btn-lg">Cari</button>
                            </form>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-primary waves-effect waves-light pull-right btn-lg" data-toggle="modal" data-target="#modal-tambah" type="button"><span class="btn-label"><i class="fa fa-plus"></i></span>Tambah Data</button>
                        </div>
                    </div>
                        <hr>
                    <div class="row">
                        <div class="col-sm-12">
                            <table id="tdokter" class="table table-bordered table-striped table-vcenter js-dataTable-full dataTable no-footer" width="100%">
                                <thead>
                                    <tr>
                                        <th width="5%">No.</th>
                                        <th>Nama Dokter</th>
                                        <th>Jadwal Periksa</th>
                                        <th>Jadwal Jam</th>
                                        <th>spesialis</th>
                                        <th>Aksi</th>
                                     
                                 
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- sample modal content -->
<div id="modal-tambah" data-backdrop="static" class="modal fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="block block-themed block-transparent mb-0">
                <div class="block-header bg-primary-dark">
                    <h3 class="block-title">Tambah Data Dokter</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                            <i class="si si-close"></i>
                        </button>
                    </div>
                </div>
                <div class="block-content">
                    <form class="form-horizontal" id="form-tambah">
                    <?php $this->load->view('_form')?>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <button type="button" id="tombol-simpan" class="btn btn-primary" >
                        <i class="fa fa-save"></i> Save
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- sample modal content -->
<div id="modal-ubah" data-backdrop="static" class="modal fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="block block-themed block-transparent mb-0">
                <div class="block-header bg-primary-dark">
                    <h3 class="block-title">Ubah Data</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                            <i class="si si-close"></i>
                        </button>
                    </div>
                </div>
                <div class="block-content">
                    <form class="form-horizontal" id="form-ubah">
                        <input type="hidden" name="id" value="">
                        <?php $this->load->view('_form')?>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" data-dismiss="modal">
                        <i class="fa fa-save"></i> Save
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
