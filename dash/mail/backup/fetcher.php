<?php
require_once "../dir-conf.php";

require_once $access_main;
require_once $func_login;

$session = new session();
$session->init();

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

else:
	return false;
endif;

// STATISTIC = STATUS = DATA = ID PROYEK
// ID = TYPE EDIT
if(isset($_POST["id"], $_POST["status"], $_POST["statistic"], $_POST["data"], $_POST["id_0"]) && $s_level > 2):
	$post[] = codeCrypt($_POST["statistic"]);
	$post[] = codeCrypt($_POST["status"]);
	$post[] = codeCrypt($_POST["data"]);

	$get_id = codeCrypt($_POST["id"], 1);

	if($post[1] != $post[0] || $post[0] != $post[2]){
		return false;
	}

	require_once '../function.php';

	$dash = 'dash';
	$cat = 'cat';
	$mem = 'member';
	$mail = 'mail';

	$exec = new dbExec($query);

	if($s_level == 3)
		require 'fetcher/t01.php';
	elseif($s_level == 4)
		require 'fetcher/t02.php';

endif;
?>