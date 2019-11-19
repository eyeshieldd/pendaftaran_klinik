<script>
    $(document).ready(function () {
        $('.select2').select2();
        /* Set DataTable */
        dtuser = $("#tuser").DataTable({
            "ajax": {
                "url": "<?= base_url('backend/user/get_list_user'); ?>",
                "type": "POST"
            },
            "bFilter": false,
            "paging": false,
            "columns": [
                {"data": "no"},
                {"data": "nama_lengkap"},
                {"data": "username"},
                {"data": "grup"},
                {"data": "dasar"},
                {"data": "status"},
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
                url: "<?php echo base_url('backend/user/proses_tambah') ?>",
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
                        dtuser.ajax.reload(null, false);
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
                url: "<?php echo base_url('backend/user/proses_ubah') ?>",
                type: "POST",
                data: $(this).serialize(),
                timeout: 5000,
                dataType: "JSON",
                success: function (data)
                {
                    if (data.status) /* if success close modal and reload ajax table */
                    {
                        $.notify(data.pesan, "success");
                        dtuser.ajax.reload(null, false);
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
        $('#tuser').on('click', '.edit-user', function () {
            /* var modal dan form */
            var modal = '#modal-ubah';
            var form = '#form-ubah';
            var data_id = $(this).attr('data-id');
            $.ajax({
                url: "<?= base_url('backend/user/get_detail_user') ?>",
                type: "POST",
                data: "data_id=" + data_id,
                timeout: 5000,
                dataType: "JSON",
                success: function (data)
                {
                    if (data.status) /* if success close modal and reload ajax table */
                    {
                        $(form + ' [name="user_id"]').val(data.data.user_id);
                        $(form + ' [name="nama_pengguna"]').val(data.data.username);
                        $(form + ' [name="nama_lengkap"]').val(data.data.nama_lengkap);
                        $(form + ' [name="telepon"]').val(data.data.telepon);
                        $(form + ' [name="email"]').val(data.data.email);
                        $(form + ' [data-elemen="grup_select"]').val(data.grup).trigger('change');

                        if (data.data.jenis_kelamin == "L")
                            $(form + ' [data-value="l"]').attr("checked", true);
                        else
                            $(form + ' [data-value="p"]').attr("checked", true);
                        if (data.data.status == 1)
                            $(form + ' [name="status"]').attr("checked", true);
                        else
                            $(form + ' [name="status"]').attr("checked", false);
                        $('.text-mdd').html(data.data.mdd);
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
        $('#tuser').on('click', '.hapus-user', function () {
            /* var modal dan form */
            var user_id = $(this).attr('data-id');
            bootbox.confirm('Apakah Anda yakin akan menghapus data ini?', function (konfirm) {
                if (konfirm) {
                    $.ajax({
                        type: 'post',
                        url: '<?php echo base_url('backend/user/hapus_user'); ?>',
                        data: 'user_id=' + user_id,
                        dataType: 'JSON',
                        timeout: 5000,
                        success: function (data) {
                            if (data.status) {
                                $.notify(data.pesan, "success");
                                dtuser.ajax.reload(null, false);
                            } else {
                                $.notify(data.pesan, "error");
                            }
                        },
                        error: function () {
                            dtuser.ajax.reload(null, false);
                            bootbox.alert('Terjadi kesalahan saat menghubungkan ke server.');
                        }
                    });
                }
            });
        });

        // hapus data
        $('#tuser').on('change', '.pilih-grup', function () {
            /* var modal dan form */
            var user_id = $(this).attr('data-id');
            var grup_id = $(this).val();
            bootbox.confirm('Apakah Anda yakin akan mengubah data ini?', function (konfirm) {
                if (konfirm) {
                    $.ajax({
                        type: 'post',
                        url: '<?php echo base_url('backend/user/ubah_grup_dasar'); ?>',
                        data: 'user_id=' + user_id + '&grup_id=' + grup_id,
                        dataType: 'JSON',
                        timeout: 5000,
                        success: function (data) {
                            if (data.status) {
                                $.notify(data.pesan, "success");
                                dtuser.ajax.reload(null, false);
                            } else {
                                $.notify(data.pesan, "error");
                            }
                        },
                        error: function () {
                            dtuser.ajax.reload(null, false);
                            bootbox.alert('Terjadi kesalahan saat menghubungkan ke server.');
                        }
                    });
                } else {
                    dtuser.ajax.reload(null, false);
                }
            });
        });

        /* Reset Form after modal is hide */
        $('#modal-tambah').on('hidden.bs.modal', function () {
            $('#form-tambah')[0].reset();
        });
        $('#modal-tambah').on('shown.bs.modal', function () {
            $('#form-tambah [name="kata_sandi"]').attr("data-validation", "required");
            $('#form-tambah [name="ulangi_kata_sandi"]').attr("data-validation", "required confirmation");
            $('#form-tambah [name="ulangi_kata_sandi"]').attr("data-validation-confirm", "kata_sandi");
            $('#form-tambah [name="ulangi_kata_sandi"]').attr("data-validation-depends-on", "kata_sandi");
            $('#form-tambah [name="nama_lengkap"]').focus();
        });

        /* Reset Form after modal is hide */
        $('#modal-ubah').on('shown.bs.modal', function () {
            $('#form-ubah [name="kata_sandi"]').attr("data-validation", "");
        });
        /* Reset Form after modal is hide */
        $('#modal-ubah').on('hidden.bs.modal', function () {
            $('#form-ubah')[0].reset();
        });

        $.validate({
            lang: 'id',
            modules: 'security, logic'
        });
    })
</script>