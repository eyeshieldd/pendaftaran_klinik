<script>
    $(document).ready(function () {
        $('#txt-username').focus();
        $('#form-login').on('submit', function () {
            if (!$(this).isValid()) {
                return false;
            }
            var form = '#form-login';
            $.ajax({
                type: 'post',
                url: '<?php echo base_url('backend/backauth/do_auth'); ?>',
                data: $(form).serialize(),
                dataType: 'JSON',
                timeout: 5000,
                success: function (data) {
                    if (data.status) {
                        window.location.href = data.portal;
                    } else {
                        // gagal
//                        $('#txt-captcha').attr('src', 'assets/images/captcha/' + data.captcha.filename);
                        clear_form();
                        show_alert('Kesalahan', data.pesan, data.type);
                    }
                },
                error: function (data) {
                    clear_form();
                    if (typeof (variable) != "undefined" && variable !== null) {
                        $('#txt-captcha').attr('src', 'assets/images/captcha/' + data.captcha.filename);
                    }
                    show_alert('Kesalahan', 'Tidak dapat mengakses ke server', 'error');
                }
            })
        })

        // validasi form
        $.validate({
            lang: 'id'
        });

    })

    // alert
    function show_alert(title, content, type = 'info', time = 4000) {
        var warna;
        if (type == 'error')
            warna = '#C46A69';
        else if (type == 'info')
            warna = "#5384AF";
        $.smallBox({
            title: title,
            content: content,
            color: warna,
            timeout: time,
            icon: "fa fa-bell"
        });
    }

    function clear_form() {
        $('#form-login').find("input[name=password], input[name=captcha_text]").val("");
    }
</script>