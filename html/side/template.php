<?php
class template{

    private $init_bc;
    private $init_title;
    private $init_page_title;
    private $init_session;

    private $init_footer;

    private $pagejs;

    private $template_js = "";
    private $template_css = "";

    private $includejs = array(
        "maps" =>
            array("
            <script src='http://maps.google.com/maps/api/js?key=AIzaSyCqZ_D2GSySIaTr3KrpR8_Iq2vMY-UIbWM&sensor=true'></script>
            <script src='../plugins/gmaps/gmaps.min.js'></script>
            <script src='../assets/pages/jquery.gmaps.js'></script>
            ","
            "),
        "gallery" =>
            array("
            <script type='text/javascript' src='../plugins/isotope/dist/isotope.pkgd.min.js'></script>
            <script type='text/javascript' src='../plugins/magnific-popup/dist/jquery.magnific-popup.min.js'></script>
            ","
            <link rel='stylesheet' href='../plugins/magnific-popup/dist/magnific-popup.css'/>
            "),
        "switchery" =>
            array("
            <script src='../plugins/switchery/switchery.min.js'></script>
            ","
            <link href='../plugins/switchery/switchery.min.css' rel='stylesheet' />
            "),
        "xeditable" =>
            array("
            <script type='text/javascript' src='../plugins/x-editable/dist/bootstrap3-editable/js/bootstrap-editable.min.js'></script>
            ","
            <link type='text/css' href='../plugins/x-editable/dist/bootstrap3-editable/css/bootstrap-editable.css' rel='stylesheet'>
            "),
        "counterup" =>
            array("
            <script src='../plugins/waypoints/lib/jquery.waypoints.js'></script>
            <script src='../plugins/counterup/jquery.counterup.min.js'></script>
            ","
            "),
        "peity" =>
            array("
            <script src='../plugins/peity/jquery.peity.min.js'></script>
            ","
            "),
        "tagsinput" =>
            array("
            <script src='/prokal/plugins/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js'></script>
            ","
            <link href='/prokal/plugins/bootstrap-tagsinput/dist/bootstrap-tagsinput.css' rel='stylesheet' />
            "),
        "dropzone" =>
            array("
            <script src='/prokal/plugins/dropzone/dist/dropzone.js'></script>
            ","
            <link href='/prokal/plugins/dropzone/dist/dropzone.css' rel='stylesheet' type='text/css' />
            "),
        "summernote" =>
            array("
            <script src='/prokal/plugins/summernote/dist/summernote.min.js'></script>
            ","
            <link href='/prokal/plugins/summernote/dist/summernote.css' rel='stylesheet' />
            "),
        "inputmask" =>
            array("
            <script src='/prokal/plugins/jQuery-Mask-Plugin-master/dist/jquery.mask.min.js' type='text/javascript'></script>
            ","
            "),
        "datepicker" =>
            array("
            <script src='/prokal/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js'></script>
            ","
            <link href='/prokal/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css' rel='stylesheet'>
            "),
        "bootstraptable" =>
            array("
            <script src='/prokal/plugins/bootstrap-table/dist/bootstrap-table.min.js'></script>
            <script src='/prokal/plugins/bootstrap-table/dist/extensions/mobile/bootstrap-table-mobile.min.js'></script>
            <script src='/prokal/plugins/bootstrap-table/dist/locale/bootstrap-table-id-ID.min.js'></script>
            ","
            <link href='/prokal/plugins/bootstrap-table/dist/bootstrap-table.min.css' rel='stylesheet' type='text/css' />
            "),
        "datatables" =>
            array("
            <script src='/prokal/plugins/datatables/jquery.dataTables.min.js'></script>
            <script src='/prokal/plugins/datatables/dataTables.bootstrap.js'></script>
            <script src='/prokal/plugins/datatables/dataTables.buttons.min.js'></script>
            <script src='/prokal/plugins/datatables/buttons.bootstrap.min.js'></script>
            <script src='/prokal/plugins/datatables/jszip.min.js'></script>
            <script src='/prokal/plugins/datatables/pdfmake.min.js'></script>
            <script src='/prokal/plugins/datatables/vfs_fonts.js'></script>
            <script src='/prokal/plugins/datatables/buttons.html5.min.js'></script>
            <script src='/prokal/plugins/datatables/buttons.print.min.js'></script>
            <script src='/prokal/plugins/datatables/dataTables.fixedHeader.min.js'></script>
            <script src='/prokal/plugins/datatables/dataTables.keyTable.min.js'></script>
            <script src='/prokal/plugins/datatables/dataTables.responsive.min.js'></script>
            <script src='/prokal/plugins/datatables/responsive.bootstrap.min.js'></script>
            <script src='/prokal/plugins/datatables/dataTables.scroller.min.js'></script>
            ","
            <link href='/prokal/plugins/datatables/jquery.dataTables.min.css' rel='stylesheet' type='text/css' />
            <link href='/prokal/plugins/datatables/buttons.bootstrap.min.css' rel='stylesheet' type='text/css' />
            <link href='/prokal/plugins/datatables/fixedHeader.bootstrap.min.css' rel='stylesheet' type='text/css' />
            <link href='/prokal/plugins/datatables/responsive.bootstrap.min.css' rel='stylesheet' type='text/css' />
            <link href='/prokal/plugins/datatables/scroller.bootstrap.min.css' rel='stylesheet' type='text/css' />
            "),
        "flot" =>
            array("
            <script src='/prokal/plugins/flot-chart/jquery.flot.js'></script>
            <script src='/prokal/plugins/flot-chart/jquery.flot.time.js'></script>
            <script src='/prokal/plugins/flot-chart/jquery.flot.tooltip.min.js'></script>
            <script src='/prokal/plugins/flot-chart/jquery.flot.resize.js'></script>
            <script src='/prokal/plugins/flot-chart/jquery.flot.pie.js'></script>
            <script src='/prokal/plugins/flot-chart/jquery.flot.selection.js'></script>
            <script src='/prokal/plugins/flot-chart/jquery.flot.stack.js'></script>
            <script src='/prokal/plugins/flot-chart/jquery.flot.crosshair.js'></script>
            ","
            "),
        "chartist" =>
            array("
            <script src='/prokal/plugins/chartist/dist/chartist.min.js'></script>
            ","
            <link rel='stylesheet' href='/prokal/plugins/chartist/dist/chartist.min.css'>
            "),
        "tablesaw" => 
            array("
                <script src='/prokal/plugins/tablesaw/dist/tablesaw.js'></script>
                <script src='/prokal/plugins/tablesaw/dist/tablesaw-init.js'></script>
                ","
                <link href='/prokal/plugins/tablesaw/dist/tablesaw.css' rel='stylesheet' type='text/css' />
            "),

        "custombox" => 
            array("
                <script src='/prokal/plugins/custombox/dist/custombox.min.js'></script>
                <script src='/prokal/plugins/custombox/dist/legacy.min.js'></script>
                ","
                <link href='/prokal/plugins/custombox/dist/custombox.min.css' rel='stylesheet'>
            "),

        "sweetalert" =>
            array("
                <script src='/prokal/plugins/bootstrap-sweetalert/sweet-alert.min.js'></script>
                ","
                <link href='/prokal/plugins/bootstrap-sweetalert/sweet-alert.css' rel='stylesheet' type='text/css'>
            "),
        "parsley" => 
            array("
                <script type='text/javascript' src='/prokal/plugins/parsleyjs/dist/parsley.min.js'></script>
                ",""
            )
    );

    function includejs($js, $css = true){
        $this->template_js = $this->template_js.$this->includejs[$js][0];
        if($css) $this->template_css = $this->template_css.$this->includejs[$js][1];
    }

    function init($array, $title, $page_title, $session){
        $this->init_bc = $array;
        $this->init_title = $title;
        $this->init_page_title = $page_title;
        $this->init_session = $session;
    }

    function pagejs($file){
        $this->pagejs = $file;
    }

    function call($string){

        switch ($string) { case 'include': ?>
        <!DOCTYPE html>
        <html>
            <head>
                <meta charset="utf-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <meta name="description" content="A fully featured admin theme which can be used to build CRM, CMS, etc.">
                <meta name="author" content="Coderthemes">

                <!-- <link rel="shortcut icon" href="/prokal/assets/images/favicon_1.ico"> -->
                <link rel="icon" type="image/png" href="/prokal/assets/images/favicon-32x32.png" sizes="32x32" />
                <link rel="icon" type="image/png" href="/prokal/assets/images/favicon-16x16.png" sizes="16x16" />

                <title><?php echo $this->init_page_title; ?></title>

                <?php echo $this->template_css; ?>

                <link href="/prokal/assets/css/side/bootstrap.min.css" rel="stylesheet" type="text/css" />
                <link href="/prokal/assets/css/side/bootstrap-custom.css" rel="stylesheet" type="text/css" />
                <link href="/prokal/assets/css/side/core.css" rel="stylesheet" type="text/css" />
                <link href="/prokal/assets/css/side/components.css" rel="stylesheet" type="text/css" />
                <link href="/prokal/assets/css/side/icons.css" rel="stylesheet" type="text/css" />
                <link href="/prokal/assets/css/side/pages.css" rel="stylesheet" type="text/css" />
                <link href="/prokal/assets/css/side/menu.css" rel="stylesheet" type="text/css" />
                <link href="/prokal/assets/css/side/responsive.css" rel="stylesheet" type="text/css" />

                <script src="/prokal/assets/js/modernizr.min.js"></script>

                <style>
                .modal-open {
                    padding-right: 0 !important;
                }
                </style>

        <?php
        break;
                
        case 'topbar': ?>

    </head>


    <body class="fixed-left">
        
        <!-- Begin page -->
        <div id="wrapper">
        
            <!-- Top Bar Start -->
            <div class="topbar">

                <!-- LOGO -->
                <div class="topbar-left">
                    <div class="text-center">
                        <a href="." class="logo"><!-- <i class="md md-equalizer"></i> --> <span>SIBIMA-KU</span> </a>
                    </div>
                </div>
        <?php
        break;

        case 'navbar': ?>
        <!-- Navbar Start -->
                <!-- Navbar -->
                <div class="navbar navbar-default" role="navigation">
                    <div class="container">
                        <div class="">
                            <div class="pull-left">
                                <button class="button-menu-mobile open-left waves-effect">
                                    <i class="md md-menu"></i>
                                </button>
                                <span class="clearfix"></span>
                            </div>


                            <ul class="nav navbar-nav navbar-right pull-right">

                                <li class="dropdown hidden-xs">
                                    <a href="#" data-target="#" class="dropdown-toggle waves-effect waves-light"
                                       data-toggle="dropdown" aria-expanded="true">
                                        <i class="md md-notifications"></i> <span
                                            class="badge badge-xs badge-pink">3</span>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-lg">
                                        <li class="text-center notifi-title">Notification</li>
                                        <li class="list-group nicescroll notification-list">
                                            <!-- list item-->
                                            

                                        </li>

                                        <li>
                                            <a href="javascript:void(0);" class=" text-right">
                                                <small><b>See all notifications</b></small>
                                            </a>
                                        </li>

                                    </ul>
                                </li>
                                <li class="hidden-xs">
                                    <a href="#" class="right-bar-toggle waves-effect waves-light"><i
                                            class="md md-settings"></i></a>
                                </li>

                            </ul>
                        </div>
                        <!--/.nav-collapse -->
                    </div>
                </div>
            </div>
            <!-- Top Bar End -->

        <?php
        break;

        case 'leftbar': ?>

        <!-- ========== Left Sidebar Start ========== -->
            <div class="left side-menu">
                <div class="sidebar-inner slimscrollleft">

                    <div id="sidebar-menu">
                        <ul>
                            <li class="menu-title">Main</li>

                            <?php if($this->init_session['level'] > 5) echo '
                            <li class="has_sub">
                                <a href="javascript:void(0);" class="waves-effect waves-primary"><i class="md md-layers"></i> <span> Administrator </span>
                                 <span class="menu-arrow"></span></a>
                                <ul class="list-unstyled">
                                    <li><a href="?d=usrman">User Manager</a></li>
                                    <li><a href="?d=archive">Arsip</a></li>
                                    <li><a href="?d=fileman">File Manager</a></li>';
                                if($this->init_session['level'] > 6) echo '
                                    <li><a href="?d=sufileman">SU File Manager</a></li>
                                    <li><a href="?d=sufileman">SU Manager</a></li>';
                                if($this->init_session['level'] > 5) echo '
                                </ul>
                            </li>'; ?>

                            <?php if($this->init_session['level'] > 2) echo '
                            <li class="has_sub">
                                <a href="javascript:void(0);" class="waves-effect waves-primary"><i class="md md-dashboard"></i> <span> Dashboard </span>
                                 <span class="menu-arrow"></span></a>
                                <ul class="list-unstyled">
                                    <li><a href="?d=catman">Daftar Kegiatan</a></li>
                                    <li><a href="?d=catman&id='.$_SESSION['kegiatan'].'">Rincian Kegiatan</a></li>
                                    <li><a href="?d=dashboard&id='.$_GET['id'].'">Rincian Pekerjaan</a></li>
                                </ul>
                            </li>';
                            else echo '<li>
                                <a href="?d=dashboard" class="waves-effect waves-primary"><i
                                        class="md md-dashboard"></i><span> Dashboard </span></a>
                            </li>'; ?>

                            <?php if($this->init_session['level'] > 2) $_GET['id'] = "&id=".$_GET['id']; else $_GET['id'] = ''; ?>
                            <li>
                                <a href="?d=docs<?php echo $_GET['id']; ?>" class="waves-effect waves-primary"><i
                                        class="md md-assignment"></i><span> Dokumen </span></a>
                            </li>

                            <li>
                                <a href="?d=gallery<?php echo $_GET['id']; ?>" class="waves-effect waves-primary"><i
                                        class="md md-photo-library"></i><span> Gallery </span></a>
                            </li>

                            <li>
                                <a href="?d=chat<?php echo $_GET['id']; ?>" class="waves-effect waves-primary"><i
                                        class="md md-chat"></i><span> Chat Room </span></a>
                            </li>

                            <li>
                                <a href="?d=maps<?php echo $_GET['id']; ?>" class="waves-effect waves-primary"><i
                                        class="md md-location-history"></i><span> Peta Lokasi </span></a>
                            </li>

                            <li class="menu-title">More</li>

                            <?php if($this->init_session['level'] > 2) echo '
                            <li>
                                <a  href="?d=mail" class="waves-effect waves-primary"><i
                                        class="md md-mail"></i><span> Permintaan </span></a>
                            </li>'; ?>

                            <li class="has_sub">
                                <a href="javascript:void(0);" class="waves-effect waves-primary"><i
                                        class="md md-settings-applications"></i><span> Pengaturan </span> <span class="menu-arrow"></span></a>
                                <ul class="list-unstyled">
                                    <!-- <li><a href="#">Profile</a></li>
                                    <li><a href="#">Pengaturan</a></li> -->
                                    <li><a href="../login/logout.php">Logout</a></li>
                                </ul>
                            </li>

                        </ul>
                        <div class="clearfix"></div>
                    </div>

                    <div class="clearfix"></div>
                </div>

                <div class="user-detail">
                    <div class="dropup">
                        <a href="" class="profile" aria-expanded="true">
                            <span class="user-info-span">
                                <h5 class="m-t-0 m-b-0"><?php echo $this->init_session['name']; ?></h5>
                                <p class="text-muted m-b-0">
                                    <small><i class="fa fa-circle text-success"></i> <span>
                                    <?php switch ($this->init_session['level']) {
                                        case 5:
                                            echo 'Kepala Dinas';
                                            break;

                                        case 4:
                                            echo 'PPK';
                                            break;

                                        case 3:
                                            echo 'PPTK';
                                            break;

                                        case 2:
                                            echo 'Kontraktor';
                                            break;
                                        
                                        default:
                                            # code...
                                            break;
                                    } ?></span></small>
                                </p>
                            </span>
                        </a>
                    </div>
                </div>
            </div>
            <!-- Left Sidebar End --> 

            <div class="content-page">
                <!-- Start content -->
                <div class="content">
                    <div class="container">

                        <!-- Page-Title -->
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="page-title-box">
                                    <ol class="breadcrumb pull-right">
                                        <?php

                                for($x=0;$x<count($this->init_bc);$x++):

                                    if($this->init_bc[$x][2] == 1):
                                        echo "<li class='active'>".$this->init_bc[$x][1]."</li>";
                                    else:
                                        echo "<li><a href='".$this->init_bc[$x][0]."'>".$this->init_bc[$x][1]."</a></li>";
                                    endif;

                                endfor;
                                ?>
                                    </ol>
                                    <h4 class="page-title"><?php echo $this->init_title; ?></h4>
                                </div>
                            </div>
                        </div>

        <?php
        break;

        case 'footer': ?>
                        </div>
                    <!-- end container -->

                </div>
                <!-- end content -->



                <!-- FOOTER -->
                <footer class="footer text-right">
                    2017 © Excelsior Dev.
                </footer>
                <!-- End FOOTER -->

            </div>
        <?php
        break;

        case 'includejs': ?>

        <script>
            var resizefunc = [];
        </script>

        <!-- jQuery  -->
        <script src="/prokal/assets/js/jquery.min.js"></script>
        <script src="/prokal/assets/js/bootstrap.min.js"></script>
        <script src="/prokal/assets/js/detect.js"></script>
        <script src="/prokal/assets/js/fastclick.js"></script>
        <script src="/prokal/assets/js/jquery.slimscroll.js"></script>
        <script src="/prokal/assets/js/jquery.blockUI.js"></script>
        <script src="/prokal/assets/js/waves.js"></script>
        <script src="/prokal/assets/js/wow.min.js"></script>
        <script src="/prokal/assets/js/jquery.nicescroll.js"></script>
        <script src="/prokal/assets/js/jquery.scrollTo.min.js"></script>
        <script src="/prokal/plugins/switchery/switchery.min.js"></script>

        <script src="/prokal/assets/js/jquery.core.js"></script>
        <script src="/prokal/assets/js/side/jquery.app.js"></script>

        <?php echo $this->template_js; ?>

        <?php if(isset($this->pagejs)): ?>
        <script src="/prokal/assets/pages/<?php echo $this->pagejs; ?>"></script>
        <?php endif;
        break;

        case 'endfile': ?>

                    </body>
        </html>

        <?php
        break;
        }
    }
}