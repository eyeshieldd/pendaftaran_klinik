<script>
    $(document).ready(function () {
        /* Set DataTable */
        dtgroup = $("#tgroup").DataTable({
            "ajax": {
                "url": "<?= base_url('backend/group/get_list_group'); ?>",
                "type": "POST"
            },
            "bFilter": false,
            "ordering": true,
            "paging": false,
            "columns": [
                {"data": "no", "orderable": false},
                {"data": "group_name"},
                {"data": "group_desc"},
                {"data": "group_portal"},
                {"data": "group_restricted"},
                {"data": "aksi", "orderable": false}
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
                url: "<?php echo base_url('backend/group/proses_tambah') ?>",
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
                        dtgroup.ajax.reload(null, false);
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
                url: "<?php echo base_url('backend/group/proses_ubah') ?>",
                type: "POST",
                data: $(this).serialize(),
                timeout: 5000,
                dataType: "JSON",
                success: function (data)
                {
                    if (data.status) /* if success close modal and reload ajax table */
                    {
                        $.notify(data.pesan, "success");
                        dtgroup.ajax.reload(null, false);
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
        $('#tgroup').on('click', '.edit-group', function () {
            /* var modal dan form */
            var modal = '#modal-ubah';
            var form = '#form-ubah';
            var data_id = $(this).attr('data-id');
            $.ajax({
                url: "<?= base_url('backend/group/get_detail_group') ?>",
                type: "POST",
                data: "data_id=" + data_id,
                timeout: 5000,
                dataType: "JSON",
                success: function (data)
                {
                    if (data.status) /* if success close modal and reload ajax table */
                    {
                        $(form + ' [name="group_id"]').val(data.data.group_id);
                        $(form + ' [name="group_name"]').val(data.data.group_name);
                        $(form + ' [name="group_desc"]').val(data.data.group_desc);
                        $(form + ' [name="group_portal"]').val(data.data.group_portal);
                        if (data.data.group_restricted == 1)
                            $(form + ' [name="group_restricted"]').attr("checked", true);
                        else
                            $(form + ' [name="group_restricted"]').attr("checked", false);
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
        $('#tgroup').on('click', '.hapus-group', function () {
            /* var modal dan form */
            var group_id = $(this).attr('data-id');
            bootbox.confirm('Apakah Anda yakin akan menghapus data ini?', function (konfirm) {
                if (konfirm) {
                    $.ajax({
                        type: 'post',
                        url: '<?php echo base_url('backend/group/hapus_group'); ?>',
                        data: 'group_id=' + group_id,
                        dataType: 'JSON',
                        timeout: 5000,
                        success: function (data) {
                            if (data.status) {
                                $.notify(data.pesan, "success");
                                dtgroup.ajax.reload(null, false);
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
            $('#form-tambah [name="group_name"]').focus();
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