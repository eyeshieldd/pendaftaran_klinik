<script>
   jQuery(function(){ 
        Codebase.helpers(['datepicker','select2']); 
    });
   jQuery(document).ready(function() {
        var breakpointDefinition = {
            tablet: 1024,
            phone: 480
        };
        var responsiveHelper_dt_basic = [];

        $(".select2").select2({
            allowClear: true
        });
        /* Set DataTable */
        dtdokter = $("#tdokter").DataTable({
            "ajax": {
                "url": "<?=base_url('dokter/get_list_dokter');?>",
                "type": "POST"
            },
            // "sDom": dom_footer,
            "serverSide": true,
            "bFilter": false,
            "paging": true,
            "columns": [
                {"data": "no"},
                {"data": "nama_dokter"},
                {"data": "jadwal_periksa"},
                {"data": "jadwal_jam"},
                {"data": "aksi"}
           
            ]
        });

        // tombol simpan
        $('#tombol-simpan').click(function (){
            var modal = '#modal-add';
            var form = '#form-tambah';

            if (!$(form).isValid()) {
                $(form + ' #tombol-submit').attr('disabled', false);
                return;
            }

            $.ajax({
                url: "<?php echo base_url('contoh_satu/proses_tambah_data') ?>",
                type: "POST",
                data: $(form).serialize(),
                timeout: 5000,
                dataType: "JSON",
                success: function (data)
                {
                    if (data.status)
                    {
                        notif.success(data.pesan, "Berhasil");
                        $(modal).modal('hide');
                        $(form)[0].reset();
                        dtcontoh.ajax.reload(null, false);
                    } else {
                        notif.error(data.pesan,'Gagal');
                    }
                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                  alertajax.error(textStatus, jqXHR.status);
                }
            });
            $(form + ' #tombol-submit').attr('disabled', false);
        })

        // tombol simpan
        $('#tombol-ubah').click(function (){
            var modal = '#modal-ubah';
            var form = '#form-ubah';

            if (!$(form).isValid()) {
                $(form + ' #tombol-ubah').attr('disabled', false);
                return;
            }

            $.ajax({
                url: "<?php echo base_url('contoh_satu/proses_ubah_data') ?>",
                type: "POST",
                data: $(form).serialize(),
                timeout: 5000,
                dataType: "JSON",
                success: function (data)
                {
                    if (data.status)
                    {
                        notif.success(data.pesan, "Berhasil");
                        $(modal).modal('hide');
                        $(form)[0].reset();
                        dtcontoh.ajax.reload(null, false);
                    } else {
                        notif.error(data.pesan,'Gagal');
                    }
                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    alertajax.error(textStatus, jqXHR.status);
                }
            });
            $(form + ' #tombol-ubah').attr('disabled', false);
        })

        // tombol ubah
        $('#tcontoh').on('click', '.ubah-data', function () {
            /* var modal dan form */
            var modal = '#modal-ubah';
            var form = '#form-ubah';
            var data_id = $(this).attr('data-id');
            $.ajax({
                url: "<?= base_url('contoh_satu/get_detail_data') ?>",
                type: "POST",
                data: "data_id=" + data_id,
                timeout: 5000,
                dataType: "JSON",
                success: function (data)
                {
                    console.log(data)
                    if (data.status) /* if success close modal and reload ajax table */
                    {
                        $(form + ' [name="slider_id"]').val(data.data.slider_id);
                        $(form + ' [name="title"]').val(data.data.title);
                        $(form + ' [name="caption"]').val(data.data.caption);
                        $(form + ' [name="status"]').val(data.data.status);
                        $('#text-mdb').html(data.data.username);
                        $('#text-mdd').html(data.data.mdd);

                        $(modal).modal('show');
                    } else {
                        notif.error(data.pesan,'Gagal');
                    }
                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                       alertajax.error(textStatus, jqXHR.status);
                }
            })
        });

        // tombol ubah
        $('#tcontoh').on('click', '.hapus-data', function () {

            /* var modal dan form */
            var data_id = $(this).attr('data-id');
            $.confirm({
                title: 'Hapus data?',
                content: 'Apakah Anda yakin akan menghapus data ini?',
                type:'red',
                buttons: {
                    ya: {
                        btnClass: 'btn-red',
                        action:function () {
                            $.ajax({
                                type: 'post',
                                url: '<?php echo base_url('contoh_satu/proses_hapus_data'); ?>',
                                data: 'data_id=' + data_id,
                                dataType: 'JSON',
                                timeout: 5000,
                                success: function (data) {
                                    if (data.status) {
                                        notif.success(data.pesan, "Berhasil");
                                        dtcontoh.ajax.reload(null, false);
                                    } else {
                                        notif.error(data.pesan, "Gagal");
                                        dtcontoh.ajax.reload(null, false);
                                    }
                                },
                                error: function (jqXHR, textStatus, errorThrown) {
                                    dtcontoh.ajax.reload(null, false);
                                    alertajax.error(textStatus, jqXHR.status);
                                }
                            });
                        }
                    },
                    batal:function(){}
                }
            });
        });

        $('#modal-tambah').on('hidden.bs.modal', function(e) {
            $(this).find('#form-tambah')[0].reset();
        });

        $('#modal-ubah').on('hidden.bs.modal', function(e) {
            $(this).find('#form-ubah')[0].reset();
        });

          // $.validate({
          //   lang: 'id'
          // });

    })
</script>