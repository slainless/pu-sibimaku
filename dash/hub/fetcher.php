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

// min level 3 / pptk
if($s_level !== 2) {
	errCode("404", "Page not found");
}

$debug = 0;
$token = 0;
$terminate = 0;

$max_btable_limit = 100;

$wl3b = array('4c');

// POST variable checker
if(!isset($_POST['mode'], $_POST['primary'])){
	errCode("EC004", "001");
}

// basic var setter
$code['primary'] = codeCrypt($_POST['primary']);
$code['mode'] = codeCrypt($_POST['mode']);

if($code['primary'] != '3b'){
	errCode("EC004", "002b");
}

// var checker
if(!in_array($code['mode'], ${"wl".$code['primary']})){
	errCode("EC004", "002a");
}


// 001
if($debug) var_dump($code);
if($terminate === 1) { 
	var_dump($code); 
	exit();
}

// ----- TOKEN check ------ //

switch ($code['mode']) {
	case '4c':

		if(!isset($_POST['token'])){
			errCode("EC004", "003");
		}

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
}


// ----- ID check ------ //

switch ($code['mode']) {

	case '4c':  // FETCH KEGIATAN
		if(!isset($_POST['data'])){
			errCode("EC004", "Lagi ngapain disini? tersesat ya mas/mbak? (013)", true);
		}
		
		$code['id'] = codeCrypt($_POST['data'], true);

		if(!$code['id']){
			errCode("EC004", "Lagi ngapain disini? tersesat ya mas/mbak? (014)", true);
		}
	break;

}

// ----- REQUEST var check ------ //
// 

$exec = new dbExec($query);

switch($code['mode']):
	case '4c': // FETCH TABLE
		if(isset($_POST['sort'], $_POST['order'], $_POST['offset'], $_POST['limit'])){
			$var['sort'] = filter_input(INPUT_POST, 'sort', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_FLAG_ENCODE_HIGH);
			$var['search'] = filter_input(INPUT_POST, 'search', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_FLAG_ENCODE_HIGH);
			$var['offset'] = filter_input(INPUT_POST, 'offset', FILTER_SANITIZE_NUMBER_INT);
			$var['limit'] = filter_input(INPUT_POST, 'limit', FILTER_SANITIZE_NUMBER_INT);

			if($var['limit'] > $max_btable_limit){
				errCode("EC005", "Melebihi batas fetching table (005)", true);
			}

			if($_POST['order'] != 'asc' && $_POST['order'] != 'desc'){
				errCode("EC004", "Lagi ngapain disini? tersesat ya mas/mbak? (008)", true);
			}
			$var['order'] = $_POST['order'];

		}
		else {
			errCode("EC004", "Lagi ngapain disini? tersesat ya mas/mbak? (010)", true);
		}
	break;


	default:
		// default
	break;

endswitch;

switch ($code['mode']) {
	case '4c':
		
		$whitelist_sort = array('title', 'jumlah', 'pagu');

		if(!in_array($var['sort'], $whitelist_sort)){
			errCode("EC004", "Lagi ngapain disini? tersesat ya mas/mbak? (009)", true);
		}

		break;
	
	default:
		# code...
		break;
}
	
//  SUBCODE END
// --------------------------------------------------- //
//  SUBCODE : QUERY

// debug only
// var_dump($var);

switch($code['mode']):

	case '4c': // fetch detail kegiatan
		if(!isset($_POST['data'])){
			errCode("EC004", "Lagi ngapain disini? tersesat ya mas/mbak? (013)", true);
		}
		
		$code['id'] = codeCrypt($_POST['data'], true);

		if(isset($var['search']) && !empty($var['search'])) {
			$stmtq_attach = " AND REPLACE(REPLACE(SUBSTRING_INDEX(".$tbl_dash.".dp_info, '\"', 2), '\"', ''), '/', '') LIKE ?";
		}
		else {
			$stmtq_attach = "";
		}

		switch ($_SESSION['sublevel']) {
			case 0: $sublevel = 'rel_id'; break;
			case 1: $sublevel = 'konsultan'; break;
			case 2: $sublevel = 'auditor'; break;
		}

		$stmtq = "SELECT SQL_CALC_FOUND_ROWS
			".$tbl_dash.".id AS id,
			REPLACE(REPLACE(SUBSTRING_INDEX(".$tbl_dash.".dp_info, '\"', 2), '\"', ''), '/', '') AS title,
			@addendum:=CONVERT(REPLACE(REPLACE(SUBSTRING_INDEX(SUBSTRING_INDEX(".$tbl_dash.".dp_addendum, '/', -2), '\"', -2), '\"', ''), '/', ''), signed) as addendum,
			".$tbl_mem.".name AS pptk,
			".$tbl_dash.".n_pagu AS pagu,
			".$tbl_dash.".n_kontrak AS kontrak,
			".$tbl_dash.".ke_info AS penyerapan,
			".$tbl_dash.".pf_total AS fisik,
			convert(".$tbl_dash.".n_pagu, signed) - convert(".$tbl_dash.".ke_info, signed) AS sisa_pagu,
			case 
				when @addendum > 0 then @addendum - convert(".$tbl_dash.".ke_info, signed) 
			    else convert(".$tbl_dash.".n_kontrak, signed) - convert(".$tbl_dash.".ke_info, signed) 
			end AS sisa_kontrak,
			@kontrak_or_addendum:=case when @addendum > 0 then @addendum else convert(".$tbl_dash.".n_kontrak, signed) end AS kon_or_add, 
			@penyerapan_per_koradd_100:=convert(".$tbl_dash.".ke_info, signed) / @kontrak_or_addendum * 100 AS persen_keuangan,
			@persen_status:=pf_total - @penyerapan_per_koradd_100 as persen_status,
			case 
				when @persen_status > 20 then 'Tidak Aman' 
			    when @persen_status <= 20 and @persen_status > 0 then 'Aman' 
			    when @persen_status = 0 or @persen_status is NULL then 'Aman'
			    else 'Tidak Aman'
			end as keuangan 
			FROM ".$tbl_dash." LEFT JOIN ".$tbl_mem." ON ".$tbl_dash.".lead_id = ".$tbl_mem.".id where ".$tbl_dash.".".$sublevel." = ? ".$stmtq_attach." ORDER BY ".$var['sort']." ".$var['order']." LIMIT ? OFFSET ?";

		
		$param['result'] = array('id', 'title', 'addendum', 'pptk', 'pagu', 'kontrak', 'penyerapan', 'persen_fisik', 'sisa_pagu', 'sisa_kontrak', 'kon_or_add', 'persen_keuangan', 'persen_status', 'keuangan');
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

		if($result):

			$param['result'] = 'total';
			$stmtq = 'select found_rows()';
			$total = $exec->freeQuery($stmtq, $param);
			unset($param);

			$y = count($result);
			for($x=0;$x<$y;$x++){
				settype($result[$x]['penyerapan'], 'float');
				settype($result[$x]['sisa_kontrak'], 'float');
				$json['rows'][$x]['title'] = '<a href="/dash/dashboard/'.$result[$x]['id'].'">'.$result[$x]['title'].'</a>';
				$json['rows'][$x]['pptk'] = $result[$x]['pptk'];
				if($result[$x]['pagu'] != 0) $json['rows'][$x]['pagu'] = 'Rp. '.number_format($result[$x]['pagu'], '2', ',', '.');
				if($result[$x]['kontrak'] != 0) $json['rows'][$x]['kontrak'] = 'Rp. '.number_format($result[$x]['kontrak'], '2', ',', '.');
				if($result[$x]['addendum'] != 0) $json['rows'][$x]['addendum'] = 'Rp. '.number_format($result[$x]['addendum'], '2', ',', '.');
				if($result[$x]['persen_fisik'] != 0) $json['rows'][$x]['fisik'] = $result[$x]['persen_fisik']." %";
				$json['rows'][$x]['penyerapan'] = 'Rp. '.number_format($result[$x]['penyerapan'], '2', ',', '.');
				if($result[$x]['sisa_pagu'] != 0) $json['rows'][$x]['sisa_pagu'] = 'Rp. '.number_format($result[$x]['sisa_pagu'], '2', ',', '.');
				$json['rows'][$x]['sisa_kontrak'] = 'Rp. '.number_format($result[$x]['sisa_kontrak'], '2', ',', '.');
				$json['rows'][$x]['keuangan'] = $result[$x]['keuangan'];
			}
			$json['total'] = $total['total'];

		else:
			$json['footer']['addendum'] = $json['footer']['pagu'] = $json['footer']['kontrak'] = $json['footer']['penyerapan'] = $json['footer']['sisa_pagu'] = $json['footer']['sisa_kontrak'] = "Rp. 0,00";

			
		endif;

		$json['token'] = $_SESSION['req_token'];
		echo json_encode($json);
	break;

endswitch;

//  SUBCODE END
// --------------------------------------------------- //

// MAIN CODE END
// ################################################### //
?>