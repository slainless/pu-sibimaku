<?php
    require_once "dir-conf.php";

    require_once $access_main;
    require_once "function-login.php";

    $session = new session();
    $login = new login($query);
?>

<?php if($login->check() == false):?>

    <!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <meta name="description" content="A fully featured admin theme which can be used to build CRM, CMS, etc.">
        <meta name="author" content="">

        <link rel="shortcut icon" href="../assets/images/favicon_1.ico">

        <title>SIBIMA-KU | Login</title>

        <link href="/plugins/switchery/switchery.min.css" rel="stylesheet" />
        <link href="/assets/css/side/bootstrap.min.css" rel="stylesheet" type="text/css">
        <link href="/assets/css/side/core.css" rel="stylesheet" type="text/css">
        <link href="/assets/css/side/icons.css" rel="stylesheet" type="text/css">
        <link href="/assets/css/side/components.css" rel="stylesheet" type="text/css">
        <link href="/assets/css/side/pages.css" rel="stylesheet" type="text/css">
        <link href="/assets/css/side/menu.css" rel="stylesheet" type="text/css">
        <link href="/assets/css/side/responsive.css" rel="stylesheet" type="text/css">

        <script src="/assets/js/modernizr.min.js"></script>
        <script src="/assets/pages/user/sha512.js"></script>
        <script src="/assets/pages/user/forms.js"></script>

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->

        <style>
            .wrapper-page {
                max-width: 1024px;
                margin: 0 auto;
            }
            .wrapper-page {
                height: 100%;
                overflow: hidden;
                width: 100%;
            }
        </style>
        
    </head>
    <body>


        <div class="wrapper-page">
            <div class="row">
                <div class="col-sm-12">
                    <div class="col-sm-2 m-t-20" style="padding: 20px">
                        <img src="../assets/images/Emblem_of_North_Kalimantan.png" width="100%">
                    </div>
                    <div class="col-sm-10 m-t-20 text-center" style="padding: 20px; padding-right: 0;">
                        <h3 class="m-t-15">PEMERINTAH PROVINSI KALIMANTAN UTARA</h3>
                        <h2>DINAS PEKERJAAN UMUM, PENATAAN RUANG, PERUMAHAN DAN KAWASAN PERMUKIMAN</h2>
                        <h5>Jalan Agathis, Telp. (0552) 21490, TANJUNG SELOR</h5>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="row">
                        <div class="col-sm-offset-4 col-sm-4" style="padding: 20px; padding-bottom: 0; margin-top: -40px">
                            <form class="form-horizontal m-t-20" action="/login/process" method="POST">

                                <div class="form-group">
                                    <div class="col-xs-12">
                                        <input class="form-control" type="text" required="" placeholder="Username" name="username">
                                        <i class="md md-account-circle form-control-feedback l-h-34"></i>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-xs-12">
                                        <input class="form-control" type="password" required="" placeholder="Password" name="password">
                                        <i class="md md-vpn-key form-control-feedback l-h-34"></i>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-xs-12">

                                    </div>
                                </div>

                                <div class="form-group text-center m-t-20">
                                    <div class="col-xs-12">
                                        <button class="btn btn-primary btn-custom w-md waves-effect waves-light" type="submit" onclick="formhash(this.form, this.form.password);">Log In
                                        </button>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="row">
                                <div class="col-sm-offset-5 col-sm-2"  style="padding: 30px">
                                    <img src="../assets/images/2031.png" width="100%">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 text-center">
                            <h3>SISTEM INFORMASI BINA MARGA KALIMANTAN UTARA</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        
        <script>
            var resizefunc = [];
        </script>

        <!-- Main  -->
        <script src="../assets/js/jquery.min.js"></script>
        <script src="../assets/js/bootstrap.min.js"></script>
        <script src="../assets/js/detect.js"></script>
        <script src="../assets/js/fastclick.js"></script>
        <script src="../assets/js/jquery.slimscroll.js"></script>
        <script src="../assets/js/jquery.blockUI.js"></script>
        <script src="../assets/js/waves.js"></script>
        <script src="../assets/js/wow.min.js"></script>
        <script src="../assets/js/jquery.nicescroll.js"></script>
        <script src="../assets/js/jquery.scrollTo.min.js"></script>
        <script src="../plugins/switchery/switchery.min.js"></script>

        <!-- Custom main Js -->
        <script src="../assets/js/jquery.core.js"></script>
        <script src="../assets/js/jquery.app.js"></script>
    
    </body>
</html>

<?php else:
        header("Location: /dash/");
endif;
?>
        

</body>
</html>

