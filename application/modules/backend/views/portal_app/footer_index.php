<script>
    $(document).ready(function () {
        /* Set DataTable */
        dtportal = $("#tportal").DataTable({
            "ajax": {
                "url": "<?= base_url('backend/portal_app/get_list_portal'); ?>",
                "type": "POST",
                "headers": {'HTTP_X_XSRF_TOKEN': "<?= $this->security->get_csrf_hash() ?>"}
            },
            "bFilter": false,
            "paging": false,
            "columns": [
                {"data": "no"},
                {"data": "portal_name"},
                {"data": "portal_number", "className": "text-center"},
                {"data": "portal_desc"},
                {"data": "portal_link"},
                {"data": "aksi"}
            ]
        });

        /* Form Tambah Action */
        $('#form-tambah').submit(function () {
            /* var modal dan form */
            var modal = '#modal-tambah';
            var form = '#form-tambah';

            if (!$(form).isValid()) {
                $(form + ' #tombol-simpan').html('<i class="fa fa-save"></i> Simpan');
                $(form + ' #tombol-simpan').attr('disabled', false);
                return;
            }
            $(form + ' #tombol-simpan').text('Menyimpan...');
            $(form + ' #tombol-simpan').attr('disabled', true);

            $.ajax({
                url: "<?php echo base_url('backend/portal_app/proses_tambah') ?>",
                type: "POST",
                data: $(this).serialize(),
                timeout: 5000,
                dataType: "JSON",
                success: function (data)
                {
                    if (data.status) /* if success close modal and reload ajax table */
                    {
                        $.notify(data.pesan, "success");
                        $(modal).modal('hide');
                        $(form)[0].reset();
                        dtportal.ajax.reload(null, false);
                    } else {
                        bootbox.alert(data.pesan);
                    }
                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    bootbox.alert('Terjadi kesalahan saat menghubungkan ke server.');
                }
            });
            $(form + ' #tombol-simpan').html('<i class="fa fa-save"></i> Simpan');
            $(form + ' #tombol-simpan').attr('disabled', false);
        });
        /* Form Edit Item Action */
        $('#form-ubah').submit(function () {
            /* var modal dan form */
            var form = '#form-ubah';
            var modal = '#modal-ubah';
            if (!$(form).isValid(null, null, false)) {
                $(form + ' #btn-simpan').html('<i class="fa fa-save"></i> Simpan'); /* change button text */
                $(form + ' #btn-simpan').attr('disabled', false); /* set button enable */
                return;
            }
            $(form + ' #btn-simpan').text('Menyimpan...');
            $(form + ' #btn-simpan').attr('disabled', true);
            $.ajax({
                url: "<?php echo base_url('backend/portal_app/proses_ubah') ?>",
                type: "POST",
                data: $(this).serialize(),
                timeout: 5000,
                dataType: "JSON",
                success: function (data)
                {
                    if (data.status) /* if success close modal and reload ajax table */
                    {
                        $.notify(data.pesan, "success");
                        dtportal.ajax.reload(null, false);
                        $(modal).modal('hide');
                    } else {
                        $.notify(data.pesan, "error");
                    }
                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    bootbox.alert('Terjadi kesalahan saat menghubungkan ke server.' + textStatus);
                }
            });
        });
        /* Button Detail Action */
        $('#tportal').on('click', '.edit-portal', function () {
            /* var modal dan form */
            var modal = '#modal-ubah';
            var form = '#form-ubah';
            var data_id = $(this).attr('data-id');
            $.ajax({
                url: "<?= base_url('backend/portal_app/get_detail_portal') ?>",
                type: "POST",
                data: "data_id=" + data_id,
                timeout: 5000,
                dataType: "JSON",
                success: function (data)
                {
                    if (data.status) /* if success close modal and reload ajax table */
                    {
                        $(form + ' [name="portal_id"]').val(data.data.portal_id);
                        $(form + ' [name="portal_name"]').val(data.data.portal_name);
                        $(form + ' [name="portal_number"]').val(data.data.portal_number);
                        $(form + ' [name="portal_desc"]').val(data.data.portal_desc);
                        $(form + ' [name="portal_link"]').val(data.data.portal_link);
                        $(form + ' [name="portal_title"]').val(data.data.meta_title);
                        $(form + ' [name="portal_tag"]').val(data.data.meta_tag);
                        $(form + ' [name="portal_meta_desc"]').val(data.data.meta_desc);
                        $('#text-mdb').html(data.data.username);
                        $('#text-mdd').html(data.data.mdd);
                        $(modal).modal('show');
                    } else {
                        bootbox.alert('Terjadi kesalahan pada saat pengambilan data.<br/>' + data.pesan);
                    }
                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    bootbox.alert('Terjadi kesalahan saat menghubungkan ke server.');
                }
            })
        });

        // hapus data
        $('#tportal').on('click', '.hapus-portal', function () {
            /* var modal dan form */
            var portal_id = $(this).attr('data-id');
            bootbox.confirm('Apakah Anda yakin akan menghapus data ini?', function (konfirm) {
                if (konfirm) {
                    $.ajax({
                        type: 'post',
                        url: '<?php echo base_url('backend/portal_app/hapus_portal'); ?>',
                        data: 'portal_id=' + portal_id,
                        dataType: 'JSON',
                        timeout: 5000,
                        success: function (data) {
                            if (data.status) {
                                $.notify(data.pesan, "success");
                                dtportal.ajax.reload(null, false);
                            } else {
                                $.notify(data.pesan, "error");
                            }
                        },
                        error: function () {
                            dataTableVar.ajax.reload(null, false);
                            bootbox.alert('Terjadi kesalahan saat menghubungkan ke server.');
                        }
                    });
                }
            });
        });

        /* Reset Form after modal is hide */
        $('#modal-tambah').on('hidden.bs.modal', function () {
            $('#form-tambah')[0].reset();
        });
        $('#modal-tambah').on('shown.bs.modal', function () {
            $('#form-tambah [name="portal_name"]').focus();
        });

        /* Reset Form after modal is hide */
        $('#modal-ubah').on('hidden.bs.modal', function () {
            $('#form-ubah')[0].reset();
        });

        $.validate({
            lang: 'id'
        });
    })
</script>