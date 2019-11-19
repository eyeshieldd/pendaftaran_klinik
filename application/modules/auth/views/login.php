<!doctype html>
<html lang="en" class="no-focus">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">

        <title>CircleLabs with Codebase &amp; UI Framework</title>

        <meta name="description" content="Codebase - Bootstrap 4 Admin Template &amp; UI Framework created by pixelcave and published on Themeforest">
        <meta name="author" content="pixelcave">
        <meta name="robots" content="noindex, nofollow">

        <!-- Open Graph Meta -->
        <meta property="og:title" content="">
        <meta property="og:site_name" content="Codebase">
        <meta property="og:description" content="">
        <meta property="og:type" content="website">
        <meta property="og:url" content="">
        <meta property="og:image" content="">

        <!-- Icons -->
        <!-- The following icons can be replaced with your own, they are used by desktop and mobile browsers -->
        <link rel="shortcut icon" href="assets/media/favicons/favicon.png">
        <link rel="icon" type="image/png" sizes="192x192" href="assets/media/favicons/favicon-192x192.png">
        <link rel="apple-touch-icon" sizes="180x180" href="assets/media/favicons/apple-touch-icon-180x180.png">
        <!-- END Icons -->
        <!-- Fonts and Codebase framework -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Muli:300,400,400i,600,700">
        <link rel="stylesheet" id="css-main" href="<?=base_url('assets/operator/css/codebase.min.css')?>">
        <link rel="stylesheet" id="css-main" href="<?=base_url('assets/operator/js/plugins/toast-master/css/jquery.toast.css')?>">
    </head>
    <body>
        <div id="page-container" class="main-content-boxed">
            <!-- Main Container -->
            <main id="main-container">
                <!-- Page Content -->
                <div class="bg-body-dark bg-pattern" style="">
                    <div class="row mx-0 justify-content-center">
                        <div class="hero-static col-lg-6 col-xl-4">
                            <div class="content content-full overflow-hidden">
                                <!-- Header -->
                                <div class="py-30 text-center">
                                    <a class="link-effect font-w700" href="index.html">
                                        <i class="si si-fire"></i>
                                        <span class="font-size-xl text-primary-dark">Circle</span><span class="font-size-xl">Labs</span>
                                    </a>
                                </div>
                                <form class="js-validation-signin" action="<?php echo base_url('auth/do_login') ?>" id="login-form" onsubmit="return false" method="post">
                                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>"/>
                                    <div class="block block-themed block-rounded block-shadow">
                                        <div class="block-header bg-gd-dusk">
                                            <h3 class="block-title">Please Sign In</h3>
                                            <div class="block-options">
                                                <button type="button" class="btn-block-option">
                                                    <i class="si si-wrench"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="block-content">
                                            <div class="form-group row">
                                                <div class="col-12">
                                                    <label for="login-username">Username</label>
                                                    <input class="form-control" placeholder="Username" name="username" required="" type="text">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-12">
                                                    <label for="login-password">Password</label>
                                                    <input class="form-control" placeholder="Password" required="" name="password" type="password">
                                                </div>
                                            </div>
                                            <div class="form-group row mb-0">
                                                <div class="col-sm-6 d-sm-flex align-items-center push">
                                                </div>
                                                <div class="col-sm-6 text-sm-right push">
                                                    <button type="submit" class="btn btn-alt-primary" id="tombol-do-login">
                                                        <i class="si si-login mr-10"></i> Sign In
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                <!-- END Sign In Form -->
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END Page Content -->
            </main>
            <!-- END Main Container -->
        </div>
        <!-- END Page Container -->
        <script src="<?=base_url('assets/operator/js/core/jquery.min.js')?>"></script>
        <script src="<?=base_url('assets/operator/js/codebase.core.min.js')?>"></script>
        <script src="<?=base_url('assets/operator/js/codebase.app.min.js')?>"></script>
        <!-- Page JS Plugins -->
        <script src="<?=base_url('assets/operator/js/plugins/jquery-validation/jquery.validate.min.js')?>"></script>
        <script src="<?=base_url('assets/operator/js/plugins/toast-master/js/jquery.toast.js')?>"></script>
        <!-- Page JS Code -->
        <script src="<?=base_url('assets/operator/js/pages/op_auth_signin.min.js')?>"></script>
        <script>
            var notif = {
                success : function(message = "default text", header = "default header"){
                    $.toast({
                        heading: header,
                        text: message,
                        position: 'top-right',
                        loaderBg:'#06d79c',
                        icon: 'info',
                        loader: false,
                        hideAfter: 3000,
                        stack: 6
                      });
                },
                info : function(message = "default text", header = "default header"){
                    $.toast({
                        heading: header,
                        text: message,
                        position: 'top-right',
                        loaderBg:'#ff6849',
                        icon: 'info',
                        loader: false,
                        hideAfter: 3000,
                        stack: 6
                      });
                },
                warning : function(message = "default text", header = "default header"){
                    $.toast({
                        heading: header,
                        text: message,
                        position: 'top-right',
                        loaderBg:'#ff6849',
                        icon: 'warning',
                        loader: false,
                        hideAfter: 3000,
                        stack: 6
                      });
                },
                error : function(message = "default error text", header = "default error header"){
                    $.toast({
                        heading: header,
                        text: message,
                        position: 'top-right',
                        loaderBg:'#ef5350',
                        icon: 'error',
                        loader: false,
                        hideAfter: 3000,
                        stack: 6
                      });
                }
            }
            $(document).ready(function() {
                $('#txt-username').focus();
                $('#tombol-do-login').on('click', function() {
                    var form = '#login-form';
                    $.ajax({
                        type: 'post',
                        url: '<?php echo base_url('auth/do_login'); ?>',
                        data: $(form).serialize(),
                        dataType: 'JSON',
                        timeout: 5000,
                        success: function(data) {
                            if (data.status) {
                                window.location.href = data.portal;
                            } else {
                                // gagal
                                $('#txt-captcha').attr('src', 'assets/images/captcha/' + data.captcha.filename);
                                clear_form();
                                 $('#txt-password').focus();
                                notif.error(data.pesan,'error');
                            }
                        },
                        error: function(data) {
                            clear_form();
                            if (typeof (variable) != "undefined" && variable !== null) {
                                $('#txt-captcha').attr('src', 'assets/images/captcha/' + data.captcha.filename);
                            }
                            notif.error('terjadi kesalahan saat menghubungkan ke server','error');
                        }
                    })
                })

            })

            function clear_form() {
                $('#login-form').find("input[name=password], input[name=captcha_text]").val("");
            }
        </script>
    </body>
</html>