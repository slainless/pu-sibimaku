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

if($_SESSION['level'] !== 3 && $_SESSION['level'] !== 4) {
	errCode("EC001", "000");
}

// basic post check
if(!isset($_POST['mode'], $_POST['primary'])){
	errCode("EC004", "001");
}

// basic var setter
$code['primary'] = codeCrypt($_POST['primary']);
$code['mode'] = codeCrypt($_POST['mode']);

$wlf1 = array('cd', 'd6');
$wlb4 = array('b7', 'f2');

// var checker
if($code['primary'] != 'f1' && $code['primary'] != 'b4')
	errCode("EC004", "002");

if(!in_array($code['mode'], ${'wl'.$code['primary']})) 
	errCode("EC004", "002a");

// 001
if($debug) var_dump($code);
if($terminate === 1) { 
	var_dump($code); 
	exit();
}

// ----- MAIN var check ------ //

switch ($code['mode']):

	case 'cd': case 'd6':
	case 'b7': case 'f2':

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

	case 'd6': case 'b7':
	case 'f2':
		if(!isset($_POST['data']))
			errCode("EC004", "004a");

		$code['id'] = codeCrypt($_POST['data'], true);
	break;

endswitch;

// mode
// e7 = fetch table
// 52 = new upload
// 1e = edit upload
// b6 upload process

switch ($code['mode']):

	case 'cd': // fetch table
		if(isset($_POST['sort'], $_POST['order'], $_POST['offset'], $_POST['limit'], $_POST['order'])):

			$var['sort'] = filter_input(INPUT_POST, 'sort', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_FLAG_ENCODE_HIGH);
			$var['order'] = filter_input(INPUT_POST, 'order', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_FLAG_ENCODE_HIGH);
			$var['search'] = filter_input(INPUT_POST, 'search', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_FLAG_ENCODE_HIGH);
			if(isset($_POST['filter'])) $var['filter'] = filter_input(INPUT_POST, 'filter', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH | FILTER_FORCE_ARRAY);
			$var['offset'] = filter_input(INPUT_POST, 'offset', FILTER_SANITIZE_NUMBER_INT);
			$var['limit'] = filter_input(INPUT_POST, 'limit', FILTER_SANITIZE_NUMBER_INT);

            $sort_wl = array('user', 'title', 'status', 'time');
            if(!in_array($var['sort'], $sort_wl)) errCode("EC004", "006");
            if($var['order'] != 'asc' && $var['order'] != 'desc') errCode("EC004", "007");

            switch ($var['sort']):
            	case 'user': $var['sort'] = 'kontraktor'; break;
            	case 'title': $var['sort'] = 'name'; break;
            	case 'status': $var['sort'] = 'status_per'; break;
            	case 'time': $var['sort'] = 'time_per'; break;
            endswitch;

			$param['result'] = array('id', 'rel_id', 'target', 'status', 'time', 'form', 'name', 'kontraktor', 'target_per_se', 'read_per', 'time_per', 'status_per');

            $wlFilterName = array('read', 'status', 'all');
	        if($_SESSION['level'] === 3):
	            $wlread = array('read', 'unread');
	            $wlstatus = array('1', '2', '3', '4', '5', '6');
	        else:
	        	$wlread = array('read', 'unread');
	            $wlstatus = array('1', '2', '3', '4');
	        endif;

            $wlall = array('all');

            $f_all = 0;
            $stmtq_filter = '';

			if(isset($_POST['filter'])):

	            $y = count($var['filter']);
	            for($x=0; $x<$y; $x++):

	            	if($var['filter'][$x]['name'] == 'all'){
	            		$f_all = 1;
	            		unset($var['filter']);
	            		break;	
	            	}

	            	if(!in_array($var['filter'][$x]['name'], $wlFilterName)) errCode("EC004", "007a");
	            	if(!in_array($var['filter'][$x]['value'], ${'wl'.$var['filter'][$x]['name']})) errCode("EC004", "007b");

	            	
					$var['filter'][$var['filter'][$x]['name']][] = $var['filter'][$x]['value'];

	            endfor;

	        endif;

	        if($_SESSION['level'] === 3):
	        	$stmtq_target = "2";
	        	$stmtq_status = "case 
			    	when (".$tbl_mail.".target = 1 and ".$tbl_mail.".status = 0 and ".$tbl_mail.".f_pptk = 0) then 0
			        when (".$tbl_mail.".target = 1 and ".$tbl_mail.".status = 0 and ".$tbl_mail.".f_pptk = 1) then 1
			        when (".$tbl_mail.".target = 0 and ".$tbl_mail.".status = 2) then 2
			        when (".$tbl_mail.".target = 2 and ".$tbl_mail.".status = 1) then 3
			        when (".$tbl_mail.".target = 1 and ".$tbl_mail.".status = 2) then 4
			        when (".$tbl_mail.".target = 3) then 5
			        else ''
			    end";
				$stmtq_where = $tbl_dash.".lead_id = ? and (".$tbl_mail.".target = 1 or ".$tbl_mail.".f_pptk = 1)";
	        else:
	        	$stmtq_target = "3";
	        	$stmtq_status = "case 
			        when (".$tbl_mail.".target = 2 and ".$tbl_mail.".status = 1 and ".$tbl_mail.".f_ppk = 0) then 0
			        when (".$tbl_mail.".target = 2 and ".$tbl_mail.".status = 1 and ".$tbl_mail.".f_ppk = 1) then 1
			    	when (".$tbl_mail.".target in (1, 0) and ".$tbl_mail.".f_ppk = 1) then 2
			        when (".$tbl_mail.".target = 3) then 3
			        else ''
			    end";
				$stmtq_where = $tbl_dash.".ppk = ? and ".$tbl_mail.".form_data <> '' and (".$tbl_mail.".target = 2 or ".$tbl_mail.".f_ppk = 1)";
	        endif;

			$param['param']['type'] = 'i';
			$param['param']['value'][] = $_SESSION['user_id'];

			if(isset($_POST['filter'])):
		        if($f_all == 0):

			        if(isset($var['filter']['status'])):
			        	$stmtq_filter .= " having status_per in (";
			        	$y = count($var['filter']['status']);
			        	foreach($var['filter']['status'] as $x => $v):

			        		$stmtq_filter .= "?";
			        		if($x !== $y - 1)
			        			$stmtq_filter .= ',';

							$param['param']['type'] .= 'i';
							$param['param']['value'][] = $v - 1;

			        	endforeach;
			        	$stmtq_filter .= ")";
		        	endif;

			        if(isset($var['filter']['read']) && count($var['filter']['read']) !== 2):
			        	if(isset($var['filter']['status']))
		        			$stmtq_filter .= 'and read_per = ?';
		        		else
		        			$stmtq_filter .= 'read_per = ?';

				        	switch ($var['filter']['read'][0]):
				        		case 'read': $v = 1; break;
				        		case 'unread': $v = 0; break;
				        	endswitch;

							$param['param']['type'] .= 'i';
							$param['param']['value'][] = $v;

		        	endif;

		        endif;

	        endif;

	        if(isset($var['search']) && !empty($var['search'])):
				if(isset($var['filter']['status']) || isset($var['filter']['read']))
        			$stmtq_filter .= 'and name like ?';
        		else
        			$stmtq_filter .= ' having name like ?';

				$param['param']['type'] .= "s";
				$param['param']['value'][] = "%".$var['search']."%";
			endif;


			$stmtq = "
			SELECT SQL_CALC_FOUND_ROWS
				".$tbl_mail.".id as id, 
				".$tbl_mail.".rel_id as rel_id, 
			    ".$tbl_mail.".target as target, 
			    ".$tbl_mail.".status as status,
			    ".$tbl_mail.".time as time,
			    ".$tbl_mail.".form_data as form,
			    
			    replace(substring_index(".$tbl_dash.".dp_info, ',', 1), '\"', '') as name, 
			    (select name from ".$tbl_mem." where id = ".$tbl_dash.".rel_id) as kontraktor,

			    @target_per:=replace(replace(substring_index(substring_index(".$tbl_mail.".target_list, ';', ".$stmtq_target."), ';', -1), '/', ''), '\"', '') as target_per_se, 
			    substring_index(substring_index(@target_per, ',', 2), ',', -1) as read_per, 
			    substring_index(@target_per, ',', -1) as time_per, 

			    ".$stmtq_status." as status_per
			        FROM ".$tbl_mail." left join ".$tbl_dash." on ".$tbl_mail.".rel_id = ".$tbl_dash.".id where ".$stmtq_where." ".$stmtq_filter." ORDER BY ".$var['sort']." ".$var['order']." LIMIT ? OFFSET ?
			";

			$param['param']['type'] .= 'ii';
			$param['param']['value'][] = $var['limit'];
			$param['param']['value'][] = $var['offset'];

			$dump = '';

			$param['option']['force_array'] = true;
			$param['option']['transpose'] = true;
			$param['option']['dump_query'] = &$dump;

			$result = $exec->freeQuery($stmtq, $param);
			unset($param);

			$param['result'] = 'total';
			$stmtq = 'select found_rows()';
			$total = $exec->freeQuery($stmtq, $param);
			unset($param);

			//var_dump($result);
			//exit();

			if($result):

				foreach ($result as $key => $value):

					switch ($value['status_per']):
						case '0': 
							$temp[0] = 'default'; 
							$temp[1] = 'Baru';
							$temp[2] = ''; 
							break;
						case '1': 
							$temp[0] = 'inverse';
							$temp[1] = 'Revisi';
							$temp[2] = '';
							break;
						case '2': 
							$temp[0] = $_SESSION['level'] === 3 ? 'purple' : 'danger';
							$temp[1] = 'Ditolak';
							$temp[2] = 'Menunggu Revisi'; 
							break;
						case '3': 
							$temp[0] = ($_SESSION['level'] === 4 || empty($value['form'])) ? 'warning' : 'success';
							$temp[1] = 'Diterima';
							$temp[2] = $_SESSION['level'] === 3 ? (empty($value['form']) ? 'Pengisian form BAP' : 'Menunggu keputusan PPK') : 'Sedang diproses Bendahara'; 
							break;
						case '4': 
							$temp[0] = 'danger';
							$temp[1] = 'Ditolak';
							$temp[2] = 'Ditolak PPK. Menunggu Revisi'; 
							break;
						case '5': 
							$temp[0] = 'warning';
							$temp[1] = 'Diterima';
							$temp[2] = 'Diterima PPK. Sedang diproses Bendahara'; 
							break;

					endswitch;

					$json['rows'][$key]['status'] = 
					'<span class="label label-stardusk label-'.$temp[0].'" data-toggle="tooltip" data-container="body" data-placement="bottom" title="" data-original-title="'.$temp[2].'">'.$temp[1].'</span>';
					if($value['read_per'] !== '0'):
						$json['rows'][$key]['read'] = 
						'<i class="md md-radio-button-on" data-toggle="tooltip" data-container="body" data-placement="right" title="" data-original-title="Belum dilihat"></i>';
						$json['rows'][$key]['unlock'] = 1;
					else:
						$json['rows'][$key]['read'] = 
						'<i class="md md-radio-button-off" data-toggle="tooltip" data-container="body" data-placement="right" title="" data-original-title="Telah dilihat"></i>';
						$json['rows'][$key]['unlock'] = 0;
					endif;

					$json['rows'][$key]['kontraktor'] = '<span class="text">'.$value['kontraktor'].'</span>';
					$json['rows'][$key]['name'] = '<span class="text" style="text-overflow: ellipsis;">'.$value['name'].'</span>';
					$json['rows'][$key]['time'] = '<span>'.interval($value['time_per']).'</span>';
					$json['rows'][$key]['aksi'] = 
					'<a class="btn-sm btn-inverse  waves-effect waves-light btn-custom str-custom m-r-5 btn-load" data-primary="'.codeGen("f","1").'" data-mode="'.codeGen("d","6").'" data-id="'.codeGen($value['id'], "", true).'"> <i class="md md-assignment" style="font-size: 1.4em; line-height: 1em"></i></a>';


				endforeach;

				$json['total'] = $total['total'];
				$json['token'] = $_SESSION['req_token'];
				echo json_encode($json);
				exit();

			else:

				$json['total'] = 0;
				$json['token'] = $_SESSION['req_token'];
				echo json_encode($json);
				exit();

			endif;

		endif;
	break;

	case 'd6': // fetch new
		ob_start();
		require 'fetcher/t01.php';		
		$json['data'] = ob_get_contents();
		ob_end_clean();
		$json['token'] = $_SESSION['req_token'];

		echo json_encode($json);
		exit();
	break;

	case 'b7':
		if(!isset($_POST['submit'], $_POST['keterangan']))
			errCode("EC004", "122");

		date_default_timezone_set("Asia/Makassar");

		$var['submit'] = filter_input(INPUT_POST, 'submit', FILTER_SANITIZE_NUMBER_INT);

		if($var['submit'] !== '1' && $var['submit'] !== '0'){
			errCode("EC004", "Lagi ngapain disini? tersesat ya mas/mbak?", true);	
		}

		$param['comment'] = 1;
		$param['css_expression'] = 1;
		$param['tidy'] = -1;
		$param['elements'] = 'p, span, ul, li, ol, strong, em';
		$var['comment'] = htmLawed(trim($_POST['keterangan']), $param);

		unset($param);

		if($_SESSION['level'] === 3):

			if($var['submit'] === '1'):

				$stmtq_target_1 = 'replace(target_list, substring_index(substring_index(target_list, ";", 2), ";", -1), "/\"1\",\"1\",\"'.date("Y-m-d H:i:s").'\"/")';

				$stmtq_target_2 = 'replace(target_list, substring_index(substring_index(target_list, ";", 3), ";", -1), "/\"2\",\"1\",\"'.date("Y-m-d H:i:s").'\"/")';

				$stmtq = "update ".$tbl_mail." set f_pptk = 1, status = 1, target = 2, comment = '', time_mod = now(), target_list = ".$stmtq_target_1.", target_list = ".$stmtq_target_2." where id = ?";
				$param['param']['type'] = 'i';
				$param['param']['value'] = $code['id'];

				$swalParam = array(
					'type' => 'success',
					'param' => array(
						'form' => 1,
						'link' => '/dash/form/'.codeGen($code['id'],'',true).'-'.$_SESSION['req_token']
					)
				);

				if($exec->freeQuery($stmtq, $param))
					swal('Berhasil', 'Silahkan isi form BAP (Jika belum)', $swalParam, '1500', true, true, true);
				else	
					swal('Gagal', 'Terjadi kesalahan pada sistem. Silahkan hubungi Administrator', 'error', '3000', true, true, true);		

			else:

				$stmtq_target_1 = 'replace(target_list, substring_index(target_list, ";", 1), "/\"0\",\"1\",\"'.date("Y-m-d H:i:s").'\"/")';
				$stmtq_target_2 = 'replace(target_list, substring_index(substring_index(target_list, ";", 2), ";", -1), "/\"1\",\"0\",\"'.date("Y-m-d H:i:s").'\"/")';

				$stmtq = "update ".$tbl_mail." set f_pptk = 1, status = 2, target = 0, comment = ?, time_mod = now(), target_list = ".$stmtq_target_1.", target_list = ".$stmtq_target_2." where id = ?";
				$param['param']['type'] = 'si';
				$param['param']['value'] = array($var['comment'], $code['id']);

				if($exec->freeQuery($stmtq, $param))
					swal('Berhasil', '', 'success', '1500', true, true, true);
				else	
					swal('Gagal', 'Terjadi kesalahan pada sistem. Silahkan hubungi Administrator', 'error', '3000', true, true, true);
			
			endif;

		elseif($_SESSION['level'] === 4):

			if($var['submit'] === '1'):

				$param['field'] = array(
					array('name' => 'form_data', 'result' => 'json'),
					array('name' => 'form_target', 'result' => 'file')
				);
				$param['where'] = array('name' => 'id', 'value' => $code['id'], 'type' => 'i');
				$param['table'] = $tbl_mail;

				$temp_result = $exec->select($param);
				$var = array_merge($var, json_decode($temp_result['json'], true));

				require $dir_dash.'form/fpdf.php';
				require $dir_dash.'form/extended.php';

				$pdf = new PDF();
				$pdf->AliasNbPages();

				$pdf->AddFont('Tahoma','','tahoma.php');
				$pdf->AddFont('Tahoma','B','tahomabd.php');

				require $dir_dash.'form/fetcher/UMa.php';
				require $dir_dash.'form/fetcher/UMb.php';

				$var['path'] = $dir_upload.$result['proyek_id'].'/'.$temp_result['file'].'.pdf';

				$pdf->Output('F', $var['path']);

			    if(is_file($dir_upload.$result['proyek_id'].'/bap-preview.pdf'))
					unlink($dir_upload.$result['proyek_id'].'/bap-preview.pdf');

				$var['name'] = 'Berita Acara Pembayaran - UM/Uang Muka';

				unset($param);

				$stmtq = '
				insert into '.$tbl_docs.' 
					( id, rel_id, name, link, tag, tag_id, time, time_mod ) 
				select 
					temp.id, temp.rel_id, temp.name, temp.link, temp.tag, temp.tag_id, temp.time, temp.time_mod from 
						(select 
							id, ? as rel_id, ? as name, ? as link, ? as tag, ? as tag_id, now() as time, now() as time_mod from '.$tbl_docs.' where tag_id = ? and tag = ?) as temp 
				on duplicate key 
				update '.$tbl_docs.'.rel_id = temp.rel_id, '.$tbl_docs.'.name = temp.name, '.$tbl_docs.'.link = temp.link, '.$tbl_docs.'.time_mod = temp.time_mod';

			    date_default_timezone_set("Asia/Makassar");
				$param['param']['type'] = 'issiiii';
				$param['param']['value'] =  array(
					$result['proyek_id'], 
					$var['name'],
					$var['path'],
					10,
					$code['id'],
					$code['id'],
					10
				);

				if(!$exec->freeQuery($stmtq, $param))
					swal('Gagal', 'Terjadi kesalahan pada sistem. Silahkan hubungi Administrator', 'error', '3000', true, true, true);

				unset($param);
				/*
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
			    }*/

				$stmtq_target_1 = 'replace(target_list, substring_index(target_list, ";", -1), "/\"3\",\"1\",\"'.date("Y-m-d H:i:s").'\"/")';
				$stmtq_target_2 = 'replace(target_list, substring_index(substring_index(target_list, ";", 3), ";", -1), "/\"2\",\"0\",\"'.date("Y-m-d H:i:s").'\"/")';

				$stmtq = "update ".$tbl_mail." set f_ppk = 1, status = 0, target = 3, comment = ?, time_mod = now(), target_list = ".$stmtq_target_1.", target_list = ".$stmtq_target_2." where id = ?";
				$param['param']['type'] = 'si';
				$param['param']['value'] = array($var['comment'], $code['id']);

				if(!$exec->freeQuery($stmtq, $param))
					swal('Gagal', 'Terjadi kesalahan pada sistem. Silahkan hubungi Administrator', 'error', '3000', true, true, true);
				else
					swal('Berhasil', '', 'success', '3000', true, true, true);
				unset($param);
			
			else:

				$stmtq_target_1 = 'replace(target_list, substring_index(substring_index(target_list, ";", 2), ";", -1), "/\"1\",\"1\",\"'.date("Y-m-d H:i:s").'\"/")';
				$stmtq_target_2 = 'replace(target_list, substring_index(substring_index(target_list, ";", 3), ";", -1), "/\"2\",\"0\",\"'.date("Y-m-d H:i:s").'\"/")';

				$stmtq = "update ".$tbl_mail." set f_ppk = 1, status = 2, target = 1, comment = ?, time_mod = now(), target_list = ".$stmtq_target_1.", target_list = ".$stmtq_target_2." where id = ?";
				$param['param']['type'] = 'si';
				$param['param']['value'] = array($var['comment'], $code['id']);

				if($exec->freeQuery($stmtq, $param))
					swal('Berhasil', '', 'success', '1500', true, true, true);
				else	
					swal('Gagal', 'Terjadi kesalahan pada sistem. Silahkan hubungi Administrator', 'error', '3000', true, true, true);

			endif;

		endif;
	break;

	case 'f2':

		date_default_timezone_set("Asia/Makassar");

		$stmtq_target_1 = 'replace(target_list, substring_index(substring_index(target_list, ";", 2), ";", -1), "/\"1\",\"0\",\"'.date("Y-m-d H:i:s").'\"/")';
		$stmtq_target_2 = 'replace(target_list, substring_index(substring_index(target_list, ";", 3), ";", -1), "/\"2\",\"1\",\"'.date("Y-m-d H:i:s").'\"/")';

		$stmtq = "update ".$tbl_mail." set f_pptk = 1, status = 1, form = 0, target = 2, time_mod = now(), target_list = ".$stmtq_target_1.", target_list = ".$stmtq_target_2." where id = ?";
		$param['param']['type'] = 'i';
		$param['param']['value'] = $code['id'];

		if($exec->freeQuery($stmtq, $param))
			swal('Berhasil', '', 'success', '1500', true, true, true);
		else	
			swal('Gagal', 'Terjadi kesalahan pada sistem. Silahkan hubungi Administrator', 'error', '3000', true, true, true);

	break;

endswitch;