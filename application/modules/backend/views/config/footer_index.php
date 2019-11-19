<script>
    $(document).ready(function () {
        /* Set DataTable */
        dtconfig = $("#tconfig").DataTable({
            "ajax": {
                "url": "<?= base_url('backend/config/get_list_config'); ?>",
                "type": "POST"
            },
            "bFilter": false,
            "pagination":false,
            "columns": [
                {"data": "no"},
                {"data": "config_name"},
                {"data": "config_group"},
                {"data": "config_value"},
                {"data": "config_desc"},
                {"data": "config_portal"},
                {"data": "restricted","className":"text-center"},
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
                url: "<?php echo base_url('backend/config/proses_tambah') ?>",
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
                        dtconfig.ajax.reload(null, false);
                    } else {
                        bootbox.alert(data.pesan);
                    }
                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    bootbox.alert('Terjadi kesalahan saat menghubungkan ke server. Error : ' + textStatus);
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
                url: "<?php echo base_url('backend/config/proses_ubah') ?>",
                type: "POST",
                data: $(this).serialize(),
                timeout: 5000,
                dataType: "JSON",
                success: function (data)
                {
                    if (data.status) /* if success close modal and reload ajax table */
                    {
                        $.notify(data.pesan, "success");
                        dtconfig.ajax.reload(null, false);
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
        $('#tconfig').on('click', '.edit-config', function () {
            /* var modal dan form */
            var modal = '#modal-ubah';
            var form = '#form-ubah';
            var data_id = $(this).attr('data-id');
            $.ajax({
                url: "<?= base_url('backend/config/get_detail_config') ?>",
                type: "POST",
                data: "data_id=" + data_id,
                timeout: 5000,
                dataType: "JSON",
                success: function (data)
                {
                    if (data.status) /* if success close modal and reload ajax table */
                    {
                        $(form + ' [name="config_id"]').val(data.data.config_id);
                        $(form + ' [name="config_name"]').val(data.data.config_name);
                        $(form + ' [name="config_group"]').val(data.data.config_group);
                        $(form + ' [name="config_desc"]').val(data.data.config_desc);
                        $(form + ' [name="config_value"]').val(data.data.config_value);
                        $(form + ' [name="config_portal_id"]').val(data.data.config_portal_id);
                        if (data.data.restricted == 1)
                            $(form + ' [name="restricted"]').attr("checked", true);
                        else
                            $(form + ' [name="restricted"]').attr("checked", false);
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
        $('#tconfig').on('click', '.hapus-config', function () {
            /* var modal dan form */
            var config_id = $(this).attr('data-id');
            bootbox.confirm('Apakah Anda yakin akan menghapus data ini?', function (konfirm) {
                if (konfirm) {
                    $.ajax({
                        type: 'post',
                        url: '<?php echo base_url('backend/config/hapus_config'); ?>',
                        data: 'config_id=' + config_id,
                        dataType: 'JSON',
                        timeout: 5000,
                        success: function (data) {
                            if (data.status) {
                                $.notify(data.pesan, "success");
                                dtconfig.ajax.reload(null, false);
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
            $('#form-tambah [name="config_name"]').focus();
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