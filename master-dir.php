<?php
$prefix = "prokal";
$master_root = $_SERVER["DOCUMENT_ROOT"];

$master_root = $master_root."/".$prefix;
$dir_access = $master_root."/access/";
$dir_login = $master_root."/login/";
$dir_html = $master_root."/html/";
$dir_upload = $master_root."/drive/";

$abs = "/".$prefix;

$abs_dir_upload = $abs."/drive/";

/* ACCESS */
$access_main = $dir_access."db-main.php";

/* FUNCTION */
$func_login = $dir_login."function-login.php";