<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <meta name="description" content="A fully featured admin theme which can be used to build CRM, CMS, etc.">
        <meta name="author" content="Coderthemes">

        <link rel="shortcut icon" href="/prokal/assets/images/favicon_1.ico">

        <title>SIBIMA-KU | Error Code: <?php echo substr($errorCode,-3,3); ?></title>

        <link href="/prokal/assets/css/side/bootstrap.min.css" rel="stylesheet" type="text/css">
        <link href="/prokal/assets/css/side/core.css" rel="stylesheet" type="text/css">
        <link href="/prokal/assets/css/side/icons.css" rel="stylesheet" type="text/css">
        <link href="/prokal/assets/css/side/components.css" rel="stylesheet" type="text/css">
        <link href="/prokal/assets/css/side/pages.css" rel="stylesheet" type="text/css">
        <link href="/prokal/assets/css/side/menu.css" rel="stylesheet" type="text/css">
        <link href="/prokal/assets/css/side/responsive.css" rel="stylesheet" type="text/css">

        <script src="/prokal/assets/js/modernizr.min.js"></script>

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->

        
    </head>
    <body>


        <div class="ex-page-content">
            <div class="container">
                <div class="row">
                    <div class="col-sm-6">
                        <svg class="svg-box" width="380px" height="500px" viewBox="0 0 837 1045" version="1.1"
                             xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                             xmlns:sketch="http://www.bohemiancoding.com/sketch/ns">
                            <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"
                               sketch:type="MSPage">
                                <path d="M353,9 L626.664028,170 L626.664028,487 L353,642 L79.3359724,487 L79.3359724,170 L353,9 Z"
                                      id="Polygon-1" stroke="#3bafda" stroke-width="6" sketch:type="MSShapeGroup"></path>
                                <path d="M78.5,529 L147,569.186414 L147,648.311216 L78.5,687 L10,648.311216 L10,569.186414 L78.5,529 Z"
                                      id="Polygon-2" stroke="#7266ba" stroke-width="6" sketch:type="MSShapeGroup"></path>
                                <path d="M773,186 L827,217.538705 L827,279.636651 L773,310 L719,279.636651 L719,217.538705 L773,186 Z"
                                      id="Polygon-3" stroke="#f76397" stroke-width="6" sketch:type="MSShapeGroup"></path>
                                <path d="M639,529 L773,607.846761 L773,763.091627 L639,839 L505,763.091627 L505,607.846761 L639,529 Z"
                                      id="Polygon-4" stroke="#00b19d" stroke-width="6" sketch:type="MSShapeGroup"></path>
                                <path d="M281,801 L383,861.025276 L383,979.21169 L281,1037 L179,979.21169 L179,861.025276 L281,801 Z"
                                      id="Polygon-5" stroke="#ffaa00" stroke-width="6" sketch:type="MSShapeGroup"></path>
                            </g>
                        </svg>
                    </div>

                    <div class="col-sm-6">
                        <div class="message-box">
                            <h1 class="m-b-0"><?php echo $errorCode; ?></h1>
                            <p><?php echo $reason; ?></p>
                            <?php if(!$nobutton): ?>
                            <div class="buttons-con">
                                <div class="action-link-wrap">
                                    <a onclick="history.back(-1)" class="btn btn-custom btn-primary waves-effect waves-light m-t-20">Go Back</a>
                                    <a href="/prokal/dash/" class="btn btn-custom btn-primary waves-effect waves-light m-t-20">Go to Home Page</a>
                                </div>
                            </div>
                            <?php endif; 

                            if($logout): ?>
                                <a href="/prokal/login/logout.php" class="btn btn-custom btn-primary waves-effect waves-light m-t-20">Logout</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        
    	<script>
            var resizefunc = [];
        </script>

        <!-- Main  -->
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

        <!-- Custom main Js -->
        <script src="/prokal/assets/js/jquery.core.js"></script>
        <script src="/prokal/assets/js/jquery.app.js"></script>
	
	</body>
</html>