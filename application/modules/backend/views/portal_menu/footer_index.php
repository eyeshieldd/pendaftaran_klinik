<script>
    $(document).ready(function () {
        /* Set DataTable */
        dtmenu = $("#tmenu").DataTable({
            "ajax": {
                "url": "<?= base_url('backend/portal_menu/get_list_menu'); ?>",
                "type": "POST",
                "data": function (d) {
                    d.portal_id = $('#form-pilih-portal').val()
                }
            },
            "bFilter": false,
            "paging": false,
            "columns": [
                {"data": "no"},
                {"data": "menu_name"},
                {"data": "menu_order", "className": "text-right"},
                {"data": "menu_position"},
                {"data": "menu_desc"},
                {"data": "menu_link"},
                {"data": "menu_show"},
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
                url: "<?php echo base_url('backend/portal_menu/proses_tambah') ?>",
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
                        dtmenu.ajax.reload(null, false);
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
                url: "<?php echo base_url('backend/portal_menu/proses_ubah') ?>",
                type: "POST",
                data: $(this).serialize(),
                timeout: 5000,
                dataType: "JSON",
                success: function (data)
                {
                    if (data.status) /* if success close modal and reload ajax table */
                    {
                        $.notify(data.pesan, "success");
                        dtmenu.ajax.reload(null, false);
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
        $('#tmenu').on('click', '.edit-menu', function () {
            /* var modal dan form */
            var modal = '#modal-ubah';
            var form = '#form-ubah';
            var data_id = $(this).attr('data-id');
            $.ajax({
                url: "<?= base_url('backend/portal_menu/get_detail_menu') ?>",
                type: "POST",
                data: "data_id=" + data_id,
                timeout: 5000,
                dataType: "JSON",
                success: function (data)
                {
                    if (data.status) /* if success close modal and reload ajax table */
                    {
                        $(form + ' [name="menu_id"]').val(data.data.menu_id);
                        $(form + ' [name="portal_id"]').val(data.data.portal_id);
                        $(form + ' [name="menu_name"]').val(data.data.menu_name);
                        $(form + ' [name="menu_desc"]').val(data.data.menu_desc);
                        $(form + ' [name="menu_portal"]').val(data.data.menu_portal);
                        $(form + ' [name="menu_position"]').val(data.data.menu_position);
                        $(form + ' [name="menu_order"]').val(data.data.menu_order);
                        $(form + ' [name="menu_link"]').val(data.data.menu_link);
                        $(form + ' [name="menu_icon"]').val(data.data.menu_icon);
                        $(form + ' [name="menu_fonticon"]').val(data.data.menu_fonticon);
                        if (data.data.menu_show == 1)
                            $(form + ' [name="menu_show"]').attr("checked", true);
                        else
                            $(form + ' [name="menu_show"]').attr("checked", false);
                        $('#text-mdd').html(data.data.mdd);
                        $('.pilihan-submenu-portal').empty();
                        $('.pilihan-submenu-portal').append('<option></option>');
                        var select_html = '';
                        $.each(data.menu, function (i, vmenu) {
                            if (vmenu.menu_id == data.data.menu_parent)
                                select_html += '<option value="' + vmenu.menu_id + '" selected="selected">' + vmenu.menu_name + '</option>';
                            else
                                select_html += '<option value="' + vmenu.menu_id + '">' + vmenu.menu_name + '</option>';
                        })
                        $('.pilihan-submenu-portal').append(select_html);
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
        $('#tmenu').on('click', '.hapus-menu', function () {
            /* var modal dan form */
            var menu_id = $(this).attr('data-id');
            bootbox.confirm('Apakah Anda yakin akan menghapus data ini?', function (konfirm) {
                if (konfirm) {
                    $.ajax({
                        type: 'post',
                        url: '<?php echo base_url('backend/portal_menu/hapus_menu'); ?>',
                        data: 'menu_id=' + menu_id,
                        dataType: 'JSON',
                        timeout: 5000,
                        success: function (data) {
                            if (data.status) {
                                $.notify(data.pesan, "success");
                                dtmenu.ajax.reload(null, false);
                            } else {
                                $.notify(data.pesan, "error");
                            }
                        },
                        error: function () {
                            dtmenu.ajax.reload(null, false);
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

        $('#modal-tambah').on('show.bs.modal', function () {
            $('#form-tambah [name="portal_id"]').val($('#form-pilih-portal').val());
            $('#form-tambah [name="menu_name"]').focus();
            get_menu_list($('#form-tambah [name="portal_id"]').val());
        });

        /* Reset Form after modal is hide */
        $('#modal-ubah').on('hide.bs.modal', function () {
            $('#form-ubah')[0].reset();
        });

        $('#form-modal-portal-id').change(function () {
            get_menu_list($('#form-modal-portal-id').val());
        })
        $('#form-pilih-portal').change(function () {
            dtmenu.ajax.reload(null, false);
        })

        function get_menu_list(portal_id = null) {
            if (portal_id == null)
                return;
            $.ajax({
                type: 'post',
                url: '<?php echo base_url('backend/portal_menu/list_menu_by_portal'); ?>',
                data: 'portal_id=' + portal_id,
                dataType: 'JSON',
                timeout: 5000,
                success: function (data) {
                    $('.pilihan-submenu-portal').empty();
                    $('.pilihan-submenu-portal').append('<option></option>');
                    var select_html = '';
                    $.each(data.rs_menu, function (i, vmenu) {
                        select_html += '<option value="' + vmenu.menu_id + '">' + vmenu.menu_name + '</option>';
                    })
                    $('.pilihan-submenu-portal').append(select_html);
                },
                error: function () {
                    bootbox.alert('Terjadi kesalahan saat menghubungkan ke server.');
                }
            });
        }

        $.validate({
            lang: 'id'
        });
    })
</script>