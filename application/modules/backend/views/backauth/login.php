<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>CIRCLE LABS DEV TOOLS | Log in</title>
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <!-- Bootstrap 3.3.7 -->
        <link rel="stylesheet" href="<?= base_url('assets/backend/bootstrap/css/bootstrap.min.css') ?>">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="<?= base_url('assets/backend/font-awesome/css/font-awesome.min.css') ?>">
        <!-- Theme style -->
        <link rel="stylesheet" href="<?= base_url('assets/global/jquery-form-validator-net/form-validator/theme-default.min.css') ?>">
        <!-- Theme style -->
        <link rel="stylesheet" href="<?= base_url('assets/backend/css/AdminLTE.min.css') ?>">
    </head>
    <body class="hold-transition login-page">
        <div class="login-box">
            <div class="login-logo">
                <a href="javascript:void(0)"><b>CIRCLE</b>LABS</a>
            </div>
            <!-- /.login-logo -->
            <div class="login-box-body">
                <?php
                echo ($this->session->form_msg != '') ? '<div class="login-box-msg text-red no-padding">' . $this->session->form_msg . '</div>' : '';
                ?>
                <p class="login-box-msg text-red no-padding"></p>
                <p class="login-box-msg">Masukkan nama pengguna dan kata sandi.</p>
                <form action="<?= base_url('backend/backauth/do_login') ?>" method="post" id="form-login" onsubmit="return false">
                    <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                    <div class="form-group has-feedback">
                        <input type="text" name="username" required="required" class="form-control" placeholder="Pengguna" data-validation="required">
                        <span class="glyphicon glyphicon-user form-control-feedback"></span>
                    </div>
                    <div class="form-group has-feedback">
                        <input type="password" name="password" required="required"  class="form-control" placeholder="Kata Kunci" data-validation="required">
                        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                    </div>
                    <div class="row">
                        <div class="col-xs-8">
                        </div>
                        <!-- /.col -->
                        <div class="col-xs-4">
                            <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>
            </div>
            <!-- /.login-box-body -->
        </div>
        <!-- /.login-box -->
        <!-- jQuery 3 -->
        <script src="<?= base_url('assets/backend/jquery/dist/jquery.min.js') ?>"></script>
        <!-- Bootstrap 3.3.7 -->
        <script src="<?= base_url('assets/backend/bootstrap/js/bootstrap.min.js') ?>"></script>
        <script src="<?= base_url('assets/global/jquery-form-validator-net/form-validator/jquery.form-validator.min.js') ?>"></script>
        <script src="<?= base_url('assets/global/notify/notify.min.js') ?>"></script>
        <script>
                    $(document).ready(function () {

                        $.ajaxSetup({
                            headers: {<?= '"' . $this->security->get_csrf_token_name() . '":"' . $this->security->get_csrf_hash() . '"' ?>}
                        });

                        $('#txt-username').focus();
                        $('#form-login').on('submit', function () {
                            if (!$(this).isValid()) {
                                return false;
                            }
                            var form = '#form-login';
                            $.ajax({
                                type: 'post',
                                url: '<?= base_url('backend/backauth/do_auth'); ?>',
                                data: $(form).serialize(),
                                dataType: 'JSON',
                                timeout: 5000,
                                success: function (data) {
                                    if (data.status) {
                                        window.location.href = "<?= base_url('backend/depan'); ?>";
                                    } else {
                                        // gagal
                                        clear_form();
                                        $.notify(data.pesan, "error");
                                    }
                                },
                                error: function (data) {
                                    clear_form();
                                    if (typeof (variable) != "undefined" && variable !== null) {
                                        $('#txt-captcha').attr('src', 'assets/images/captcha/' + data.captcha.filename);
                                    }
                                    $.notify('Tidak dapat mengakses ke server', "error");
                                }
                            })
                        })

                        // validasi form
                        $.validate({
                            lang: 'id'
                        });

                        function clear_form() {
                            $('#form-login').find("input[name=password], input[name=captcha_text]").val("");
                        }
                        
                        $('#form-login [name="username"]').focus();
                    })
        </script>
    </body>
</html>
