<script>
    function cek_all_selected() {
        console.log('as');
    }

    function cek_sub_all(id = null) {
        if (id != null) {
            if ($(".ch" + id + ":checked").length == $(".ch" + id).length)
                $(".ca" + id).prop('checked', true);
            else
                $(".ca" + id).prop('checked', false);
        }
        if ($(".checked-all:checked").length == $(".checked-all").length)
            $(".checkall-header").prop('checked', true);
        else
            $(".checkall-header").prop('checked', false);
    }


    $(document).ready(function () {
        /* Set DataTable */
        dtmenu = $("#tmenu").DataTable({
            "ajax": {
                "url": "<?= base_url('backend/permission/get_list_menu_permission'); ?>",
                "type": "POST",
                "data": function (d) {
                    d.portal_id = $('#form-pilih-portal').val(),
                            d.group_id = $('#form-pilih-group').val()
                }
            },
            "drawCallback": function () {
                var count = $('#tmenu tr').length - 1;
                if (count > 1)
                    cek_sub_all();
                else
                    $(".checkall-header").prop('checked', false);
            },
            "bFilter": false,
            "paging": false,
            "columns": [
                {"data": "no"},
                {"data": "checkpermission"},
                {"data": "menu_name"},
                {"data": "portal"},
                {"data": "user"},
                {"data": "read"},
                {"data": "create"},
                {"data": "update"},
                {"data": "delete"}
            ]
        });
        $("#tmenu").on("click", ".checked-all", function () {
            var status = $(this).prop("checked");
            if (status) {
                $(".ch" + $(this).val()).prop('checked', true);
            } else {
                $(".ch" + $(this).val()).prop('checked', false);
            }
        })

        $("#tmenu").on("click", ".checkall-header", function () {
            var status = $(this).prop("checked");
            if (status) {
                $("#form-permission [type='checkbox']").prop('checked', true);
            } else {
                $("#form-permission [type='checkbox']").prop('checked', false);
            }
        })

        /* Form Tambah Action */
        $('#tombol-simpan').on("click", function () {

            if ($("#form-pilih-portal").val() == "" || $("#form-pilih-group").val() == "") {
                $.notify("Silakan pilih portal dan group user terlebih dahulu.", "error");
                return;
            }
            /* var modal dan form */
            var form = '#form-permission';
            $.ajax({
                url: "<?php echo base_url('backend/permission/proses_set_permission') ?>",
                type: "POST",
                data: $(form).serialize(),
                timeout: 5000,
                dataType: "JSON",
                success: function (data)
                {
                    if (data.status) /* if success close modal and reload ajax table */
                    {
                        $.notify(data.pesan, "success");
                        dtmenu.ajax.reload(null, false);
                    } else {
                        bootbox.alert(data.pesan);
                    }
                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    bootbox.alert('Terjadi kesalahan saat menghubungkan ke server.');
                }
            });
        });
        $("#tombol-cari").on("click", function () {
            $('#form-permission [name="portal_id"]').val($('#form-pilih-portal').val());
            $('#form-permission [name="group_id"]').val($('#form-pilih-group').val());
            dtmenu.ajax.reload(null, false);
        })

        $.validate({
            lang: 'id'
        });
    })
</script>