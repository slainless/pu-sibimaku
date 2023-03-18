<?php
// ################################################### //
// INCLUDE/REQUIRE START

require_once $_PRIVATE['root']."dash/dir-conf.php";

require_once $access_main;
require_once $func_login;

require $_PRIVATE['root']."dash/function-dash.php";

// INCLUDE/REQUIRE END
// ################################################### //
// SESSION CHECK START
// 
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

if(!isset($_SESSION['level'])):
	errCode("404", "Page not found");
endif;

// SESSION CHECK END
// ################################################### //
// MAIN CODE HERE


// --------------------------------------------------- //
//  SUBCODE : SECURITY CHECK

// min level 2 / konsultan
// 
$exec = new dbExec($query);

$debug = 0;
$token = 0;
$terminate = 0;

if($_SESSION['level'] < 6) {
	errCode("EC001", "000");
}

// basic post check
if(!isset($_POST['mode'], $_POST['primary'])){
	errCode("EC004", "001");
}

// basic var setter
$code['primary'] = codeCrypt($_POST['primary']);
$code['mode'] = codeCrypt($_POST['mode']);

$sort_wl = array('f5', '41', '75', '4c', '8b', 'e2');

// var checker
if($code['primary'] != 'bf')
	errCode("EC004", "002");

if(!in_array($code['mode'], $sort_wl)) 
	errCode("EC004", "002a");

// 001
if($debug) var_dump($code);
if($terminate === 1) { 
	var_dump($code); 
	exit();
}

// ----- MAIN var check ------ //

switch ($code['mode']):

	case 'f5': case '41': case '75':
	case '4c': case '8b': case 'e2':

		if(!isset($_POST['token']))
			errCode("EC004", "003");

		// token check
		if($token):
			if($_SESSION['req_token'] === $_POST['token']){
				$_SESSION['req_token'] = tokenGen();
				
			}
			else {
				$_SESSION['req_token'] = tokenGen();
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

endswitch;

switch ($code['mode']):

	case '4c':
		if(!isset($_POST['data']))
			errCode("EC004", "004a");

		$code['id'] = codeCrypt($_POST['data'], true);
	break;

	case '75': // proses new
		if(!isset($_POST['user'], $_POST['name'], $_POST['pass'], $_POST['level']))
			errCode("EC004", "004b");

		$var['level'] = filter_input(INPUT_POST, 'level', FILTER_SANITIZE_NUMBER_INT);

		if($var['level'] === '3' && !isset($_POST['perusahaan'], $_POST['direktur'], $_POST['bank'], $_POST['rekening'], $_POST['npwp'], $_POST['alamat']))
			errCode("EC004", "004c");

		settype($var['level'], 'int');
		if($var['level'] > 3 && $var['level'] < 8 && !isset($_POST['nip']))
			errCode("EC004", "004c");

		if(
			!preg_match('/^[A-Za-z0-9@#_.]*$/', $_POST['user']) ||
			!preg_match('/^[A-Za-z0-9.,{}()\[\]#@_\-<>\/ ]*$/', $_POST['name']) ||
			($var['level'] === '3' && !preg_match('/^[a-zA-Z0-9.,#@\- ]+$/', $_POST['perusahaan'])) ||
			($var['level'] === '3' && !preg_match('/^[a-zA-Z0-9.,\- ]+$/', $_POST['direktur']))||
			($var['level'] === '3' && !preg_match('/^[a-zA-Z0-9\.\- ]*$/', $_POST['npwp'])) ||
			($var['level'] === '3' && !preg_match('/^[a-zA-Z0-9.,#@\- ]*$/', $_POST['alamat'])) ||
			($var['level'] > 3 && $var['level'] < 8 && !preg_match('/^[0-9.,#@\- ]*$/', $_POST['nip'])) ||
			($var['level'] === '3' && $_POST['bank'] < 0) ||
			($var['level'] === '3' && $_POST['bank'] > 1) ||
			strlen($_POST['pass']) != 128 ||
			empty($var['level']) ||
			$var['level'] > 7 ||
			$var['level'] < 0
		)
			swal('Error', 'Bypass Input! (001)', 'error', '3000', false, true, true);

		$var['name'] = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_FLAG_ENCODE_HIGH);
		$var['perusahaan'] = filter_input(INPUT_POST, 'perusahaan', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_FLAG_ENCODE_HIGH);
		$var['direktur'] = filter_input(INPUT_POST, 'direktur', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_FLAG_ENCODE_HIGH);
		$var['npwp'] = filter_input(INPUT_POST, 'npwp', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_FLAG_ENCODE_HIGH);
		$var['alamat'] = filter_input(INPUT_POST, 'alamat', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_FLAG_ENCODE_HIGH);
		$var['nip'] = filter_input(INPUT_POST, 'nip', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_FLAG_ENCODE_HIGH);
		$var['rekening'] = filter_input(INPUT_POST, 'rekening', FILTER_SANITIZE_NUMBER_INT);
		$var['bank'] = filter_input(INPUT_POST, 'bank', FILTER_SANITIZE_NUMBER_INT);
		$var['user'] = $_POST['user'];
		$var['salt'] = hash('sha512', uniqid(openssl_random_pseudo_bytes(16), TRUE));
		$var['pass'] = hash('sha512', $_POST['pass'] . $var['salt']);

		if(
			empty($var['name']) ||
			empty($var['user'])
		)
			swal('Error', 'Bypass Input! (002)', 'error', '3000', false, true, true);

		$var['sub'] = 0;

		switch ($var['level']):
			case '5': case '2':
				$var['sub'] = 1; break;
			case '1':
				$var['sub'] = 2; break;
		endswitch;

		switch ($var['level']):
			case '1': case '2': case '3': 
				$var['level'] = $var['mask_level'] = 2; break;
			case '4':
				$var['level'] = $var['mask_level'] = 3; break;
			case '5':
				$var['level'] = 3; $var['mask_level'] = 4; break;
			case '6':
				$var['level'] = 3; $var['mask_level'] = 4; break;
			case '7':
				$var['level'] = 3; $var['mask_level'] = 5; break;

		endswitch;
	break;

	case '8b': // proses edit
		if(!isset($_POST['user'], $_POST['name'], $_POST['data']))
			errCode("EC004", "004b");

		$code['id'] = codeCrypt($_POST['data'], true);

		if(isset($_POST['pass'])):

			if(strlen($_POST['pass']) != 128)
				swal('Error', 'Bypass Input! (003)', 'error', '3000', false, true, true);

			$var['salt'] = hash('sha512', uniqid(openssl_random_pseudo_bytes(16), TRUE));
			$var['pass'] = hash('sha512', $_POST['pass'] . $var['salt']);

		endif;

		if(isset($_POST['level'])):

			$var['level'] = filter_input(INPUT_POST, 'level', FILTER_SANITIZE_NUMBER_INT);
			settype($var['level'], 'int');
			if(
				empty($var['level']) ||
				$var['level'] > 7 ||
				$var['level'] < 0
			)
				swal('Error', 'Bypass Input! (004)', 'error', '3000', false, true, true);

		endif;

		$param['table'] = $tbl_dash;
		$param['field'] = array('name' => 'id', 'result' => 'id');
		$param['where_op'] = array('or', 'or', 'or', 'or', 'or', 'or');
		$param['where'] = array(
			array('name' => 'lead_id', 'value' => $code['id'], 'type' => 'i'),
			array('name' => 'rel_id', 'value' => $code['id'], 'type' => 'i'),
			array('name' => 'konsultan', 'value' => $code['id'], 'type' => 'i'),
			array('name' => 'auditor', 'value' => $code['id'], 'type' => 'i'),
			array('name' => 'ppk', 'value' => $code['id'], 'type' => 'i'),
			array('name' => 'kadis', 'value' => $code['id'], 'type' => 'i'),
		);
		$check = $exec->select($param);
		unset($param);

		if($check):
			if(isset($var['level'])) unset($var['level']); $var['message'] = '(* Level tidak berubah. User bertanggung jawab dalam sebuah proyek)';
		else:
			$var['message'] = '';
		endif;


		if(
			!preg_match('/^[A-Za-z0-9@#_.]*$/', $_POST['user']) ||
			!preg_match('/^[A-Za-z0-9.,{}()\[\]#@_\-<>\/ ]*$/', $_POST['name'])
		)
			swal('Error', 'Bypass Input! (005)', 'error', '3000', false, true, true);

		$var['name'] = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_FLAG_ENCODE_HIGH);
		$var['user'] = $_POST['user'];

		if(
			empty($var['name']) ||
			empty($var['user'])
		)
			swal('Error', 'Bypass Input! (006)', 'error', '3000', false, true, true);

		$param['table'] = $tbl_mem;
		$param['field'] = array(
			array('name' => 'f_assign', 'result' => 'assign'),
			array('name' => 'level', 'result' => 'level'),
			array('name' => 'sub', 'result' => 'sub')
		);
		$param['where'] = array('name' => 'id', 'value' => $code['id'], 'type' => 'i');
		$result = $exec->select($param);

		$flag_level = 0;

		if(!$result || ($result['assign'] !== 0 && isset($var['level'])) || $result['level'] > 5 && $_SESSION['level'] < 7)
			swal('Error', 'Bypass Input! (007)', 'error', '3000', false, true, true);

		if(isset($var['level']) && $var['level'] === 3 && !isset($_POST['perusahaan'], $_POST['direktur'], $_POST['bank'], $_POST['rekening'], $_POST['npwp'], $_POST['alamat']))
			errCode("EC004", "004d");
		elseif(
			((isset($var['level']) && $var['level'] === 3) || ($result['level'] === 2 && $result['sub'] === 0)) 
			&& isset($_POST['perusahaan'], $_POST['direktur'], $_POST['bank'], $_POST['rekening'], $_POST['npwp'], $_POST['alamat']))
			$flag_level = 1;

		if(isset($var['level']) && $var['level'] > 3 && $var['level'] < 8 && !isset($_POST['nip']))
			errCode("EC004", "004d");
		elseif(
			((isset($var['level']) && $var['level'] > 3 && $var['level'] < 8) || $result['level'] > 2) && isset($_POST['nip']))
			$flag_level = 2;
			
		if(
			($flag_level === 1 && !isset($_POST['perusahaan'], $_POST['direktur'], $_POST['bank'], $_POST['rekening'], $_POST['npwp'], $_POST['alamat'])) ||
			($flag_level === 2 && !isset($_POST['nip'])) ||
			($flag_level === 1 && !preg_match('/^[a-zA-Z0-9.,#@\- ]+$/', $_POST['perusahaan'])) ||
			($flag_level === 1 && !preg_match('/^[a-zA-Z0-9.,#@\- ]+$/', $_POST['alamat'])) ||
			($flag_level === 1 && !preg_match('/^[a-zA-Z0-9.,\- ]+$/', $_POST['direktur']))||
			($flag_level === 1 && !preg_match('/^[a-zA-Z0-9\.\- ]*$/', $_POST['npwp'])) ||
			($flag_level === 1 && $_POST['bank'] < 0) ||
			($flag_level === 1 && $_POST['bank'] > 1) ||
			($flag_level === 2 && !preg_match('/^[0-9.,#@\- ]*$/', $_POST['nip']))
		)
			swal('Error', 'Bypass Input! (005a)', 'error', '3000', false, true, true);

			$var['name'] = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_FLAG_ENCODE_HIGH);
			$var['perusahaan'] = filter_input(INPUT_POST, 'perusahaan', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_FLAG_ENCODE_HIGH);
			$var['direktur'] = filter_input(INPUT_POST, 'direktur', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_FLAG_ENCODE_HIGH);
			$var['alamat'] = filter_input(INPUT_POST, 'alamat', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_FLAG_ENCODE_HIGH);
			$var['npwp'] = filter_input(INPUT_POST, 'npwp', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_FLAG_ENCODE_HIGH);
			$var['nip'] = filter_input(INPUT_POST, 'nip', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_FLAG_ENCODE_HIGH);
			$var['rekening'] = filter_input(INPUT_POST, 'rekening', FILTER_SANITIZE_NUMBER_INT);
			$var['bank'] = filter_input(INPUT_POST, 'bank', FILTER_SANITIZE_NUMBER_INT);

		if(isset($var['level'])):
			$var['sub'] = 0;

			switch ($var['level']):
				case '5': case '2':
					$var['sub'] = 1; break;
				case '1':
					$var['sub'] = 2; break;
			endswitch;

			switch ($var['level']):
				case '1': case '2': case '3': 
					$var['level'] = $var['mask_level'] = 2; break;
				case '4':
					$var['level'] = $var['mask_level'] = 3; break;
				case '5':
					$var['level'] = 3; $var['mask_level'] = 4; break;
				case '6':
					$var['level'] = 3; $var['mask_level'] = 4; break;
				case '7':
					$var['level'] = 3; $var['mask_level'] = 5; break;

			endswitch;
		endif;
	break;
 
	case 'e2': // proses edit
		if(!isset($_POST['data']))
			errCode("EC004", "004b");

		$code['id'] = codeCrypt($_POST['data'], true);

		$param['table'] = $tbl_mem;
		$param['field'] = array(
			array('name' => 'f_assign', 'result' => 'assign'),
			array('name' => 'level', 'result' => 'level')
		);
		$param['where'] = array('name' => 'id', 'value' => $code['id'], 'type' => 'i');
		$result = $exec->select($param);

		if(!$result || $result['level'] > 5)
			swal('Error', 'Bypass Input! (007)', 'error', '3000', false, true, true);

		if($result['assign'] !== 0 && $result['level'] > 3)
			swal('Gagal menghapus user', 'User tidak dapat dihapus. User ini merupakan user utama', 'error', '3000', true, true, true);

		$param['table'] = $tbl_dash;
		$param['field'] = array('name' => 'id', 'result' => 'id');
		$param['where_op'] = array('or', 'or', 'or', 'or', 'or', 'or');
		$param['where'] = array(
			array('name' => 'lead_id', 'value' => $code['id'], 'type' => 'i'),
			array('name' => 'rel_id', 'value' => $code['id'], 'type' => 'i'),
			array('name' => 'konsultan', 'value' => $code['id'], 'type' => 'i'),
			array('name' => 'auditor', 'value' => $code['id'], 'type' => 'i'),
			array('name' => 'ppk', 'value' => $code['id'], 'type' => 'i'),
			array('name' => 'kadis', 'value' => $code['id'], 'type' => 'i'),
		);
		$check = $exec->select($param);

		if($check)
			swal('Gagal menghapus user', 'User tidak dapat dihapus. User ini bertanggung jawab dalam sebuah proyek', 'error', '3000', true, true, true);

	break;

endswitch;


// mode
// e7 = fetch table
// 52 = new upload
// 1e = edit upload
// b6 upload process

switch ($code['mode']):

	case 'f5': // fetch table
		if(isset($_POST['sort'], $_POST['order'], $_POST['offset'], $_POST['limit'], $_POST['order'])):

			$var['sort'] = filter_input(INPUT_POST, 'sort', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_FLAG_ENCODE_HIGH);
			$var['order'] = filter_input(INPUT_POST, 'order', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_FLAG_ENCODE_HIGH);
			$var['search'] = filter_input(INPUT_POST, 'search', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_FLAG_ENCODE_HIGH);
			if(isset($_POST['filter'])) $var['filter'] = filter_input(INPUT_POST, 'filter', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH | FILTER_FORCE_ARRAY);
			$var['offset'] = filter_input(INPUT_POST, 'offset', FILTER_SANITIZE_NUMBER_INT);
			$var['limit'] = filter_input(INPUT_POST, 'limit', FILTER_SANITIZE_NUMBER_INT);



            $sort_wl = array('user', 'name', 'status', 'level');
            if(!in_array($var['sort'], $sort_wl)) errCode("EC004", "006");
            if($var['order'] != 'asc' && $var['order'] != 'desc') errCode("EC004", "007");

            if($var['sort'] == 'status'):
            	$var['sort'] = 'temp_status';
            elseif($var['sort'] == 'level'):
            	if($var['order'] == 'asc')
            		$var['sort'] = 'mask_level asc, sub asc, f_assign asc';
            	else
            		$var['sort'] = 'mask_level desc, sub desc, f_assign desc';
            	$var['order'] = '';
            endif;

            switch ($var['sort']) {
            	case 'user': $var['sort'] = 'username'; break;
            }

            if(isset($var['search']) && !empty($var['search']))
				$stmtq_attach = " where name LIKE ?";
			else
				$stmtq_attach = "";

			$param['result'] = array('id', 'user', 'name', 'level', 'status', 'sub', 'assign', 'mask_level');
			if(isset($var['search']) && !empty($var['search'])) {
				$param['param']['type'] = "s";
				$param['param']['value'] = array("%".$var['search']."%");
			}
			else {
				$param['param']['type'] = "";
				$param['param']['value'] = array();
			}

			if($s_level === 6)
				if(isset($var['search']) && !empty($var['search']))
					$stmtq_attach .= " and level < 6";
				else
					$stmtq_attach .= " where level < 6";

            $wlFilterName = array('level', 'status', 'all');
            $wllevel = array('1', '2', '3', '4', '5', '6', '7');
            $wlstatus = array('aktif', 'inaktif');
            $wlall = array('all');

            $f_all = 0;
            $status_attach = $level_attach = '';

			if(isset($_POST['filter'])):
	            $y = count($var['filter']);
	            for($x=0; $x<$y; $x++):

	            	if($var['filter'][$x]['name'] == 'all'){
	            		$f_all = 1;
	            		break;	
	            	}

	            	if(!in_array($var['filter'][$x]['name'], $wlFilterName)) errCode("EC004", "007a");
	            	if(!in_array($var['filter'][$x]['value'], ${'wl'.$var['filter'][$x]['name']})) errCode("EC004", "007b");

	            	
					$var['filter'][$var['filter'][$x]['name']][] = $var['filter'][$x]['value'];

	            endfor;

		        if($f_all == 0):

			        if(isset($var['filter']['level'])):
			        	$stmtq_attach .= " and (";
			        	foreach($var['filter']['level'] as $x => $v):

			        		if($x !== 0){
			        			$level_attach .= ' or ';
			        		}

							switch ($v):
								case '1':
									$level_attach .= '(mask_level = 2 and sub = 2)'; break;
								case '2': 
									$level_attach .= '(mask_level = 2 and sub = 1)'; break;
								case '3': 
									$level_attach .= '(mask_level = 2 and sub = 0)'; break;
								case '4': 
									$level_attach .= 'mask_level = 3'; break;
								case '5': 
									$level_attach .= '(mask_level = 4 and sub = 1)'; break;
								case '6': 
									$level_attach .= '(mask_level = 4 and sub = 0)'; break;
								case '7': 
									$level_attach .= 'mask_level = 5'; break;

							endswitch;

			        	endforeach;
			        	$stmtq_attach .= $level_attach.")";
		        	endif;

			        if(isset($var['filter']['status'])):
		        		$stmtq_attach .= ' having temp_status in (';
		        		foreach($var['filter']['status'] as $x => $v):

			        		switch ($v) {
			        			case 'aktif': $v = 1; break;
			        			case 'inaktif': $v = 0; break;
			        		}
			        		if($x !== 0){
			        			$stmtq_attach .= ' , ';
			        		}

							$stmtq_attach .= '?';
							$param['param']['type'] .= 'i';
							$param['param']['value'][] = $v;

		        		endforeach;
		        		$stmtq_attach .= ")";
		        	endif;

		        endif;

	        endif;


			$stmtq = "SELECT id, username, name, level, (case when mask_level != level then 0 when f_assign = 0 then 0 else 1 end) as temp_status, sub, f_assign, mask_level from ".$tbl_mem." ".$stmtq_attach." ORDER BY ".$var['sort']." ".$var['order']." LIMIT ? OFFSET ?";

			$param['param']['type'] .= 'ii';
			$param['param']['value'][] = $var['limit'];
			$param['param']['value'][] = $var['offset'];

			$param['option']['force_array'] = true;
			$param['option']['transpose'] = true;

				/*			echo $stmtq;
			var_dump($param);
			exit();*/

			$result = $exec->freeQuery($stmtq, $param);
			unset($param);

			$stmtq_attach = '';

            if(isset($var['search']) && !empty($var['search'])):
				$stmtq_attach = " where name LIKE ?";
				$param['param']['type'] = "s";
				$param['param']['value'] = array("%".$var['search']."%");
			endif;

			if($s_level === 6)
				if(isset($var['search']) && !empty($var['search']))
					$stmtq_attach .= " and level < 6";
				else
					$stmtq_attach = " where level < 6";

			$stmtq = "SELECT count(id) from ".$tbl_mem.$stmtq_attach;
			$param['result'] = "total";
			$json['total'] = $exec->freeQuery($stmtq, $param)['total'];
			unset($param);

			if(!$result):

				$json['total'] = 0;
				$json['token'] = $_SESSION['req_token'];

				echo json_encode($json);
				exit();
			else:

				foreach ($result as $key => $value):
						
					$json['rows'][$key]['user'] = '<span data-toggle="tooltip" data-container="body" data-placement="right" title="" data-original-title="Username">'.$value['user'].'</span>';
					$json['rows'][$key]['name'] = '<strong data-toggle="tooltip" data-container="body" data-placement="right" title="" data-original-title="Name">'.$value['name'].'</strong>';

					switch ($value['level']) {
						case 0: case 1: $temp[0] = 'default'; break;
						case 2: $temp[0] = 'warning'; break;
						case 3: $temp[0] = 'inverse'; break;
						case 4: $temp[0] = 'purple'; break;
						case 5: $temp[0] = 'info'; break;
						case 6: $temp[0] = 'pink'; break;
						case 7: $temp[0] = 'danger'; break;
					}
					switch ($value['mask_level']) {
						case 0: case 1: $temp[1] = 'Empty'; break;
						case 2: 
							switch ($value['sub']):
								case 0: $temp[1] = 'Kontraktor'; break;
								case 1: $temp[1] = 'Konsultan'; break;
								case 2: $temp[1] = 'Auditor'; break;
							endswitch; break;
						case 3: $temp[1] = 'PPTK'; break;
						case 4:
							switch ($value['sub']):
								case 0: $temp[1] = 'PPK'; break;
								case 1: $temp[1] = 'Bendahara'; break;
							endswitch; break;
						case 5: $temp[1] = 'Kepala Dinas'; break;
						case 6: $temp[1] = 'Operator'; break;
						case 7: $temp[1] = 'Administrator'; break;
					}

					$temp[2] = $value['level'];
					if($value['mask_level'] !== $value['level'] || $value['assign'] === 0): $temp[0] = 'default'; $temp[2] = $value['mask_level']; endif;

					$json['rows'][$key]['level'] = 
					'<span class="label label-stardusk label-'.$temp[0].'" data-toggle="tooltip" data-container="body" data-placement="bottom" title="" data-original-title="Level : '.$temp[2].'">'.$temp[1].'</span>';

					if($value['level'] === $value['mask_level'] && $value['assign'] !== 0)
						$value['status'] =
						'<i class="md md-radio-button-on" data-toggle="tooltip" data-container="body" data-placement="right" title="" data-original-title="Status : Aktif"></i>';
					else
						$value['status'] =
						'<i class="md md-radio-button-off" data-toggle="tooltip" data-container="body" data-placement="right" title="" data-original-title="Status : Inaktif"></i>';

					$json['rows'][$key]['status'] = $value['status'];
					$json['rows'][$key]['id'] = $value['id'];
					$json['rows'][$key]['aksi'] = 
					'<a class="btn-sm btn-danger  waves-effect waves-light btn-custom str-custom m-r-5 remove" data-primary="'.codeGen("b","f").'" data-mode="'.codeGen("e","2").'" data-id="'.codeGen($value['id'], "", true).'"> <i class="md md-delete" style="font-size: 1.4em; line-height: 1em"></i></a>'.
					'<a class="btn-sm btn-inverse  waves-effect waves-light btn-load btn-custom str-custom m-r-5" data-primary="'.codeGen("b","f").'" data-mode="'.codeGen("4","c").'" data-id="'.codeGen($value['id'], "", true).'">
						<i class="md md-edit" style="font-size: 1.4em; line-height: 1em"></i>
					</a>';

				endforeach;

				$json['token'] = $_SESSION['req_token'];
				echo json_encode($json);
				exit();
			endif;

		endif;
	break;

	case '41': // fetch new
		ob_start();
		require 'fetcher/t01.php';		
		$json['data'] = ob_get_contents();
		ob_end_clean();
		$json['token'] = $_SESSION['req_token'];

		echo json_encode($json);
		exit();
	break;

	case '4c': // fetch edit
		ob_start();
		require 'fetcher/t02.php';		
		$json['data'] = ob_get_contents();
		ob_end_clean();
		$json['token'] = $_SESSION['req_token'];

		echo json_encode($json);
		exit();
	break;

	case '75': // proses new

		$param['table'] = $tbl_mem;
		$param['field'] = array(
			array('name' => 'username', 'value' => $var['user'], 'type' => 's'),
			array('name' => 'name', 'value' => $var['name'], 'type' => 's'),
			array('name' => 'salt', 'value' => $var['salt'], 'type' => 's'),
			array('name' => 'level', 'value' => $var['level'], 'type' => 's'),
			array('name' => 'sub', 'value' => $var['sub'], 'type' => 's'),
			array('name' => 'mask_level', 'value' => $var['mask_level'], 'type' => 's'),
			array('name' => 'password', 'value' => $var['pass'], 'type' => 's'),
		);

		if($var['level'] === 3 || $var['level'] === 2)
			$param['field'][] = array('name' => 'f_assign', 'value' => 1, 'type' => 'i');

		if($var['level'] > 2 && $var['level'] < 6)
			$param['field'][] = array('name' => 'nip', 'value' => $var['nip'], 'type' => 's');

		if($var['level'] === 2 && $var['sub'] === 0):
			$param['field'][] = array('name' => 'perusahaan', 'value' => $var['perusahaan'], 'type' => 's');
			$param['field'][] = array('name' => 'direktur', 'value' => $var['direktur'], 'type' => 's');
			$param['field'][] = array('name' => 'npwp', 'value' => $var['npwp'], 'type' => 's');
			$param['field'][] = array('name' => 'bank', 'value' => $var['bank'], 'type' => 'i');
			$param['field'][] = array('name' => 'rekening', 'value' => $var['rekening'], 'type' => 's');
			$param['field'][] = array('name' => 'alamat', 'value' => $var['alamat'], 'type' => 's');
		endif;

		$dump = '';
		$param['option']['dump_query'] = &$dump;

		if($exec->insert($param))
			swal('Berhasil menambah user', '', 'success', '1500', true, true, true);
		else

			if(isset($dump['errno']) && $dump['errno'] == 1062)
				swal('Gagal menambah user', 'Username tidak tersedia atau sudah terpakai!', 'error', '3000', true, true, true);
			else
				swal('Gagal menambah user', 'Terjadi kesalahan pada sistem. Silahkan hubungi Administrator', 'error', '3000', true, true, true);
	break;

	case '8b': // proses edit
		$param['table'] = $tbl_mem;
		$param['field'] = array(
			array('name' => 'username', 'value' => $var['user'], 'type' => 's'),
			array('name' => 'name', 'value' => $var['name'], 'type' => 's')
		);
		$dump = '';
		$param['option']['dump_query'] = &$dump;
		$param['option']['time'] = 'time_mod';

		if(isset($var['pass'])):
			$param['field'][] = array('name' => 'salt', 'value' => $var['salt'], 'type' => 's');
			$param['field'][] = array('name' => 'password', 'value' => $var['pass'], 'type' => 's');
		endif;

		if(isset($var['level'], $var['mask_level'], $var['sub'])): 
			$param['field'][] = array('name' => 'level', 'value' => $var['level'], 'type' => 'i');
			$param['field'][] = array('name' => 'mask_level', 'value' => $var['mask_level'], 'type' => 'i');
			$param['field'][] = array('name' => 'sub', 'value' => $var['sub'], 'type' => 'i');
		endif;

		if($flag_level === 1):

			$param['field'][] = array('name' => 'perusahaan', 'value' => $var['perusahaan'], 'type' => 's');
			$param['field'][] = array('name' => 'direktur', 'value' => $var['direktur'], 'type' => 's');
			$param['field'][] = array('name' => 'npwp', 'value' => $var['npwp'], 'type' => 's');
			$param['field'][] = array('name' => 'bank', 'value' => $var['bank'], 'type' => 'i');
			$param['field'][] = array('name' => 'rekening', 'value' => $var['rekening'], 'type' => 's');
			$param['field'][] = array('name' => 'alamat', 'value' => $var['alamat'], 'type' => 's');
			$param['field'][] = array('name' => 'f_assign', 'value' => 1, 'type' => 'i');

		elseif($flag_level === 2):

			$param['field'][] = array('name' => 'nip', 'value' => $var['nip'], 'type' => 's');

		endif;

		if($exec->update($param))
			swal('Berhasil mengubah user', $var['message'], 'success', '1500', true, true, true);
		else
			swal('Gagal mengubah user', 'Terjadi kesalahan pada sistem. Silahkan hubungi Administrator', 'error', '3000', true, true, true);
	break;

	case 'e2': // proses hapus
		$param['table'] = $tbl_mem;
		$param['where'] = array('name' => 'id', 'value' => $code['id'], 'type' => 'i');

		$dump = '';
		$param['option']['dump_query'] = &$dump;

		if($exec->delete($param))
			swal('Berhasil menghapus user', '', 'success', '1500', true, true, true);
		else
			swal('Gagal mengahpus user', 'Terjadi kesalahan pada sistem. Silahkan hubungi Administrator', 'error', '3000', true, true, true);
	break;

endswitch;