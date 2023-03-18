<?php
require_once "../dir-conf.php";

require_once $access_main;
require_once $func_login;

require '../function.php';

$session = new session();
$session->start();

if(isset($_SESSION['level']) && $_SESSION['level'] > 1){

    $s_id = $_SESSION['user_id'];
    $s_username = $_SESSION['username'];
    $s_name = $_SESSION['name'];
    $s_login_string = $_SESSION['login_string'];
    $s_level = $_SESSION['level'];
    $s_status = $_SESSION['status'];
    $s_rel_id = $_SESSION['rel_id'];

}

$exec = new dbExec($query);

$debug = 0;
$token = 1;
$terminate = 0;

if($_SESSION['level'] < 2) {
	errCode("EC001", "000");
}

// basic post check
if(!isset($_POST['mode'], $_POST['primary'])){
	errCode("EC004", "001");
}

// basic var setter
$code['primary'] = codeCrypt($_POST['primary']);
$code['mode'] = codeCrypt($_POST['mode']);

// var checker
if($code['primary'] != 'cd'){
	errCode("EC004", "002");
}

// 001
if($debug) var_dump($code);
if($terminate === 1) { 
	var_dump($code); 
	exit();
}

switch ($code['mode']) {
	case 'e7': case 'b6':

		if(!isset($_POST['id'], $_POST['action'], $_POST['token'])){
			errCode("EC004", "003");
		}

		$code['id'] = codeCrypt($_POST['id'], true);
		$code['action'] = codeCrypt($_POST['action']);

		 // token check
		 if($token):
			if($_SESSION['req_token'] === $_POST['token']){
				$_SESSION['req_token'] = bin2hex(random_bytes(random_int(1, 5)));
				
			}
			else {
				$_SESSION['req_token'] = bin2hex(random_bytes(random_int(1, 5)));
				errCode("EC005", "004");
			}
		endif;
		session_regenerate_id();


		// 002
		if($debug) var_dump($_SESSION['req_token']);
		if($terminate === 2) { 
			var_dump($_SESSION['req_token']); 
			exit();
		}

	break;
	
	case '1e': case '52':

		if(!isset($_POST['id'])){
			errCode("EC004", "005");
		}

		$code['id'] = codeCrypt($_POST['id'], true);

	break;
}


// mode
// e7 = fetch table
// 52 = new upload
// 1e = edit upload

switch ($code['mode']) {
	case 'e7':

		if(isset($_POST['sort'], $_POST['order'], $_POST['offset'], $_POST['limit'], $_POST['order'])):

			$var['sort'] = filter_input(INPUT_POST, 'sort', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_FLAG_ENCODE_HIGH);
			$var['order'] = filter_input(INPUT_POST, 'order', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_FLAG_ENCODE_HIGH);
			$var['search'] = filter_input(INPUT_POST, 'search', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_FLAG_ENCODE_HIGH);
			$var['offset'] = filter_input(INPUT_POST, 'offset', FILTER_SANITIZE_NUMBER_INT);
			$var['limit'] = filter_input(INPUT_POST, 'limit', FILTER_SANITIZE_NUMBER_INT);

            $sort_wl = array('tag', 'format', 'title', 'status', 'time');
            if(!in_array($var['sort'], $sort_wl)) errCode("EC004", "006");
            if($var['order'] != 'asc' && $var['order'] != 'desc') errCode("EC004", "007");

            if(isset($var['search']) && !empty($var['search'])) {
				$stmtq_attach = " AND name LIKE ?";
			}
			else {
				$stmtq_attach = "";
			}

			$stmtq = "SELECT id, name, link, tag, substring_index(link, '.', -1) as format, status, time_mod, time, revisi from ".$tbl_docs." where rel_id = ? ".$stmtq_attach." ORDER BY ".$var['sort']." ".$var['order']." LIMIT ? OFFSET ?";

		
			$param['result'] = array('id', 'name', 'link', 'tag', 'format', 'status', 'time_mod', 'time', 'revisi');
			if(isset($var['search']) && !empty($var['search'])) {
				$param['param']['type'] = "isii";
				$param['param']['value'] = array($code['id'], "%".$var['search']."%", $var['limit'], $var['offset']);
			}
			else {
				$param['param']['type'] = "iii";
				$param['param']['value'] = array($code['id'], $var['limit'], $var['offset']);
			}

			$param['option']['force_array'] = true;
			$param['option']['transpose'] = true;

			$result = $exec->freeQuery($stmtq, $param);
			unset($param);

			if($debug) var_dump($result);

			if(!$result):

				$json['total'] = 0;
				$json['token'] = $_SESSION['req_token'];

				echo json_encode($json);

			else:

				$json['total'] = 0;
				foreach ($result as $key => $value) {

					$json['total']++;

					$z = explode("/", $value['link']);
		        	$zx = count($z);

		        	$value['link'] = $abs_dir_upload.$z[$zx-2].'/'.$z[$zx-1];

		        	switch ($value['tag']) {

		        		case '0':
		        			unset($value['tag']);
		        			$value['tag']['text'] = 'Lain';
		        			$value['tag']['type'] = 'inverse';

		        		break;

		        		case '1':
		        			unset($value['tag']);
		        			$value['tag']['text'] = 'Harian';
		        			$value['tag']['type'] = 'warning';
		        		break;

		        		case '2':
		        			unset($value['tag']);
		        			$value['tag']['text'] = 'Bulanan';
		        			$value['tag']['type'] = 'info';
		        		break;

		        		case '3':
		        			unset($value['tag']);
		        			$value['tag']['text'] = 'Tahunan';
		        			$value['tag']['type'] = 'purple';
		        		break;
		        		
		        		default:
		        			# code...
		        		break;
		        	}

		        	switch ($value['status']) {
		        		case '0':
		        			unset($value['status']);
		        			$value['status']['text'] = '<i class="md md-lock-outline" style="font-size: 1.4em; line-height: 1em"></i>';
		        			$value['status']['type'] = 'danger';
		        			$value['unlock'] = 0;
		        			$value['rev'] = "<i class='md md-radio-button-off'></i>";
		        		break;

		        		case '1':
		        			unset($value['status']);
		        			$value['status']['text'] = '<i class="md md-lock-outline" style="font-size: 1.4em; line-height: 1em"></i>';
		        			$value['status']['type'] = 'success';
		        			$value['unlock'] = 1;
		        			$value['rev'] = "<i class='md md-radio-button-on'></i>";
		        		break;
		        		
		        		default:
		        			# code...
		        			break;
		        	}

		        	switch ($value['format']) {
		        		case 'docx':
		        			unset($value['format']);
		        			$value['format']['text'] = 'DOCX';
		        			$value['format']['type'] = 'label-info"';
		        		break;

		        		case 'pdf':
		        			unset($value['format']);
		        			$value['format']['text'] = 'PDF';
		        			$value['format']['type'] = 'label-danger"';
		        		break;

		        		case 'xls': 
		        			unset($value['format']);
		        			$value['format']['text'] = 'XLS';
		        			$value['format']['type'] = 'label-success"';
		        		break;

		        		case 'xlsx':
		        			unset($value['format']);
		        			$value['format']['text'] = 'XLSX';
		        			$value['format']['type'] = 'label-success"';
		        		break;
		        		
		        		default:
		        			# code...
		        			break;
		        	}

		        	$value['time_mod'] = interval($value['time_mod']);

		        	if(checkId($code['id'], $_SESSION, $tbl_dash, $query)) $value['attach'] = '<a class="m-r-5"><i class="md md-edit" style="font-size: 1.4em; line-height: 1em"></i></a>';
		        	else $value['attach'] = '';

					$json['rows'][$key]['title'] = '<a href="'.$value['link'].'" target="_blank">'.$value['name'].'</a>';
					$json['rows'][$key]['status'] = '<span class="text-'.$value['status']['type'].' m-r-5">'.$value['status']['text'].'</span>'.$value['attach'].'<a href="'.$value['link'].'" target="_blank"><i class="md md-cloud-download" style="font-size: 1.4em; line-height: 1em"></i></a>';
					$json['rows'][$key]['tag'] = '<span style="width: 100%; display: block; padding: 6px;" class="label label-'.$value['tag']['type'].'">'.$value['tag']['text'].'</span>';
					$json['rows'][$key]['time'] = $value['time_mod'];
					$json['rows'][$key]['unlock'] = $value['unlock'];
					$json['rows'][$key]['rev'] = $value['rev'];
					$json['rows'][$key]['format'] = '<span style="width: 100%; display: block; padding: 6px" class="label '.$value['format']['type'].'>'.$value['format']['text'].'</span>';

				}

				$json['token'] = $_SESSION['req_token'];

				echo json_encode($json);

			endif;

		endif;

	break;

	case '52':
		if(!checkId($code['id'], $_SESSION, $tbl_dash, $query)){
			errCode("EC004", "008");
		}
		require 'fetcher/t01.php';
	break;

	case '1e':
		$param = array(
			'field' => array('name' => 'rel_id', 'result' => 'rel_id'), 
			'table' => $tbl_docs, 
			'where' => array('name' => 'id', 'type' => 'i', 'value' => $code['id'])
		);
		$check = $exec->select($param);
		if(!checkId($check['rel_id'], $_SESSION, $tbl_dash, $query)){
			errCode("EC004", "009");
		}
		require 'fetcher/t02.php';
	break;

	case 'b6':

		if(isset($_FILES['file'], $_POST['mime'], $_POST['title'], $_POST['tag'])):

			$max_size = 20000000;

			if(!checkId($code['id'], $_SESSION, $tbl_dash, $query)){
				errCode("EC004", "006");
			}

			// ----------------------------------------------- //

			$var['tag'] = filter_input(INPUT_POST, 'tag', FILTER_SANITIZE_NUMBER_INT);
			if($var['tag'] > 10 || $var['tag'] < 0 || empty($var['tag'])){
				$var['tag'] = 0;
			}

			$var['name'] = $_POST['title'];

			$var['name'] = mb_ereg_replace("([^\w\s\d\-_~,;\[\]\(\).])", '', $var['name']);
			$var['name'] = mb_ereg_replace("([\.]{2,})", '', $var['name']);

			// mime whitelist
			$temp = array('docx', 'doc', 'pdf', 'xls', 'xlsx');
			if(!in_array($_POST['mime'], $temp)){
				swalAjax("Error", "Ekstensi file tidak diizinkan");
			}
			$var['mime'] = $_POST['mime'];

			if(strlen($var['name']) > 40) {
				substr($var['name'], 0, 40);
			}

			unset($temp);
			$now = date("Gis");
		    $temp = bin2hex(random_bytes(random_int(1, 2)));
		    $now = dechex($now);
			$var['path'] = substr($var['name'], 0, 15)."~".$now;

			if (!is_dir($dir_upload.$code['id'])) {
			    mkdir($dir_upload.$code['id'], 0777, true);
			}

			$var['path'] = $dir_upload.$code['id']."/".$var['path'].".".$var['mime'];

			if($_FILES['file']['error'] !== 0){
				swalAjax("Error", "Gagal mengupload File! (ERR: ".$_FILES['file']['error'].")");
			}

			if ($_FILES['file']['size'] > $max_size) {
				swalAjax("Error", "File melewati batas maksimal!");
			}

		    if(!move_uploaded_file($_FILES['file']['tmp_name'], $var['path'])) {
				swalAjax("Error", "Gagal mengupload File! Sistem sedang gangguan, silahkan melapor ke Admin (ERR: FN)");
		    }

		    unset($temp);

		    date_default_timezone_set("Asia/Makassar");
		    $param['table'] = $tbl_docs;
		    $param['field'] = array(
		    	array('name' => 'rel_id', 'value' => $code['id'], 'type' => 'i'),
		    	array('name' => 'status', 'value' => 0, 'type' => 'i'),
		    	array('name' => 'name', 'value' => $var['name'], 'type' => 's'),
		    	array('name' => 'link', 'value' => $var['path'], 'type' => 's'),
		    	array('name' => 'tag', 'value' => $var['tag'], 'type' => 'i'),
		    	array('name' => 'revisi', 'value' => 0, 'type' => 'i'),
		    	array('name' => 'time', 'value' => date("Y-m-d H:i:s"), 'type' => 's'),
		    	array('name' => 'time_mod', 'value' => date("Y-m-d H:i:s"), 'type' => 's')
		    );

		    if(!$exec->insert($param)){
				unlink($var['path']);
		    	swalAjax("Error", "Gagal mengupload File! Sistem sedang gangguan, silahkan melapor ke Admin (ERR: DB)");
		    }
		    else {
		    	swalAjax("Berhasil mengupload File", "", "success");
		    	exit();
		    };

		else:
			swalAjax("Error", "Gagal mengupload File! (ERR: DATA)");
		endif;

	break;
	
	default:
		# code...
		break;
}
