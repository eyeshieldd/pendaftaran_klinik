<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>CIRCLELABS | DEV CONFIG</title>
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <!-- Bootstrap 3.3.7 -->
        <link rel="stylesheet" href="<?= base_url('assets/backend/bootstrap/css/bootstrap.min.css') ?>">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="<?= base_url('assets/backend/font-awesome/css/font-awesome.min.css') ?>">
        <!-- Theme style -->
        <link rel="stylesheet" href="<?= base_url('assets/backend/js/datatables.net-bs/css/dataTables.bootstrap.min.css') ?>">
        <!-- Theme style -->
        <link rel="stylesheet" href="<?= base_url('assets/backend/css/AdminLTE.min.css') ?>">
        <!-- Theme style -->
        <link rel="stylesheet" href="<?= base_url('assets/global/bootbox/bootbox-custom.css') ?>">
        <!-- Theme style -->
        <link rel="stylesheet" href="<?= base_url('assets/global/jquery-form-validator-net/form-validator/theme-default.min.css') ?>">
        <!-- AdminLTE Skins. Choose a skin from the css/skins
             folder instead of downloading all of them to reduce the load. -->
        <link rel="stylesheet" href="<?= base_url('assets/backend/css/skins/skin-blue-light.min.css') ?>">

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->

        <!-- Google Font -->
        <!--        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">-->
        <link rel="stylesheet" href="<?= base_url('assets/backend/google-font/google-fonts.css') ?>">
        <!--css tambahan yang di load dari controller-->
        <?php echo isset($FILE_CSS) ? $FILE_CSS : ''; ?>
    </head>
    <body class="hold-transition skin-blue-light sidebar-mini">
        <!-- Site wrapper -->
        <div class="wrapper">

            <header class="main-header">
                <!-- Logo -->
                <a href="javascript:void()" class="logo">
                    <!-- logo for regular state and mobile devices -->
                    <span class="logo-lg"><b>CIRCLE</b>LABS</span>
                </a>
                <!-- Header Navbar: style can be found in header.less -->
                <nav class="navbar navbar-static-top">
                    <!-- Sidebar toggle button-->
                </nav>
            </header>

            <!-- =============================================== -->

            <!-- Left side column. contains the sidebar -->
            <aside class="main-sidebar">
                <!-- sidebar: style can be found in sidebar.less -->
                <section class="sidebar">
                    <!-- Sidebar user panel -->
                    <div class="user-panel">
                        <div class="pull-left image">
                            <img src="<?= base_url('assets/images/circlelabs.jpg') ?>" class="img-circle" alt="Image">
                        </div>
                        <div class="pull-left info">
                            <p>CIRCLELABS</p>
                            <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
                        </div>
                    </div>
                    <!-- /.search form -->
                    <!-- sidebar menu: : style can be found in sidebar.less -->
                    <ul class="sidebar-menu" data-widget="tree">
                        <li class="header">MENU</li>
                        <?= $TPL_SIDE_MENU; ?>
                        <li><a href="https://adminlte.io/docs"><i class="fa fa-book"></i> <span>Documentation</span></a></li>
                        <li class="header">LABELS</li>
                        <li><a href="#"><i class="fa fa-circle-o text-red"></i> <span>Important</span></a></li>
                        <li><a href="#"><i class="fa fa-circle-o text-yellow"></i> <span>Warning</span></a></li>
                        <li><a href="#"><i class="fa fa-circle-o text-aqua"></i> <span>Information</span></a></li>
                    </ul>
                </section>
                <!-- /.sidebar -->
            </aside>

            <!-- =============================================== -->

            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>
                        <?= $PORTAL_INFO['nama_menu'] ?>
                        <small><?= $PORTAL_INFO['deskripsi'] ?></small>
                    </h1>
                    <ol class="breadcrumb">
                        <li><a href="#"><i class="fa fa-dashboard"></i> Portal</a></li>
                    </ol>
                </section>
                <?php !empty($TPL_ISI) ? $this->load->view($TPL_ISI) : 'Index belum di-load'; ?>
                <!-- /.content -->
            </div>
            <!-- /.content-wrapper -->

            <footer class="main-footer">
                <div class="pull-right hidden-xs">
                    <b>Version</b> 1.0
                </div>
                <strong>Copyright &copy; 2017 <a href="http://circlelabs.id">CIRCLE LABS</a>.</strong> All rights
                reserved.
            </footer>

            <!-- Control Sidebar -->
            <!-- /.control-sidebar -->
            <!-- Add the sidebar's background. This div must be placed
                 immediately after the control sidebar -->
            <div class="control-sidebar-bg"></div>
        </div>
        <!-- ./wrapper -->

        <!-- jQuery 3 -->
        <script src="<?= base_url('assets/backend/jquery/dist/jquery.min.js') ?>"></script>
        <!-- Bootstrap 3.3.7 -->
        <script src="<?= base_url('assets/backend/bootstrap/js/bootstrap.min.js') ?>"></script>
        <!-- AdminLTE App -->
        <!--<script src="../../dist/js/adminlte.min.js"></script>-->
        <script src="<?= base_url('assets/backend/js/adminlte.min.js') ?>"></script>
        <script src="<?= base_url('assets/global/bootbox/bootbox.min.js') ?>"></script>
        <script src="<?= base_url('assets/global/notify/notify.min.js') ?>"></script>
        <script src="<?= base_url('assets/global/jquery-form-validator-net/form-validator/jquery.form-validator.min.js') ?>"></script>
        <!--datatable-->
        <script src="<?= base_url('assets/backend/js/datatable/jquery.dataTables.min.js') ?>"></script>
        <!--datatable-->
        <script src="<?= base_url('assets/backend/js/datatables.net-bs/js/dataTables.bootstrap.min.js') ?>"></script>
        <!-- circlelabs custom js-->
        <script>
            $(document).ready(function () {
                $('.sidebar-menu').tree();

                $.ajaxSetup({
                    headers: {<?= '"' . $this->security->get_csrf_token_name() . '":"' . $this->security->get_csrf_hash() . '"' ?>}
                });

                $.extend(true, $.fn.dataTable.defaults, {
                    "processing": true,
                    "language": {
                        "url": "<?= base_url('assets/backend/js/datatable/indonesia.json') ?>"
                    },
                    "searchDelay": 1000,
                    "serverSide": true,
                    "ordering": false,
                    "order": []
                });

                bootbox.setDefaults({
                    /*bahasa*/
                    locale: "id",
                    show: true,
                    backdrop: false,
                    closeButton: true,
                    animate: true,
                    /**
                     * @optional String
                     * @default: null
                     * an additional class to apply to the dialog wrapper
                     */
                    className: "modal-bootbox"
                });

            })
        </script>
        <?php
        echo isset($FILE_JS) ? $FILE_JS : '';
        isset($TPL_FOOTER) && !empty($TPL_FOOTER) ? $this->load->view($TPL_FOOTER) : '';
        ?>
    </body>
</html>
