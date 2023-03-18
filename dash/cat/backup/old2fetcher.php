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
//
$session = new session();
$session->start();
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
if(!isset($_POST['id'], $_POST['status'], $_POST['data_signature'], $_POST['bTable_signature'], $_POST['modal_signature'])) {
	errCode("EC004", "Lagi ngapain disini? tersesat ya mas/mbak? (001)", true);
}
	// ----- SEC var check ------ //
	// status = mode fetch
	// data_signature = action column / id
	// id = main check
	// table_signature = dummy
	// 
	// whitelist for status
	
$whitelist_mode = array('7c', '1f', '4b', '50');
$list_mode_2 = array('1f', '4b');

if(codeCrypt($_POST['id']) != '59' && codeCrypt($_POST['id']) != 'b6'){
	errCode("EC004", "Lagi ngapain disini? tersesat ya mas/mbak? (002)", true);
}

if(!in_array(codeCrypt($_POST['status']), $whitelist_mode)){

	errCode("EC004", "Lagi ngapain disini? tersesat ya mas/mbak? (003)", true);
}
else {
	$code['mode'] = codeCrypt($_POST['status']);
}

if(in_array($code['mode'], $list_mode_2)):
	$var['id'] = codeCrypt($_POST['data_signature'], true);
else:
	switch (codeCrypt($_POST['data_signature'])) {
		case '00':
			$var['action'] = 0;
			break;
		
		case '11':
			$var['action'] = 1;
			break;

		default:
			errCode("EC004", "Lagi ngapain disini? tersesat ya mas/mbak? (004)", true);
			break;
	}
endif;

// ----- MAIN var check ------ //

switch($code['mode']):
	case '7c': case '50':
		if(isset($_POST['sort'], $_POST['order'], $_POST['offset'], $_POST['limit'])){
			$var['sort'] = filter_input(INPUT_POST, 'sort', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_FLAG_ENCODE_HIGH);
			$var['search'] = filter_input(INPUT_POST, 'search', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_FLAG_ENCODE_HIGH);
			$var['offset'] = filter_input(INPUT_POST, 'offset', FILTER_SANITIZE_NUMBER_INT);
			$var['limit'] = filter_input(INPUT_POST, 'limit', FILTER_SANITIZE_NUMBER_INT);

			if($var['limit'] > $max_btable_limit){
				errCode("EC005", "Melebihi batas fetching table (005)", true);
			}

			if($_POST['order'] != 'asc' && $_POST['order'] != 'desc'){
				errCode("EC004", "Lagi ngapain disini? tersesat ya mas/mbak? (006)", true);
			}
			$var['order'] = $_POST['order'];

			$whitelist_sort = array('title', 'jumlah', 'pagu', 'pptk', 'kontrak', 'addendum', 'fisik' ,'penyerapan', 'sisa_1', 'sisa_2', 'status', 'keuangan');

			if(!in_array($var['sort'], $whitelist_sort)){
				errCode("EC004", "Lagi ngapain disini? tersesat ya mas/mbak? (007)", true);
			}

		}
		else {
			errCode("EC004", "Lagi ngapain disini? tersesat ya mas/mbak? (008)", true);
		}
	break;

	case '1f': case '4b':
		if(isset($_POST['delete'])){
			$code['delete'] = true;
		}
		else {
			$code['delete'] = false;
		}
	break;

	default:
		// default
	break;

endswitch;

if($code['mode'] == '50') $var['id_cat'] = codeCrypt($_POST['bTable_signature'], true);
elseif($code['mode'] == '7c')
	if(isset($_POST['year'])) $var['tahun'] = filter_input(INPUT_POST, 'year', FILTER_SANITIZE_NUMBER_INT);
	else errCode("EC004", "Lagi ngapain disini? tersesat ya mas/mbak? (009)", true);	


//  SUBCODE END
// --------------------------------------------------- //
//  SUBCODE : FETCHING TABLE

// debug only
// var_dump($var);

$exec = new dbExec($query);

switch($code['mode']):
	case '7c':
		$param['table'] = array($tbl_cat, $tbl_dash);
		$param['join_op'] = 'left join';
		$param['on'] = array('name' => $tbl_dash.'.kategori', 'target' => $tbl_cat.'.id');
		$param['field'] = array(
			array('name' => $tbl_cat.'.title as title', 'result' => 'title'),
			array('name' => $tbl_cat.'.id as id', 'result' => 'id'),
			array('name' => 'sum('.$tbl_dash.'.n_pagu) as pagu', 'result' => 'pagu'),
			array('name' => 'count('.$tbl_dash.'.id) as jumlah', 'result' => 'jumlah')
		);
		$param['group'] = $tbl_cat.'.id';
		$param['limit'] = array('type' => 'i', 'value' => $var['limit']);
		if($var['offset'] != '0') $param['offset'] = array('type' => 'i', 'value' => $var['offset']);
		$param['order'] = array('name' => $var['sort'], 'sort' => $var['order']);
		if(isset($var['search']) && !empty($var['search'])){
			$param['where_like'] = array('name' => $tbl_cat.'.title', 'type' => 's', 'value' => "%".$var['search']."%", 'operator' => 'and');
			
		}
		$param['where'] = array('name' => $tbl_cat.'.tahun', 'type' => 's', 'value' => $var['tahun']);

		$result = $exec->select($param);
		unset($param);

		if(!$result){
			errAjax("fail");
		}

		$stmtq = "SELECT count(id) as total_kategori,  (select sum(n_pagu) from dash where kategori in (select id from cat) and tahun = ?) as pagu,  (select count(id) from dash where kategori in (select id from cat) and tahun = ?) as jumlah FROM cat where tahun = ?";
		$param['result'] = array('total', 'pagu', 'jumlah');
		$param['param']['type'] = "sss";
		$param['param']['value'] = array($var['tahun'], $var['tahun'], $var['tahun']);

		$result['total'] = $exec->freeQuery($stmtq, $param);

		$json['total_pagu'] = "Rp. ".number_format($result['total']['pagu'], '2', ',', '.');
		$json['total_jumlah'] = $result['total']['jumlah'];
		unset($param);

		$y = count(fa($result['title']));
		for($x=0;$x<$y;$x++){
			$json['rows'][$x]['title'] = '<a href="?d=catman&id='.fa($result['id'])[$x].'">'.fa($result['title'])[$x].'</a>';
			if(fa($result['pagu'])[$x] != 0) $json['rows'][$x]['pagu'] = 'Rp. '.number_format(fa($result['pagu'])[$x], '2', ',', '.');
			if(fa($result['jumlah'])[$x] != 0) $json['rows'][$x]['jumlah'] = fa($result['jumlah'])[$x]." Pekerjaan";
			if($var['action'] === 1) $json['rows'][$x]['aksi'] = array(
				codeGen(fa($result['id'])[$x], '', 1), 
				codeGen('5','9'), 
				codeGen('1','f'), 
				codeGen(random_int(1,9),random_int(1,9)), 
				codeGen(random_int(1,9),random_int(1,9))
			);
		}
		$json['total'] = $result['total']['total'];

		echo json_encode($json);
	break;

	case '1f':

			if(!$code['delete']):
				$param['field'] = array(
				    array('name' => 'id', 'result' => 'id'),
				    array('name' => 'title', 'result' => 'nama')
				);
				$param['table'] = $tbl_cat;
				$param['where'] = array('name' => 'id', 'value' => $var['id'], 'type' => 'i');

				$result = $exec->select($param);
				unset($param);

				if(empty($result)) errAjax("false");

				$send[] = $result['nama'];
				$send[] = codeGen($result['id'], "", 1);
				$send[] = codeGen("1","8");
				$send[] = codeGen("b","2");
				$send[] = codeGen(random_int(1,9),random_int(1,9));
				$send[] = codeGen(random_int(1,9),random_int(1,9));

				echo json_encode($send);

			else:

				$param['table'] = $tbl_cat;
				$param['where'] = array('name' => 'id', 'type' => 'i', 'value' => $var['id']);

				$result = $exec->delete($param);
				unset($param);

				if($result){
					$send['status'] = 'true';
					$send['message'] = 'Berhasil menghapus Kegiatan';
				}
				else {
					$send['status'] = 'false';
				}
				echo json_encode($send);

			endif;
	break;

	case '50':
		if(isset($var['search']) && !empty($var['search'])) {
			$stmtq_attach = " AND REPLACE(REPLACE(SUBSTRING_INDEX(".$tbl_dash.".dp_info, '\"', 2), '\"', ''), '/', '') LIKE ?";
		}
		else {
			$stmtq_attach = "";
		}
		$stmtq = "SELECT
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
			FROM ".$tbl_dash." LEFT JOIN ".$tbl_mem." ON ".$tbl_dash.".lead_id = ".$tbl_mem.".id where ".$tbl_dash.".kategori = ? ".$stmtq_attach." ORDER BY ".$var['sort']." ".$var['order']." LIMIT ? OFFSET ?";

		
		$param['result'] = array('id', 'title', 'addendum', 'pptk', 'pagu', 'kontrak', 'penyerapan', 'persen_fisik', 'sisa_pagu', 'sisa_kontrak', 'kon_or_add', 'persen_keuangan', 'persen_status', 'keuangan');
		if(isset($var['search']) && !empty($var['search'])) {
			$param['param']['type'] = "isii";
			$param['param']['value'] = array($var['id_cat'], "%".$var['search']."%", $var['limit'], $var['offset']);
		}
		else {
			$param['param']['type'] = "iii";
			$param['param']['value'] = array($var['id_cat'], $var['limit'], $var['offset']);
		}
		$param['option']['force_array'] = true;

		$result = $exec->freeQuery($stmtq, $param);
		unset($param);

		if(!$result){
			errAjax("fail");
		}

		$stmtq = "select 
			count(id) as total,
			sum(@addendum:=CONVERT(REPLACE(REPLACE(SUBSTRING_INDEX(SUBSTRING_INDEX(dp_addendum, '/', -2), '\"', -2), '\"', ''), '/', ''), signed)) as addendum,
			sum(n_pagu) as pagu,
			sum(n_kontrak) as kontrak,
			sum(ke_info) as penyerapan,
			sum(convert(n_pagu, signed) - convert(ke_info, signed)) as sisa_pagu, 
			sum(case 
			    when @addendum > 0 then @addendum - convert(ke_info, signed) 
			    else convert(n_kontrak, signed) - convert(ke_info, signed) 
			end) as sisa_kontrak,
			sum(@kon_or_add:=case 
			    when @addendum > 0 then @addendum
			    else convert(n_kontrak, signed)
			end) as sum_kon_or_add
			from ".$tbl_dash." where kategori = ?";

		$param['param']['type'] = "i";
		$param['param']['value'] = array($var['id_cat']);
		$param['result'] = array('row', 'addendum', 'pagu', 'kontrak', 'penyerapan', 'sisa_pagu', 'sisa_kontrak', 'kon_or_add');

		$total = $exec->freeQuery($stmtq, $param);

		$y = count(fa($result['title']));
		for($x=0;$x<$y;$x++){
			settype($result['penyerapan'][$x], 'float');
			settype($result['sisa_kontrak'][$x], 'float');
			$json['rows'][$x]['title'] = '<a href="?d=dashboard&id='.$result['id'][$x].'">'.$result['title'][$x].'</a>';
			$json['rows'][$x]['pptk'] = $result['pptk'][$x];
			if($result['pagu'][$x] != 0) $json['rows'][$x]['pagu'] = 'Rp. '.number_format($result['pagu'][$x], '2', ',', '.');
			if($result['kontrak'][$x] != 0) $json['rows'][$x]['kontrak'] = 'Rp. '.number_format($result['kontrak'][$x], '2', ',', '.');
			if($result['addendum'][$x] != 0) $json['rows'][$x]['addendum'] = 'Rp. '.number_format($result['addendum'][$x], '2', ',', '.');
			if($result['persen_fisik'][$x] != 0) $json['rows'][$x]['fisik'] = $result['persen_fisik'][$x]." %";
			$json['rows'][$x]['penyerapan'] = 'Rp. '.number_format($result['penyerapan'][$x], '2', ',', '.');
			if($result['sisa_pagu'][$x] != 0) $json['rows'][$x]['sisa_pagu'] = 'Rp. '.number_format($result['sisa_pagu'][$x], '2', ',', '.');
			$json['rows'][$x]['sisa_kontrak'] = 'Rp. '.number_format($result['sisa_kontrak'][$x], '2', ',', '.');
			$json['rows'][$x]['keuangan'] = $result['keuangan'][$x];
			if($var['action'] === 1) $json['rows'][$x]['aksi'] = array(
				codeGen($result['id'][$x], '', 1), 
				codeGen('b','6'), 
				codeGen('4','b'), 
				codeGen(random_int(1,9),random_int(1,9)), 
				codeGen(random_int(1,9),random_int(1,9))
			);
		}
		$json['total'] = $total['row'];
		$json['addendum'] = "Rp. ".number_format($total['addendum'], '2', ',', '.');
		$json['pagu'] = "Rp. ".number_format($total['pagu'], '2', ',', '.');
		$json['kontrak'] = "Rp. ".number_format($total['kon_or_add'], '2', ',', '.');
		$json['penyerapan'] = "Rp. ".number_format($total['penyerapan'], '2', ',', '.');
		if($total['sisa_pagu'] < 0) $json['sisa_pagu'] = "Rp. 0,00"; else $json['sisa_pagu'] = "Rp. ".number_format($total['sisa_pagu'], '2', ',', '.');
		if($total['kon_or_add'] - $total['penyerapan'] < 0) $json['sisa_kontrak'] = "Rp. 0,00"; else $json['sisa_kontrak'] = "Rp. ".number_format($total['kon_or_add'] - $total['penyerapan'] , '2', ',', '.');

		$json['chart'][0][0] = $total['penyerapan']/10000000000;
		$json['chart'][1][0] = ($total['kon_or_add'] - $total['penyerapan'])/10000000000;
		$json['chart'][0][1] = $total['penyerapan']/10000000000;
		$json['chart'][1][1] = ($total['pagu'] - $total['penyerapan'])/10000000000;

		$json['total_']['0'] = $json['total_']['50down'] = $json['total_']['50up'] = $json['total_']['100'] = 0;
		$y = count(fa($result['persen_fisik']));
		for($x=0;$x<$y;$x++){
			settype(fa($result['persen_fisik'])[$x], 'float');
			if(fa($result['persen_fisik'])[$x] == 0){
				$json['total_']['0']++;
			}
			elseif(fa($result['persen_fisik'])[$x] < 50){
				$json['total_']['50down']++;
			}
			elseif(fa($result['persen_fisik'])[$x] >= 50 && fa($result['persen_fisik'])[$x] < 100){
				$json['total_']['50up']++;
			}
			elseif(fa($result['persen_fisik'])[$x] == 100){
				$json['total_']['100']++;
			}
		}

		echo json_encode($json);

	break;

	case '4b':

			if(!$code['delete']):
				$param['field'] = array(
				    array('name' => 'id', 'result' => 'id'),
				    array('name' => 'dp_info', 'result' => 'dp_info'),
				    array('name' => 'lead_id', 'result' => 'pptk'),
				    array('name' => 'n_pagu', 'result' => 'pagu'),
				);
				$param['table'] = $tbl_dash;
				$param['where'] = array('name' => 'id', 'value' => $var['id'], 'type' => 'i');

				$result = $exec->select($param);
				unset($param);

				if(empty($result)) errAjax("false");

				$send[] = cta($result['dp_info'])[0];
				$send[] = $result['pagu'];
				$send[] = $result['pptk'];
				$send[] = codeGen($result['id'], "", 1);
				$send[] = codeGen("c","f");
				$send[] = codeGen("9","1");
				$send[] = codeGen(random_int(1,9),random_int(1,9));
				$send[] = codeGen(random_int(1,9),random_int(1,9));

				echo json_encode($send);

			else:

				$param['table'] = $tbl_dash;
				$param['where'] = array('name' => 'id', 'type' => 'i', 'value' => $var['id']);

				$result = $exec->delete($param);
				unset($param);

				if($result){
					$send['status'] = 'true';
					$send['message'] = 'Berhasil menghapus Kegiatan';
				}
				else {
					$send['status'] = 'false';
				}
				echo json_encode($send);

			endif;
	break;
endswitch;

//  SUBCODE END
// --------------------------------------------------- //

// MAIN CODE END
// ################################################### //
?>