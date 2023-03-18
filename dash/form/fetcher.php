<?php
// ################################################### //
// INCLUDE/REQUIRE START

date_default_timezone_set("Asia/Makassar");
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
if($s_level !== 3 && $s_level !== 4) {
	errCode("404", "Page not found");
}

$debug = 0;
$token = 0;
$terminate = 0;

$wl40 = array('7b', 'b8');

// POST variable checker
if(!isset($_POST['mode'], $_POST['primary']))
	errCode("EC004", "001");


// basic var setter
$code['primary'] = codeCrypt($_POST['primary']);
$code['mode'] = codeCrypt($_POST['mode']);

if($code['primary'] != '40')
	errCode("EC004", "002b");


// var checker
if(!in_array($code['mode'], ${"wl".$code['primary']}))
	errCode("EC004", "002a");



// 001
if($debug) var_dump($code);
if($terminate === 1) { 
	var_dump($code); 
	exit();
}

// ----- TOKEN check ------ //

switch ($code['mode']) {
	case '7b': case 'b8':

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

	case '7b': case 'b8':
		if(!isset($_POST['data']))
			errCode("EC004", "Lagi ngapain disini? tersesat ya mas/mbak? (013)", true);
		
		$code['id'] = codeCrypt($_POST['data'], true);

		if(!$code['id'])
			errCode("EC004", "Lagi ngapain disini? tersesat ya mas/mbak? (014)", true);
	break;

}

// ----- REQUEST var check ------ //
// 

$exec = new dbExec($query);

switch($code['mode']):

	case '7b': case 'b8': // add kegiatan
		if(!isset(
			$_POST['kontrak'], 
			$_POST['dpa'],
			$_POST['tanggal_dpa'],
			$_POST['bayar'],
			$_POST['tanggal_bayar']
		))
			errCode("EC004", "Lagi ngapain disini? tersesat ya mas/mbak? (011)", true);

		$var['kontrak'] = filter_input(INPUT_POST, 'kontrak', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_FLAG_ENCODE_HIGH);
		$var['dpa'] = filter_input(INPUT_POST, 'dpa', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_FLAG_ENCODE_HIGH);
		$var['tanggal_dpa'] = filter_input(INPUT_POST, 'tanggal_dpa', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_FLAG_ENCODE_HIGH);
		$var['bayar'] = filter_input(INPUT_POST, 'bayar', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_FLAG_ENCODE_HIGH);
		$var['tanggal_bayar'] = filter_input(INPUT_POST, 'tanggal_bayar', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_FLAG_ENCODE_HIGH);
		$var['tanggal_bap'] = filter_input(INPUT_POST, 'tanggal_bap', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_FLAG_ENCODE_HIGH) ?? date('d/m/Y');
		$var['nama_1'] = filter_input(INPUT_POST, 'nama_1', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_FLAG_ENCODE_HIGH) ?? null;
		$var['nama_2'] = filter_input(INPUT_POST, 'nama_2', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_FLAG_ENCODE_HIGH) ?? null;
		$var['kode'] = filter_input(INPUT_POST, 'kode', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_FLAG_ENCODE_HIGH) ?? null;
		$var['sumber'] = filter_input(INPUT_POST, 'sumber', FILTER_SANITIZE_NUMBER_INT) ?? null;

		if(
			empty($var['kontrak']) ||
			empty($var['dpa']) ||
			empty($var['tanggal_dpa']) ||
			empty($var['bayar']) ||
			empty($var['tanggal_bayar']) ||
			(!empty($var['sumber']) && $var['sumber'] !== '1' && $var['sumber'] !== '2') ||
			!validate($var['tanggal_bap'], 'date') ||
			!validate($var['tanggal_dpa'], 'date') ||
			!validate($var['tanggal_bayar'], 'date') ||
			(!empty($var['kontrak']) && !preg_match('/^\d+\/\d{2}\.\d{2}\/[A-Z]+\/[A-Z.-]+\/[IXV]+\/\d{4}$/', $var['kontrak']))
		)
			errCode("EC004", "Lagi ngapain disini? tersesat ya mas/mbak? (012)", true);

	break;

endswitch;
	
//  SUBCODE END
// --------------------------------------------------- //
//  SUBCODE : QUERY

// debug only
// var_dump($var);

switch($code['mode']):

	case 'b8': case 'd3':

		require 'fpdf.php';
		require 'extended.php';

		$pdf = new PDF();
		$pdf->AliasNbPages();

		$pdf->AddFont('Tahoma','','tahoma.php');
		$pdf->AddFont('Tahoma','B','tahomabd.php');

		$mode = isset($_GET['mode']) ? $_GET['mode'] : 0;

		require 'fetcher/UMa.php';
		require 'fetcher/UMb.php';

		if($code['mode'] == 'b8'):
			$var['path'] = $dir_upload.$result['proyek_id'].'/bap-preview.pdf';

		elseif($code['mode'] == 'd3'):

			$now = date("Gis");
		    $temp = bin2hex(random_bytes(random_int(1, 2)));
		    $now = dechex($now);

			$var['path'] = $dir_upload.$result['proyek_id'].'/bap-um~'.$now.'.pdf';
		endif;

		$pdf->Output('F', $var['path']);

		if($code['mode'] == 'd3'):

		    if(is_file($dir_upload.$result['proyek_id'].'/bap-preview.pdf'))
				unlink($dir_upload.$result['proyek_id'].'/bap-preview.pdf');

			$var['name'] = 'Berita Acara Pembayaran - UM/Uang Muka';

		    date_default_timezone_set("Asia/Makassar");
		    $param['table'] = $tbl_docs;
		    $param['field'] = array(
		    	array('name' => 'rel_id', 'value' => $result['proyek_id'], 'type' => 'i'),
		    	array('name' => 'name', 'value' => $var['name'], 'type' => 's'),
		    	array('name' => 'link', 'value' => $var['path'], 'type' => 's'),
		    	array('name' => 'tag', 'value' => 10, 'type' => 'i'),
		    	array('name' => 'tag_id', 'value' => $code['id'], 'type' => 'i'),
		    	array('name' => 'time', 'value' => date("Y-m-d H:i:s"), 'type' => 's'),
		    	array('name' => 'time_mod', 'value' => date("Y-m-d H:i:s"), 'type' => 's')
		    );

		    if(!$exec->insert($param)){
				unlink($var['path']);
				swal('Gagal', 'Terjadi kesalahan pada sistem. Silahkan hubungi Administrator', 'error', '3000', true, true, true);
		    }
		    else {

		    }
		    
			unset($param);
			/*
			$stmtq_target_1 = 'replace(target_list, substring_index(substring_index(target_list, ";", 3), ";", -1), "/\"2\",\"1\",\"'.date("Y-m-d H:i:s").'\"/")';
			$stmtq_target_2 = 'replace(target_list, substring_index(substring_index(target_list, ";", 2), ";", -1), "/\"1\",\"0\",\"'.date("Y-m-d H:i:s").'\"/")';

			$stmtq = "update ".$tbl_mail." set f_pptk = 1, status = 1, form = 0, target = 2, time_mod = now(), target_list = ".$stmtq_target_1.", target_list = ".$stmtq_target_2." where id = ?";
			$param['param']['type'] = 'i';
			$param['param']['value'] = $code['id'];
			$dump = '';
			$param['option']['dump_query'] = &$dump;

			if($exec->freeQuery($stmtq, $param)):
				// var_dump($dump);
				swal('Berhasil', 'Pengisian form BAP selesai. Silahkan reload laman permintaan.', 'success', '1500', true, true, true);
			else:
				//var_dump($dump);
				unlink($var['path']);
				swal('Gagal', 'Terjadi kesalahan pada sistem. Silahkan hubungi Administrator', 'error', '3000', true, true, true);
			endif;*/
			
		elseif($code['mode'] == 'b8'):
			echo json_encode(
				array('preview' => $abs_dir_upload.$result['proyek_id'].'/bap-preview.pdf')
			);
			exit();
		endif;

	break;

	case '7b': 

		$stmtq = "
		    SELECT 
		        ppk.name as ppk_name, 
		        ppk.nip as ppk_nip, 
		        kontraktor.direktur as kon_name, 
		        kontraktor.alamat as kon_alamat,
		        kontraktor.perusahaan as kon_perusahaan,
		        kontraktor.bank as kon_bank,
		        kontraktor.rekening as kon_rekening,
		        kontraktor.npwp as kon_npwp,
		        replace(substring_index(".$tbl_dash.".dp_info, ',', 1), '\"', '') as proyek_name, 
		        replace(substring_index(".$tbl_dash.".dp_info, ',', -1), '\"', '') as proyek_lokasi,
		        replace(substring_index(".$tbl_dash.".dp_kontrak, ',', 1), '\"', '') as proyek_kontrak, 
		        replace(substring_index(".$tbl_dash.".dp_kontrak, ',', -1), '\"', '') as proyek_tanggal,
		        ".$tbl_dash.".n_kontrak as proyek_nilai,
		        ".$tbl_dash.".id as proyek_id,
		        (select tahun from cat where id = ".$tbl_dash.".kategori) as proyek_tahun,
		        ".$tbl_mail.".form as form
		    FROM 
		        (select name, nip from ".$tbl_mem." where level = 4 and mask_level = 4 and f_assign = 1) as ppk 
		        join 
		        (select direktur, alamat, perusahaan, bank, npwp, rekening from ".$tbl_mem." where id = 
		            (select rel_id from ".$tbl_dash." where id = (select rel_id from ".$tbl_mail." where id = ?))
		        ) as kontraktor
		        join
		        ".$tbl_dash." 
		        join ".$tbl_mail." 
		    on ".$tbl_mail.".rel_id = ".$tbl_dash.".id
		    where ".$tbl_mail.".id = ?";

		$param['param']['type'] = 'ii';
		$param['param']['value'] = array($code['id'], $code['id']);
		$param['result'] = array('ppk_name','ppk_nip','kon_name','kon_alamat','kon_perusahaan','kon_bank','kon_rekening','kon_npwp','proyek_name','proyek_lokasi','proyek_kontrak','proyek_tanggal','proyek_nilai', 'proyek_id', 'proyek_tahun', 'form');

		$result = $exec->freeQuery($stmtq, $param);
		unset($param);

		if(!$result)
		    errCode("404", "Page not found");

		$json = array(
			"kontrak" => $var['kontrak'],
			"tanggal_bap" => $var['tanggal_bap'],
			"nama_1" => $var['nama_1'] ?? $result['ppk_name'],
			"nama_2" => $var['nama_2'] ?? $result['kon_name'],
			"dpa" => $var['dpa'],
			"tanggal_dpa" => $var['tanggal_dpa'],
			"kode" => $var['kode'],
			"sumber" => $var['sumber'] ?? 1,
			"tanggal_bayar" => $var['tanggal_bayar'],
			"bayar" => $var['bayar'],
		);

		$json = jsonMin::minify(json_encode($json));

		$now = date("Gis");
	    $temp = bin2hex(random_bytes(random_int(1, 2)));
	    $now = 'bap-um~'.dechex($now).$temp;

		$param['table'] = $tbl_mail;
	    $param['field'] = array(
			array('name' => 'form_data', 'value' => $json, 'type' => 's'),
			array('name' => 'form_target', 'value' => $now, 'type' => 's')
		);
	    $param['where'] = array('name' => 'id', 'value' => $code['id'], 'type' => 'i');

	    if(!$exec->update($param)){
			swal('Gagal', 'Terjadi kesalahan pada sistem. Silahkan hubungi Administrator', 'error', '3000', true, true, true);
	    }
	    else {
			swal('Berhasil', '', 'success', '3000', true, true, true);
	    }
	    
		unset($param);

	break;

endswitch;

//  SUBCODE END
// --------------------------------------------------- //

// MAIN CODE END
// ################################################### //
?>