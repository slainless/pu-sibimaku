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

if(isset($_POST["send"], $_POST["device"], $_POST["id"], $_POST["device_statistic"], $_POST['status']) && codeCrypt($_POST["device"]) == '73' && (codeCrypt($_POST["device_statistic"]) == '47' || codeCrypt($_POST["device_statistic"]) == '61')):

	$get_id = codeCrypt($_POST["status"], 1);
	$send = filter_input(INPUT_POST, 'send', FILTER_SANITIZE_NUMBER_INT);

	require_once '../function.php';

	$dash = 'dash';
	$cat = 'cat';
	$mem = 'member';
	$mail = 'mail';

	$exec = new dbExec($query);

	$param['table'] = $dash;
	$param['field'] = array('name' => 'rel_id', 'result' => 'rel_id');
	$param['where'] = array('name' => 'id', 'type' => 'i', 'value' => $get_id);

	$checkDash = $exec->select($param);
	unset($param);

	$param['table'] = $mail;
	$param['field'] = array(
		array('name' => 'status', 'result' => 'status'),
		array('name' => 'attach', 'result' => 'attach'),
	);
	$param['where'] = array('name' => 'rel_id', 'type' => 'i', 'value' => $get_id);

	$check = $exec->select($param);
	unset($param);

	if(!$check){
		errCode("EC004", "Lagi ngapain disini? tersesat ya mas/mbak?", true);		
	}

	if($s_level == 2 && $s_id == $checkDash['rel_id']){

	}
	elseif($s_level > 3){
		// debug
	}
	else {
		errCode("EC004", "Lagi ngapain disini? tersesat ya mas/mbak?", true);
	}

	// $check['status'] = 2; // debug
	if($check['status'] !== 2){
		errCode("EC004", "Lagi ngapain disini? tersesat ya mas/mbak?", true);		
	}

	$check['attach'] = cta($check['attach']);
	$temp['unlink'] = $check['attach'][$send];
	unset($check['attach'][$send]);

	$check['attach'] = array_values($check['attach']);
	$temp['attach'] = atc($check['attach']);

	$param['table'] = $mail;
	$param['field'] = array('name' => 'attach', 'value' => $temp['attach'], 'type' => 's');
	$param['where'] = array('name' => 'rel_id', 'value' => $get_id, 'type' => 'i');

	if($exec->update($param)){
		if(unlink($temp['unlink'])){
			echo "true";
		}
		else {
			echo "false";
		}
	}
	else {
		echo "false";
	}
	exit();

endif;
// STATISTIC = STATUS = DATA = ID PROYEK
// ID = TYPE EDIT
if(isset($_POST["id"], $_POST["status"], $_POST["statistic"], $_POST["data"])):
	$post[] = codeCrypt($_POST["statistic"], 1);
	$post[] = codeCrypt($_POST["status"], 1);
	$post[] = codeCrypt($_POST["data"], 1);

	$type = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);

	if($post[1] != $post[0] || $post[0] != $post[2]){
		return false;
	}

	require_once '../function.php';

	$dash = 'dash';
	$cat = 'cat';
	$mem = 'member';
	$mail = 'mail';

	$exec = new dbExec($query);

	switch ($type) {
		case 1:
			require 'fetcher/t01.php';
			break;

		case 2:
			require 'fetcher/t02.php';
			break;

		case 3:
			require 'fetcher/t03.php';
			break;

		case 4:
			require 'fetcher/t04.php';
			break;

		case 5:
			require 'fetcher/t05.php';
			break;

		case 6:
			require 'fetcher/t06.php';
			break;
		
		default:
			# code...
			break;
	}

endif;
?>