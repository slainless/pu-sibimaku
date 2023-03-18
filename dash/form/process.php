<?php
// ################################################### //
// INCLUDE/REQUIRE START

require_once "../dir-conf.php";

require_once $access_main;
require_once $func_login;

require '../function.php';

// INCLUDE/REQUIRE END
// ################################################### //
// SESSION CHECK START

$session = new session();
$session->init();
// level check
if(!isset($_SESSION['level'])):
	errCode("404", "Page not found");
endif;


$level = new levelCheck(($_SESSION['level']));
// session var setter
if($level->minCheck(1)):

    $s_id = $_SESSION['user_id'];
    $s_username = $_SESSION['username'];
    $s_name = $_SESSION['name'];
    $s_login_string = $_SESSION['login_string'];
    $s_level = $_SESSION['level'];
    $s_status = $_SESSION['status'];
    $s_rel_id = $_SESSION['rel_id'];

else:
	errCode("SB002");
endif;

// SESSION CHECK END
// ################################################### //
// MAIN CODE HERE


// --------------------------------------------------- //
//  SUBCODE : SECURITY CHECK

// min level 3 / pptk
if($s_level < 3) {
	errCode("404", "Page not found");
}

// POST variable checker
if(!isset($_POST['status'], $_POST['device'], $_POST['device_statistic'], $_POST['modal_signature'], $_POST['data_signature'])) {
	errCode("EC004", "Lagi ngapain disini? tersesat ya mas/mbak? (S001)", true);
}
	// ----- SEC var check ------ //
	// status = id
	// device = main check
	// device_stat = mode
	// modal_sign = dummy
	// data_sign = dummy
	// 
	// whitelist for status
	
$whitelist_mode = array('4a', 'b2', '0b', '91');

if(codeCrypt($_POST['device']) != '18' && codeCrypt($_POST['device']) != 'cf'){
	errCode("EC004", "Lagi ngapain disini? tersesat ya mas/mbak? (S002)", true);
}

if(!in_array(codeCrypt($_POST['device_statistic']), $whitelist_mode)){
	errCode("EC004", "Lagi ngapain disini? tersesat ya mas/mbak? (S003)", true);
}
else {
	$code['mode'] = codeCrypt($_POST['device_statistic']);
}

// ----- MAIN var check ------ //

switch($code['mode']):
	case '4a':
		if(!isset($_POST['title'])){
			errCode("EC004", "Lagi ngapain disini? tersesat ya mas/mbak? (S004)", true);
		}

		$var['title'] = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_FLAG_ENCODE_HIGH);
		$var['year'] = filter_input(INPUT_POST, 'year', FILTER_SANITIZE_NUMBER_INT);


		if($var['title'] == "" || empty($var['year']) || strlen($var['year']) != 4){
			errCode("EC004", "Lagi ngapain disini? tersesat ya mas/mbak? (S005)", true);
		}
	break;

	case 'b2':
		if(!isset($_POST['title'], $_POST['status'])){
			errCode("EC004", "Lagi ngapain disini? tersesat ya mas/mbak? (S006)", true);
		}
		
		$var['title'] = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_FLAG_ENCODE_HIGH);
		$var['id'] = codeCrypt($_POST['status'], true);

		if(empty($var['title']) || !$var['id']){
			errCode("EC004", "Lagi ngapain disini? tersesat ya mas/mbak? (S007)", true);
		}
	break;

	case '0b':
		if(!isset($_POST['title'], $_POST['data_0'], $_POST['data_1'])){
			errCode("EC004", "Lagi ngapain disini? tersesat ya mas/mbak? (S008)", true);
		}
		
		$var['title'] = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_FLAG_ENCODE_HIGH);
		$var['title'] = '"'.$var['title'].'",""';
		$var['pptk'] = filter_input(INPUT_POST, 'data_1', FILTER_SANITIZE_NUMBER_INT);
		$var['pagu'] = filter_input(INPUT_POST, 'data_0', FILTER_SANITIZE_NUMBER_INT);
		$var['id_cat'] = codeCrypt($_POST['data_signature'], true);

		if(empty($var['title']) || empty($var['pptk']) || empty($var['pagu']) || !$var['id_cat']){
			errCode("EC004", "Lagi ngapain disini? tersesat ya mas/mbak? (S009)", true);
		}

	break;

	case '91':
		if(!isset($_POST['title'], $_POST['data_0'], $_POST['data_1'])){
			errCode("EC004", "Lagi ngapain disini? tersesat ya mas/mbak? (S010)", true);
		}
		
		$var['title'] = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_FLAG_ENCODE_HIGH);
		$var['id'] = codeCrypt($_POST['status'], true);
		$var['pptk'] = filter_input(INPUT_POST, 'data_1', FILTER_SANITIZE_NUMBER_INT);
		$var['pagu'] = filter_input(INPUT_POST, 'data_0', FILTER_SANITIZE_NUMBER_INT);

		if(empty($var['title']) || !$var['id'] || empty($var['pptk']) || empty($var['pagu'])){
			errCode("EC004", "Lagi ngapain disini? tersesat ya mas/mbak? (S011)", true);
		}

	break;

	default:
		// default
	break;

endswitch;

//  SUBCODE END
// --------------------------------------------------- //
//  SUBCODE : FETCHING TABLE

// debug only
// var_dump($var);

$exec = new dbExec($query);

switch($code['mode']):
	case '4a':

		$param['table'] = $tbl_cat;
		$param['field'] = array(
			array('name' => 'title', 'type' => 's', 'value' => $var['title']),
			array('name' => 'tahun', 'type' => 's', 'value' => $var['year'])
		);

		$result = $exec->insert($param);
		unset($param);

		if($result){
			$send['status'] = 'true';
			$send['message'] = 'Berhasil menambah Kegiatan';
		}
		else {
			$send['status'] = 'false';
		}
		echo json_encode($send);

	break;

	case 'b2':

		$param['table'] = $tbl_cat;
		$param['field'] = array('name' => 'title', 'type' => 's', 'value' => $var['title']);
		$param['where'] = array('name' => 'id', 'type' => 'i', 'value' => $var['id']);

		$result = $exec->update($param);
		unset($param);

		if($result){
			$send['status'] = 'true';
			$send['message'] = 'Berhasil mengubah Kegiatan';
		}
		else {
			$send['status'] = 'false';
		}
		echo json_encode($send);

	break;

	case '0b':

		$param['table'] = $tbl_mem;
		$param['field'] = array('name' => 'id', 'result' => 'id');
		$param['where'] = array('name' => 'id', 'value' => $var['pptk'], 'type' => 'i');

		if(!$exec->select($param)){
			errCode("EC004", "Lagi ngapain disini? tersesat ya mas/mbak? (S012)", true);
		}
		unset($param);

		$param['table'] = $tbl_cat;
		$param['field'] = array('name' => 'id', 'result' => 'id');
		$param['where'] = array('name' => 'id', 'value' => $var['id_cat'], 'type' => 'i');

		if(!$exec->select($param)){
			errCode("EC004", "Lagi ngapain disini? tersesat ya mas/mbak? (S013)", true);
		}
		unset($param);

		$param['table'] = $tbl_dash;
		$param['field'] = array(
			array('name' => 'dp_info', 'type' => 's', 'value' => $var['title']),
			array('name' => 'n_pagu', 'type' => 's', 'value' => $var['pagu']),
			array('name' => 'lead_id', 'type' => 'i', 'value' => $var['pptk']),
			array('name' => 'kategori', 'type' => 'i', 'value' => $var['id_cat']),
		);

		$result = $exec->insert($param);
		unset($param);

		if($result){
			$send['status'] = 'true';
			$send['message'] = 'Berhasil menambah Pekerjaan';
		}
		else {
			$send['status'] = 'false';
		}
		echo json_encode($send);

	break;

	case '91':

		$param['table'] = $tbl_mem;
		$param['field'] = array('name' => 'id', 'result' => 'id');
		$param['where'] = array('name' => 'id', 'value' => $var['pptk'], 'type' => 'i');

		if(!$exec->select($param)){
			errCode("EC004", "Lagi ngapain disini? tersesat ya mas/mbak? (S014)", true);
		}
		unset($param);

		$param['table'] = $tbl_dash;
		$param['field'] = array(
			array('name' => 'dp_info', 'result' => 'dp_info'),
			array('name' => 'lead_id', 'result' => 'pptk'),
			array('name' => 'n_pagu', 'result' => 'pagu'),
		);
		$param['where'] = array('name' => 'id', 'value' => $var['id'], 'type' => 'i');

		if(!$temp = $exec->select($param)){
			errCode("EC004", "Lagi ngapain disini? tersesat ya mas/mbak? (S015)", true);
		}
		unset($param);

		$temp['dp_info'] = cta($temp['dp_info']);

		if($var['title'] == $temp['dp_info'][0] && $var['pptk'] == $temp['pptk'] && $var['pagu'] == $temp['pagu']) {
			$send['status'] = 'true';
			$send['message'] = 'Berhasil mengubah Pekerjaan';
			echo json_encode($send);
			exit();
		}

		$temp['dp_info'][0] = $var['title'];
		$var['title'] = atc($temp['dp_info']);


		$param['table'] = $tbl_dash;
		$param['field'] = array(
			array('name' => 'dp_info', 'type' => 's', 'value' => $var['title']),
			array('name' => 'n_pagu', 'type' => 's', 'value' => $var['pagu']),
			array('name' => 'lead_id', 'type' => 'i', 'value' => $var['pptk']),
		);
		$param['where'] = array('name' => 'id', 'type' => 'i', 'value' => $var['id']);

		$result = $exec->update($param);
		unset($param);

		if($result){
			$send['status'] = 'true';
			$send['message'] = 'Berhasil mengubah Pekerjaan';
		}
		else {
			$send['status'] = 'false';
		}
		echo json_encode($send);

	break;

			
endswitch;

//  SUBCODE END
// --------------------------------------------------- //

// MAIN CODE END
// ################################################### //
?>