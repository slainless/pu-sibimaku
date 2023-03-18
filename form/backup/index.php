<?php
//
require_once "dir-conf.php";

require_once $access_main;
require_once $func_login;

$session = new session();

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

    $require = $level->checkReq(2, 'inbox/main.php', 'submit/main.php');
    require $require;

else: 
    $url = urlc("login", $tlimit); 
    header('Location: '.$url);
    exit();
endif;
//
echo "<br><br><a href='../login/logout.php'>LOGOUT</a>";
mysqli_close($query);