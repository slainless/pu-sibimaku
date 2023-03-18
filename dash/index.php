<?php
//

require_once "dir-conf.php";

require_once $access_main;
require_once $func_login;

$session = new session();
$session->start();

$level = new levelCheck(($_SESSION['level']));
//
if($level->minCheck(1)):

    $s_id = $_SESSION['user_id'];
    $s_username = $_SESSION['username'];
    $s_name = $_SESSION['name'];
    $s_login_string = $_SESSION['login_string'];
    $s_level = $_SESSION['level'];
    $s_status = $_SESSION['status'];
    $s_rel_id = $_SESSION['rel_id'];
    $s_token = $_SESSION['req_token'];

//    $require = $level->checkReq(2, 'inbox/main.php', 'submit/main.php');
//    require $require;

    require 'redirect.php';

else: 
    $url = urlc("login", $tlimit); 
    header('Location: '.$url);
    exit();
endif;
//
mysqli_close($query);