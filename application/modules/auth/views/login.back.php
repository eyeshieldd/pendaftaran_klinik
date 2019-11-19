<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta content="IE=edge" http-equiv="X-UA-Compatible">
        <meta content="width=device-width, initial-scale=1" name="viewport">
        <meta content="" name="description">
        <meta content="" name="author">
        <link href="<?=base_url('assets/operator/plugins/images/favicon.png')?>" rel="icon" sizes="16x16" type="image/png">
        <title>
            Circle Labs Core with Ample Admin Template
        </title>
        <!-- Bootstrap Core CSS -->
        <link href="<?=base_url('assets/operator/bootstrap/dist/css/bootstrap.min.css')?>" rel="stylesheet">
        <!-- animation CSS -->
        <link href="<?=base_url('assets/operator/css/animate.css')?>" rel="stylesheet">
        <!-- Custom CSS -->
	    <link href="<?=base_url('assets/operator/css/style.css')?>" rel="stylesheet">
	    <link rel="stylesheet" type="text/css" media="screen" href="<?php echo base_url('assets/operator/plugins/bower_components/toast-master/css/jquery.toast.css'); ?>"/>
        <!-- color CSS -->
	    <link href="<?=base_url('assets/operator/css/colors/default.css')?>" id="theme" rel="stylesheet">
                                                <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
                                                <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
                                                <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
    </head>
    <body>
        <!-- Preloader -->
        <div class="preloader">
            <div class="cssload-speeding-wheel">
            </div>
        </div>
        <section class="new-login-register" id="wrapper">
            <div class="lg-info-panel">
                <div class="inner-panel">
                    <a class="p-20 di" href="javascript:void(0)">
                        <img src="<?=base_url('assets/operator')?>/plugins/images/admin-logo.png"/>
                    </a>
                    <div class="lg-content">
                        <h2>
                            THE ULTIMATE & MULTIPURPOSE ADMIN TEMPLATE OF 2017
                        </h2>
                        <p class="text-muted">
                            with this admin you can get 2000+ pages, 500+ ui component, 2000+ icons, different demos and many more...
                        </p>
                        <a class="btn btn-rounded btn-danger p-l-20 p-r-20" href="#">
                            Buy now
                        </a>
                    </div>
                </div>
            </div>
            <div class="new-login-box">
                <div class="white-box">
                    <h3 class="box-title m-b-0">
                        Sign In to Admin
                    </h3>
                    <small>
                        Enter your details below
                    </small>
                    <form class="form-horizontal new-lg-form" action="<?php echo base_url('auth/do_login') ?>" id="login-form" onsubmit="return false" method="post">
                    	<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>"/>
                        <div class="form-group m-t-20">
                            <div class="col-xs-12">
                                <label>
                                    Username
                                </label>
                                <input class="form-control" placeholder="Username" name="username" required="" type="text">
                                </input>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-xs-12">
                                <label>
                                    Password
                                </label>
                                <input class="form-control" placeholder="Password" required="" name="password" type="password">
                                </input>
                            </div>
                        </div>
                        <div class="form-group">
                          <div class="col-md-12">
                            <div class="checkbox checkbox-info pull-left p-t-0">
                            </div>
                            <a href="<?=base_url('lupa_password')?>" id="to-recover" class="text-dark pull-right"><i class="fa fa-lock m-r-5"></i> Forgot password?</a> </div>
                        </div>
                        <div class="form-group text-center m-t-20">
                            <div class="col-xs-12">
                                <button class="btn btn-info btn-lg btn-block btn-rounded text-uppercase waves-effect waves-light" type="submit" id="tombol-do-login">
                                    Log In
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </section>
        <!-- jQuery -->
        <script src="<?=base_url('assets/operator/plugins/bower_components/jquery/dist/jquery.min.js')?>">
        </script>
        <!-- Bootstrap Core JavaScript -->
        <script src="<?=base_url('assets/operator/bootstrap/dist/js/bootstrap.min.js')?>">
        </script>
        <!-- Menu Plugin JavaScript -->
        <script src="<?=base_url('assets/operator/plugins/bower_components/sidebar-nav/dist/sidebar-nav.min.js')?>">
        </script>
        <!--slimscroll JavaScript -->
        <script src="<?=base_url('assets/operator/js/jquery.slimscroll.js')?>">
        </script>
        <!--Wave Effects -->
        <script src="<?=base_url('assets/operator/js/waves.js')?>">
        </script>
        <script type="text/javascript" src="<?php echo base_url('assets/global/jquery-form-validator-net/form-validator/jquery.form-validator.min.js') ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('assets/operator/plugins/bower_components/toast-master/js/jquery.toast.js') ?>"></script>
        <!-- Custom Theme JavaScript -->
        <script src="<?=base_url('assets/operator/js/custom.min.js')?>">
        </script>
        <!--Style Switcher -->
        <script src="<?=base_url('assets/operator/plugins/bower_components/styleswitcher/jQuery.style.switcher.js')?>">
        </script>
        <script type="text/javascript">
        $(function() {
            $(".preloader").fadeOut();
        });
        $(function() {
            $('[data-toggle="tooltip"]').tooltip()
        });
        // ==============================================================
        // Login and Recover Password
        // ==============================================================
        $('#to-recover').on("click", function() {
            $("#loginform").slideUp();
            $("#recoverform").fadeIn();
        });

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
    </script>
    <script>
    $(document).ready(function() {
        $('#txt-username').focus();
        $('#tombol-do-login').on('click', function() {
            if (!$(this).isValid()) {
                return false;
            }
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

        // validasi form
        $.validate({
            lang: 'id'
        });

    })

    function clear_form() {
        $('#login-form').find("input[name=password], input[name=captcha_text]").val("");
    }
</script>
    </body>
</html>
