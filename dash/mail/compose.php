<?php

require $dir_html."side/template.php";

$template = new template;

$exec = new dbExec($query);

$bc = array(
	array("","Dashboard","1"),
	array("","Mail","1"),
);
$title = "Mailbox";
$page_title = "SIBIMA-KU | Mailbox";

$pagejs = "d2-display.init.js";
$session = array(
	'name' => $s_name,
	'level' => $s_level,
);
$template->init($bc, $title, $page_title, $session);
$template->pagejs($pagejs);

$template->call('include');
$template->call('topbar');
$template->call('navbar');
$template->call('leftbar');
?>

                        <div class="row">

                                        <div class="card-box m-t-20">
                                            <div class="p-20">
                                                <form role="form">
                                                    <div class="form-group">
                                                        <div class="row">
                                                            <div class="col-lg-9 col-md-8">
                                                                <input type="email" class="form-control m-t-5" placeholder="Cc">
                                                            </div>
                                                            <div class="col-lg-3 col-md-4">
                                                                <div class="btn-toolbar m-t-5" role="toolbar">
                                                                    
                                                                    <div class="btn-group">
                                                                        <button type="button" class="btn btn-primary waves-effect waves-light "><i class="fa fa-inbox"></i></button>
                                                                        <button type="button" class="btn btn-primary waves-effect waves-light "><i class="fa fa-exclamation-circle"></i></button>
                                                                        <button type="button" class="btn btn-primary waves-effect waves-light "><i class="fa fa-trash-o"></i></button>
                                                                    </div>
                                                                    <div class="btn-group pull-right">
                                                                        <a href="?d=mail&compose" class="btn btn-danger waves-effect waves-light">Compose</a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="summernote">
                                                            <h6>Hello Summernote</h6>
                                                            <ul>
                                                                <li>
                                                                    Select a text to reveal the toolbar.
                                                                </li>
                                                                <li>
                                                                    Edit rich document on-the-fly, so elastic!
                                                                </li>
                                                            </ul>
                                                            <p>
                                                                End of air-mode area
                                                            </p>

                                                        </div>
                                                    </div>

                                                    <div class="btn-toolbar form-group m-b-0">
                                                        <div class="pull-right">
                                                            <button type="button" class="btn btn-success waves-effect waves-light m-r-5"><i class="fa fa-floppy-o"></i></button>
                                                            <button type="button" class="btn btn-success waves-effect waves-light m-r-5"><i class="fa fa-trash-o"></i></button>
                                                            <button class="btn btn-purple waves-effect waves-light"> <span>Send</span> <i class="fa fa-send m-l-10"></i> </button>
                                                        </div>
                                                    </div>

                                                </form>
                                            </div>
                                        </div>



                            </div> <!-- end Col-9 -->

                        </div><!-- End row -->


<?php
$template->call('footer');
$template->call('includejs');
$template->call('endfile');