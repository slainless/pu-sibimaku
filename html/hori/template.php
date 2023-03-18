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
        "counterup" =>
            array("
            <script src='/plugins/waypoints/lib/jquery.waypoints.js'></script>
            <script src='/plugins/counterup/jquery.counterup.min.js'></script>
            ","
            "),
        "peity" =>
            array("
            <script src='/plugins/peity/jquery.peity.min.js'></script>
            ","
            "),
        "summernote" =>
            array("
            <script src='/plugins/summernote/dist/summernote.min.js'></script>
            ","
            <link href='/plugins/summernote/dist/summernote.css' rel='stylesheet' />
            "),
        "inputmask" =>
            array("
            <script src='/plugins/jQuery-Mask-Plugin-master/dist/jquery.mask.min.js' type='text/javascript'></script>
            ","
            "),
        "datepicker" =>
            array("
            <script src='/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js'></script>
            ","
            <link href='/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css' rel='stylesheet'>
            "),
        "bootstraptable" =>
            array("
            <script src='/plugins/bootstrap-table/dist/bootstrap-table.min.js'></script>
            <script src='/plugins/bootstrap-table/dist/extensions/mobile/bootstrap-table-mobile.min.js'></script>
            <script src='/plugins/bootstrap-table/dist/locale/bootstrap-table-id-ID.min.js'></script>
            ","
            <link href='/plugins/bootstrap-table/dist/bootstrap-table.min.css' rel='stylesheet' type='text/css' />
            "),
        "datatables" =>
            array("
            <script src='/plugins/datatables/jquery.dataTables.min.js'></script>
            <script src='/plugins/datatables/dataTables.bootstrap.js'></script>
            <script src='/plugins/datatables/dataTables.buttons.min.js'></script>
            <script src='/plugins/datatables/buttons.bootstrap.min.js'></script>
            <script src='/plugins/datatables/jszip.min.js'></script>
            <script src='/plugins/datatables/pdfmake.min.js'></script>
            <script src='/plugins/datatables/vfs_fonts.js'></script>
            <script src='/plugins/datatables/buttons.html5.min.js'></script>
            <script src='/plugins/datatables/buttons.print.min.js'></script>
            <script src='/plugins/datatables/dataTables.fixedHeader.min.js'></script>
            <script src='/plugins/datatables/dataTables.keyTable.min.js'></script>
            <script src='/plugins/datatables/dataTables.responsive.min.js'></script>
            <script src='/plugins/datatables/responsive.bootstrap.min.js'></script>
            <script src='/plugins/datatables/dataTables.scroller.min.js'></script>
            ","
            <link href='/plugins/datatables/jquery.dataTables.min.css' rel='stylesheet' type='text/css' />
            <link href='/plugins/datatables/buttons.bootstrap.min.css' rel='stylesheet' type='text/css' />
            <link href='/plugins/datatables/fixedHeader.bootstrap.min.css' rel='stylesheet' type='text/css' />
            <link href='/plugins/datatables/responsive.bootstrap.min.css' rel='stylesheet' type='text/css' />
            <link href='/plugins/datatables/scroller.bootstrap.min.css' rel='stylesheet' type='text/css' />
            "),
        "flot" =>
            array("
            <script src='/plugins/flot-chart/jquery.flot.js'></script>
            <script src='/plugins/flot-chart/jquery.flot.time.js'></script>
            <script src='/plugins/flot-chart/jquery.flot.tooltip.min.js'></script>
            <script src='/plugins/flot-chart/jquery.flot.resize.js'></script>
            <script src='/plugins/flot-chart/jquery.flot.pie.js'></script>
            <script src='/plugins/flot-chart/jquery.flot.selection.js'></script>
            <script src='/plugins/flot-chart/jquery.flot.stack.js'></script>
            <script src='/plugins/flot-chart/jquery.flot.crosshair.js'></script>
            ","
            "),
        "chartist" =>
            array("
            <script src='/plugins/chartist/dist/chartist.min.js'></script>
            ","
            <link rel='stylesheet' href='/plugins/chartist/dist/chartist.min.css'>
            "),
        "tablesaw" => 
            array("
                <script src='/plugins/tablesaw/dist/tablesaw.js'></script>
                <script src='/plugins/tablesaw/dist/tablesaw-init.js'></script>
                ","
                <link href='/plugins/tablesaw/dist/tablesaw.css' rel='stylesheet' type='text/css' />
            "),

        "custombox" => 
            array("
                <script src='/plugins/custombox/dist/custombox.min.js'></script>
                <script src='/plugins/custombox/dist/legacy.min.js'></script>
                ","
                <link href='/plugins/custombox/dist/custombox.min.css' rel='stylesheet'>
            "),

        "sweetalert" =>
            array("
                <script src='/plugins/bootstrap-sweetalert/sweet-alert.min.js'></script>
                ","
                <link href='/plugins/bootstrap-sweetalert/sweet-alert.css' rel='stylesheet' type='text/css'>
            "),
        "parsley" => 
            array("
                <script type='text/javascript' src='/plugins/parsleyjs/dist/parsley.min.js'></script>
                ",""
            )
    );

    function includejs($js){
        $this->template_js = $this->template_js.$this->includejs[$js][0];
        $this->template_css = $this->template_css.$this->includejs[$js][1];
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

        switch ($string) { 

        case 'include': ?>
        <!DOCTYPE html>
        <html>
            <head>
                <meta charset="utf-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <meta name="description" content="A fully featured admin theme which can be used to build CRM, CMS, etc.">
                <meta name="author" content="Coderthemes">

                <!-- <link rel="shortcut icon" href="/assets/images/favicon_1.ico"> -->
                <link rel="icon" type="image/png" href="/assets/images/favicon-32x32.png" sizes="32x32" />
                <link rel="icon" type="image/png" href="/assets/images/favicon-16x16.png" sizes="16x16" />

                <title><?php echo $this->init_page_title; ?></title>

                <?php echo $this->template_css; ?>

                <link href="/assets/css/hori/bootstrap.min.css" rel="stylesheet" type="text/css" />
                <link href="/assets/css/hori/core.css" rel="stylesheet" type="text/css" />
                <link href="/assets/css/hori/components.css" rel="stylesheet" type="text/css" />
                <link href="/assets/css/hori/icons.css" rel="stylesheet" type="text/css" />
                <link href="/assets/css/hori/pages.css" rel="stylesheet" type="text/css" />
                <link href="/assets/css/hori/menu.css" rel="stylesheet" type="text/css" />
                <link href="/assets/css/hori/responsive.css" rel="stylesheet" type="text/css" />
                <link href="/assets/css/stardusk.css" rel="stylesheet" type="text/css" />

                <script src="/assets/js/modernizr.min.js"></script>

                <style>
                .modal-open {
                    padding-right: 0 !important;
                }
                </style>


        <?php
        break;
                
        case 'topbar': ?>

            </head>
            <body>
        <!-- Navigation Bar-->
                <header id="topnav">
                    <div class="topbar-main">
                        <div class="container">

                            <!-- Logo container-->
                            <div class="logo">
                                <a href="." class="logo"><!-- <i class="md md-terrain"></i> --> <span>SIBIMA-KU</span> </a>
                            </div>
                            <!-- End Logo container-->

                            <div class="menu-extras">

                                <ul class="nav navbar-nav navbar-right pull-right">

                                    <li>
                                        <a class="waves-effect"><strong><?php echo $this->init_session['name']; ?></strong></a>
                                    </li>
                                    <li class="dropdown hidden-xs">
                                        <a href="#" data-target="#" class="dropdown-toggle waves-effect waves-light"
                                           data-toggle="dropdown" aria-expanded="true">
                                            <i class="md md-notifications"></i> <span
                                                class="badge badge-xs badge-pink">1</span>
                                        </a>
                                        <ul class="dropdown-menu dropdown-menu-lg">
                                            <li class="text-center notifi-title">Notification</li>
                                            <li class="list-group nicescroll notification-list">
                                                
                                            </li>

                                            <li>
                                                <a href="javascript:void(0);" class=" text-right">
                                                    <small><b>See all notifications</b></small>
                                                </a>
                                            </li>

                                        </ul>
                                    </li>

                                    <!-- <li class="dropdown">
                                        <a href="" class="dropdown-toggle waves-effect waves-light profile" data-toggle="dropdown" aria-expanded="true"><img src="/assets/images/users/avatar-1.jpg" alt="user-img" class="img-circle"> </a>
                                        <ul class="dropdown-menu">
                                        </ul>
                                    </li> -->
                                </ul>

                                <div class="menu-item">
                                    <!-- Mobile menu toggle-->
                                    <a class="navbar-toggle">
                                        <div class="lines">
                                            <span></span>
                                            <span></span>
                                            <span></span>
                                        </div>
                                    </a>
                                    <!-- End mobile menu toggle-->
                                </div>
                            </div>

                        </div>
                    </div>
                    <!-- End topbar -->
        <?php
        break;

        case 'navbar': ?>
        <!-- Navbar Start -->
                    <div class="navbar-custom">
                        <div class="container">
                            <div id="navigation">
                                <!-- Navigation Menu-->

                                <ul class="navigation-menu">
                                    <?php if($this->init_session['level'] > 5) echo '
                                    <li class="has-submenu">
                                        <a><i class="md md-layers"></i>Administrator</a>
                                        <ul class="submenu">
                                            <li><a href="?d=usrman">User Manager</a></li>
                                            <li><a href="?d=archive">Arsip</a></li>
                                            <li><a href="?d=fileman">File Manager</a></li>';
                                        if($this->init_session['level'] > 6) echo '
                                            <li><a href="?d=sufileman">SU File Manager</a></li>
                                            <li><a href="?d=sufileman">SU Manager</a></li>';
                                        if($this->init_session['level'] > 5) echo '
                                        </ul>
                                    </li>'; ?>

                                    <li class="has-submenu">
                                        <a href="?d=catman"><i class="md md-dashboard"></i>Kegiatan</a>
                                    </li>

                                    <li class="has-submenu">
                                        <a href="?d=mail"><i class="md md-mail"></i>Permintaan</a>
                                    </li>

                                    <li class="has-submenu">
                                        <a><i class="md md-settings-applications"></i>Pengaturan</a>
                                        <ul class="submenu">
                                            <!-- <li><a href="#">Profile</a></li>
                                            <li><a href="#">Pengaturan</a></li> -->
                                            <li><a href="/login/logout.php">Logout</a></li>
                                        </ul>
                                    </li>

                                </ul>

                                <!-- End navigation menu -->
                            </div> <!-- end #navigation -->
                        </div> <!-- end container -->
                    </div> <!-- end navbar-custom -->
                </header>
                <!-- End Navigation Bar-->

            <div class="wrapper">
                <div class="container">

                    <div class="row">
                        <div class="col-sm-12">
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
                            <h4 class="page-title m-t-10"><?php echo $this->init_title; ?></h4>
                        </div>
                    </div>

        <?php
        break;

        case 'footer': ?>
                        <!-- Footer -->
                        <footer class="footer text-right">
                            <div class="container">
                                <div class="row">
                                    <div class="col-xs-12">
                                        2017 © Excelsior Dev.
                                    </div>
                                </div>
                            </div>
                        </footer>
                        <!-- End Footer -->

                    </div> <!-- end container -->
                </div>
                <!-- End wrapper -->
        <?php
        break;

        case 'basicjs': ?>

                <!-- jQuery  -->
                <script src="/assets/js/jquery.min.js"></script>
                <script src="/assets/js/bootstrap.min.js"></script>
                <script src="/assets/js/detect.js"></script>
                <script src="/assets/js/fastclick.js"></script>
                <script src="/assets/js/jquery.blockUI.js"></script>
                <script src="/assets/js/waves.js"></script>
                <script src="/assets/js/wow.min.js"></script>
                <script src="/assets/js/jquery.nicescroll.js"></script>
                <script src="/assets/js/jquery.scrollTo.min.js"></script>

                <!-- Custom main Js -->
                <script src="/assets/js/jquery.core.js"></script>
                <script src="/assets/js/hori/jquery.app.js"></script>

                <?php echo $this->template_js; ?>

                <?php if(isset($this->pagejs)): 
                if(is_array($this->pagejs)){
                  foreach($this->pagejs as $url){
                    ?>
                    <script src="/assets/pages/<?php echo $url; ?>"></script>
                    <?php }
                }
                else 
                {
                ?>
                <script src="/assets/pages/<?php echo $this->pagejs; ?>"></script>
                <?php
                }
                endif; ?>
        <?php
        break;

        case 'endfile': ?>

                    </body>
        </html>

        <?php
        break;
        }
    }
}