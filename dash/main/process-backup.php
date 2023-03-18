<?php
if($s_level > 1):

	$dash = 'dash';
	$cat = 'cat';
	$mem = 'member';
	$mail = 'mail';

	$process = new dbExec($query);

	if(isset($_POST["device"], $_POST["device_statistic"], $_POST['status']) && codeCrypt($_POST["device"]) == '73'):

		$code = codeCrypt($_POST["device_statistic"]);
		$get_id = codeCrypt($_POST["status"], 1);

		$param['table'] = $dash;
		$param['field']['name'] = 'lead_id';
		$param['field']['result'] = 'id_pptk';

		$param['where']['name'] = 'id';
		$param['where']['type'] = 'i';
		$param['where']['value'] = $get_id;

		$check = $process->select($param);

		if($s_level == 3 && $check['id_pptk'] != $s_id){
			errCode("EC001", "Lagi ngapain disini? tersesat ya mas/mbak?", true);
		}

		if($s_level < 3 && $code != '61' && $code != '47'){
			errCode("EC001", "Lagi ngapain disini? tersesat ya mas/mbak?", true);			
		}

		switch ($code) {
			case '53': // EDIT INFO PROYEK
				
				if(isset($_POST['data_t010'], $_POST['data_t011'], $_POST['data_t013'])):
					$no_kontrak = filter_input(INPUT_POST, 'data_t010', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_FLAG_ENCODE_HIGH);
					$tanggal_kontrak = str_replace("/","-",$_POST['data_t011']);

					$temp = explode("-", $tanggal_kontrak);
					if(!checkdate($temp[1], $temp[0], $temp[2])){
						return false;
					}

					$lokasi_proyek = filter_input(INPUT_POST, 'data_t013', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_FLAG_ENCODE_HIGH);
					$lokasi_proyek = str_replace("/", "&#47;", $lokasi_proyek);

					if(
						strlen($tanggal_kontrak) != 10 ||
						substr($tanggal_kontrak, 2, 1) != "-" ||
						substr($tanggal_kontrak, 5, 1) != "-" ||
						empty($lokasi_proyek)
					){
						return false;
					}



					$param['table'] = $dash;
					$param['field'] = array(
						array('name' => 'dp_info', 'result' => 'info_proyek'),
						array('name' => 'dp_kontrak', 'result' => 'info_kontrak'),
					);
					$param['where'] = array('name' => 'id', 'value' => $get_id, 'type' => 'i');

					if(!$result = $process->select($param)){
						return false;
					}

					$result['info_proyek'] = cta($result['info_proyek']);
					$result['info_kontrak'] = cta($result['info_kontrak']);

					$result['info_proyek'][1] = $lokasi_proyek;

					$result['info_kontrak'][0] = $no_kontrak;
					$result['info_kontrak'][1] = $tanggal_kontrak;

					$result['info_proyek'] = atc($result['info_proyek']);
					$result['info_kontrak'] = atc($result['info_kontrak']);

					$param['table'] = $dash;
					$param['field'] = array(
						array('name' => 'dp_info', 'value' => $result['info_proyek'], 'type' => 's'),
						array('name' => 'dp_kontrak', 'value' => $result['info_kontrak'], 'type' => 's')
					);
					$param['where'] = array('name' => 'id', 'value' => $get_id, 'type' => 'i');

					if($process->update($param)){
					}
					else {
					}

				else:
					return false;
				endif;

				break;

			case '30': // EDIT PERSONALIA

				if(isset($_POST['data_t020'], $_POST['data_t021'])):
					$id_kontraktor = filter_input(INPUT_POST, 'data_t020', FILTER_SANITIZE_NUMBER_INT);
					$konsultan = filter_input(INPUT_POST, 'data_t021', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_FLAG_ENCODE_HIGH);


					if(empty($id_kontraktor)){
						$id_kontraktor = 0;
					}

					if(empty($konsultan)){
						$konsultan = 0;
					}

					$param['table'] = $tbl_mem;
					$param['field'] = array('name' => 'id', 'result' => 'id');
					$param['where'] = array('name' => 'level', 'value' => 2, 'type' => 'i');

					$result = $process->select($param);
					unset($param);

					if((!in_array($id_kontraktor, $result['id']) && $id_kontraktor != 0) || (!in_array($konsultan, $result['id']) && $konsultan != 0)){
						return false;
					}

					$param['table'] = $tbl_dash;
					$param['field'] = array(
						array('name' => 'rel_id', 'value' => $id_kontraktor, 'type' => 'i'),
						array('name' => 'konsultan', 'value' => $konsultan, 'type' => 'i')
					);
					$param['where'] = array('name' => 'id', 'value' => $get_id, 'type' => 'i');

					$process->update($param);
					unset($param);

				endif;
				break;

			case '96': // EDIT SURAT

				if(isset($_POST['data_t030'], $_POST['data_t031'], $_POST['data_t032'], $_POST['data_t033'])):
					$surat_serah = filter_input(INPUT_POST, 'data_t030', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_FLAG_ENCODE_HIGH);
					$surat_kerja = filter_input(INPUT_POST, 'data_t031', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_FLAG_ENCODE_HIGH);
					$masa_laksana = filter_input(INPUT_POST, 'data_t032', FILTER_SANITIZE_NUMBER_INT);
					$masa_pelihara = filter_input(INPUT_POST, 'data_t033', FILTER_SANITIZE_NUMBER_INT);

					if(
						strlen($masa_pelihara) > 3 ||
						strlen($masa_laksana) > 3 ||
						empty($surat_kerja) ||
						empty($surat_serah) ||
						empty($masa_laksana)
					){
						return false;
					}

					$result['info_surat'][0] = $surat_serah;
					$result['info_surat'][1] = $surat_kerja;
					$result['info_surat'][2] = $masa_laksana;
					$result['info_surat'][3] = $masa_pelihara;

					$result['info_surat'] = atc($result['info_surat']);

					$param['table'] = $dash;
					$param['field'] = array('name' => 'dp_surat', 'value' => $result['info_surat'], 'type' => 's');
					$param['where'] = array('name' => 'id', 'value' => $get_id, 'type' => 'i');

					if($process->update($param)){
					}
					else {
					}

				else:
					return false;
				endif;

				break;

			case '12': // EDIT PEKERJAAN
				
				if(isset($_POST['data_t040'], $_POST['data_t041'], $_POST['data_t042'])):
					$no_kontrak = filter_input(INPUT_POST, 'data_t040', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY | FILTER_FLAG_ENCODE_HIGH);
					$nilai_kontrak = filter_input(INPUT_POST, 'data_t042', FILTER_SANITIZE_NUMBER_INT, FILTER_REQUIRE_ARRAY);

					if(count($_POST['data_t040']) != count($_POST['data_t041']) || count($_POST['data_t040']) != count($_POST['data_t042'])){
						return false;
					}

					$y = count($_POST['data_t040']);
					for($x=0;$x<$y;$x++):

						if(!empty($_POST['data_t041'][$x])):
							$tanggal_kontrak[$x] = str_replace("/","-",$_POST['data_t041'][$x]);
							
							$temp = explode("-", $tanggal_kontrak[$x]);
							if(!checkdate($temp[1], $temp[0], $temp[2])){
								return false;
							}
							unset($temp);

							if(
								strlen($tanggal_kontrak[$x]) != 10 ||
								substr($tanggal_kontrak[$x], 2, 1) != "-" ||
								substr($tanggal_kontrak[$x], 5, 1) != "-"
							)
							{
								return false;
							}
						endif;

						if($x !== 0 && (empty($no_kontrak[$x]) || empty($nilai_kontrak[$x]) || empty($tanggal_kontrak[$x]))){
							return false;
							$result['info_addendum'][$x][0] = str_replace("/", "&#47;", $no_kontrak[$x]);
							$result['info_addendum'][$x][1] = $tanggal_kontrak[$x];
							$result['info_addendum'][$x][2] = $nilai_kontrak[$x];
						}
						else {
							$result['info_addendum'][$x][0] = str_replace("/", "&#47;", $no_kontrak[$x]);
							if(empty($tanggal_kontrak[$x])) $result['info_addendum'][$x][1] = ''; else $result['info_addendum'][$x][1] = $tanggal_kontrak[$x];
							if(empty($nilai_kontrak[$x])) $result['info_addendum'][$x][2] = 0; else $result['info_addendum'][$x][2] = $nilai_kontrak[$x];
						}

					endfor;

					$result['info_addendum'] = atc($result['info_addendum'], true);

					$param['table'] = $dash;
					$param['field'] = array('name' => 'dp_addendum', 'value' => $result['info_addendum'], 'type' => 's');
					$param['where'] = array('name' => 'id', 'value' => $get_id, 'type' => 'i');

					if($process->update($param)){
					}
					else {
					}

				else:
					return false;
				endif;

				break;

			case '61':

				if(isset($_FILES['file'], $_POST['filetype'])):

					$limit = 5;
					$max_size = 20000000;

					$param['table'] = $dash;
					$param['where']['name'] = 'id';
					$param['where']['value'] = $get_id;
					$param['where']['type'] = 'i';
					$param['field'] = array(
						array('name' => 'rel_id', 'result' => 'rel_id'),
						array('name' => 'lead_id', 'result' => 'lead_id'),
					); // TO ADD : CHECK PROGRESS

					$checkDash = $process->select($param);
					unset($param);

					$param['table'] = $mail;
					$param['where']['name'] = 'rel_id';
					$param['where']['value'] = $get_id;
					$param['where']['type'] = 'i';
					$param['field'] = array(
						array('name' => 'rel_id', 'result' => 'rel_id'),
					);

					$check = $process->select($param);
					unset($param);
					
					if($check || !$checkDash){
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

					$y = count($_FILES['file']['name']);
					if($y > $limit){
						return false;
					}

					for($x=0;$x<$y;$x++){

						if($_FILES['file']['error'][$x] !== 0){
							unset($_FILES['file']['name'][$x]);
							continue;
						}

						if ($_FILES['file']['size'][$x] > $max_size) {
							unset($_FILES['file']['name'][$x]);
							continue;
						}

						date_default_timezone_set("Asia/Makassar");
					    $now = date("Gis");
					    $temp = bin2hex(random_bytes(random_int(1, 3)));
					    $now = dechex($now);

					    if(move_uploaded_file($_FILES['file']['tmp_name'][$x], $dir_upload.$now.$temp.".pdf")) {
					    	$moved[] = $dir_upload.$now.$temp.".pdf";
					    }
					    else {
					    	foreach($moved as $key => $value){
								unlink($value);
							}
							errCode("EC011", "Gagal mengupload file!", true);
					    }
					}

				    unset($temp);
				    $temp['attach'] = atc($moved);
				   	$temp['t_list'][] = array(0, 0, date("Y-m-d H:i:s"));
				    $temp['t_list'][] = array(1, 0, date("Y-m-d H:i:s"));
				    $temp['t_list'][] = array(2, 0, 0);
				    $temp['t_list'][] = array(3, 0, 0);
				    $temp['target_list'] = atc($temp['t_list'], true);

				    $param['table'] = $mail;
				    $param['field'] = array(
				    	array('name' => 'attach', 'value' => $temp['attach'], 'type' => 's'),
				    	array('name' => 'target', 'value' => 1, 'type' => 'i'),
				    	array('name' => 'status', 'value' => 0, 'type' => 'i'),
				    	array('name' => 'rel_id', 'value' => $get_id, 'type' => 'i'),
				    	array('name' => 'target_list', 'value' => $temp['target_list'], 'type' => 's'),
				    	array('name' => 'time', 'value' => date("Y-m-d H:i:s"), 'type' => 's')
				    );

				    if(!$process->insert($param)){
				    	foreach($moved as $key => $value){
							unlink($value);
						}
				    	errCode("EC003", "Terjadi kesalahan pada sistem.");
				    }
				    else {
				    	echo "true";
				    	exit();
				    };

				else:
					errCode("EC004", "Lagi ngapain disini? tersesat ya mas/mbak?", true);
				endif;

				break;

			case '47':

				if(isset($_FILES['file'], $_POST['filetype'])):

					$limit = 5;
					$max_size = 20000000;

					$param['table'] = $dash;
					$param['where']['name'] = 'id';
					$param['where']['value'] = $get_id;
					$param['where']['type'] = 'i';
					$param['field'] = array(
						array('name' => 'rel_id', 'result' => 'rel_id'),
						array('name' => 'lead_id', 'result' => 'lead_id'),
					); // TO ADD : CHECK PROGRESS

					$checkDash = $process->select($param);
					unset($param);

					$param['table'] = $mail;
					$param['where']['name'] = 'rel_id';
					$param['where']['value'] = $get_id;
					$param['where']['type'] = 'i';
					$param['field'] = array(
						array('name' => 'status', 'result' => 'status'),
						array('name' => 'target_list', 'result' => 'target_list'),
						array('name' => 'attach', 'result' => 'attach'),
						array('name' => 'rel_id', 'result' => 'rel_id'),
					);

					$check = $process->select($param);
					unset($param);
					
					if(!$check || !$checkDash){
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

					$y = count($_FILES['file']['name']);
					$z = zeroCount(cta($check['attach']));

					if($z + $y > $limit){
						errCode("EC004", "Lagi ngapain disini? tersesat ya mas/mbak?", true);
					}

					if($check['status'] !== 2){
						errCode("EC004", "Lagi ngapain disini? tersesat ya mas/mbak?", true);
					}

					for($x=0;$x<$y;$x++){

						if($_FILES['file']['error'][$x] !== 0){
							unset($_FILES['file']['name'][$x]);
							continue;
						}

						if ($_FILES['file']['size'][$x] > $max_size) {
							unset($_FILES['file']['name'][$x]);
							continue;
						}

						date_default_timezone_set("Asia/Makassar");
					    $now = date("Gis");
					    $temp = bin2hex(random_bytes(random_int(1, 3)));
					    $now = dechex($now);

					    if(move_uploaded_file($_FILES['file']['tmp_name'][$x], $dir_upload.$now.$temp.".pdf")) {
					    	$moved[] = $dir_upload.$now.$temp.".pdf";
					    }
					    else {
					    	foreach($moved as $key => $value){
								unlink($value);
							}
							errCode("EC011", "Gagal mengupload file!", true);
					    }
					}

				    unset($temp);
				    $temp['attach'] = cta($check['attach']);
				   	foreach($moved as $key => $value){
						$temp['attach'][] = $value;
					}
					$check['attach'] = atc($temp['attach']);

				    $temp['t_list'] = cta($check['target_list'], true);
				    $temp['t_list'][0][0] = 0;
				    $temp['t_list'][0][1] = 0;
				    $temp['t_list'][0][2] = date("Y-m-d H:i:s");
				    $temp['t_list'][1][0] = 1;
				    $temp['t_list'][1][1] = 0;
				    $temp['t_list'][1][2] = date("Y-m-d H:i:s");
				    $temp['target_list'] = atc($temp['t_list'], true);

				    $param['table'] = $mail;
				    $param['field'] = array(
				    	array('name' => 'attach', 'value' => $check['attach'], 'type' => 's'),
				    	array('name' => 'target', 'value' => 1, 'type' => 'i'),
				    	array('name' => 'status', 'value' => 0, 'type' => 'i'),
				    	array('name' => 'target_list', 'value' => $temp['target_list'], 'type' => 's'),
				    	array('name' => 'comment', 'value' => '', 'type' => 's')
				    );
				    $param['where'] = array('name' => 'rel_id', 'value' => $get_id, 'type' => 'i');

				    if(!$process->update($param)){
				    	foreach($moved as $key => $value){
							unlink($value);
						}
				    	errCode("EC003", "Terjadi kesalahan pada sistem.");
				    }
				    else {
				    	echo "true";
				    	exit();
				    };

				else:
					errCode("EC004", "Lagi ngapain disini? tersesat ya mas/mbak?", true);
				endif;

				break;

			case 'fa': // EDIT SURAT

				if(isset($_POST['data_0'], $_POST['data_1'], $_POST['data_2'], $_POST['data_3'], $_POST['data_4'])):
					$divisi = filter_input(INPUT_POST, 'data_0', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_FLAG_ENCODE_HIGH | FILTER_FORCE_ARRAY);
					$uraian = filter_input(INPUT_POST, 'data_1', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_FLAG_ENCODE_HIGH | FILTER_FORCE_ARRAY);
					$kontrak = filter_input(INPUT_POST, 'data_2', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION | FILTER_FORCE_ARRAY);
					$lalu = filter_input(INPUT_POST, 'data_3', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION | FILTER_FORCE_ARRAY);
					$ini = filter_input(INPUT_POST, 'data_4', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION | FILTER_FORCE_ARRAY);

					$y = count($divisi);
					$var['total'] = 0;
					for($x=0;$x<$y;$x++){

						$kontrak[$x] = pf($kontrak[$x]);
						$lalu[$x] = pf($lalu[$x]);
						$ini[$x] = pf($ini[$x]);

						$var['fisik'][$x][0] = str_replace("/", "&#47;", $divisi[$x]);
						$var['fisik'][$x][1] = str_replace("/", "&#47;", $uraian[$x]);
						$var['fisik'][$x][2] = $kontrak[$x];
						$var['fisik'][$x][3] = $lalu[$x];
						$var['fisik'][$x][4] = $ini[$x];

						settype($lalu[$x], 'float');
						settype($ini[$x], 'float');

						$var['total'] = $var['total'] + $lalu[$x] + $ini[$x];

					}

					$var['fisik'] = atc($var['fisik'], true);

					$param['table'] = $tbl_dash;
					$param['field'] = array(
						array('name' => 'pf_info', 'value' => $var['fisik'], 'type' => 's'),
						array('name' => 'pf_total', 'value' => $var['total'], 'type' => 's'),
					);
					$param['where'] = array('name' => 'id', 'value' => $get_id, 'type' => 'i');

					if($process->update($param)){
					}
					else {
					}

				else:
					return false;
				endif;

				break;

			case 'b3': // EDIT SURAT

				if(isset($_POST['data_0'], $_POST['data_1'])):
					$data_0 = filter_input(INPUT_POST, 'data_0', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION | FILTER_FORCE_ARRAY);
					$data_1 = filter_input(INPUT_POST, 'data_1', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION | FILTER_FORCE_ARRAY);

					if(count($data_0) != count($data_1) && count($data_0) != 12){
						errCode("EC004", "Lagi ngapain disini? tersesat ya mas/mbak?", true);
					}

					$var = array(
						$data_0,
						$data_1
					);

					$var = array_map(null, ...$var);

					foreach($var as $x => $a){
						foreach ($a as $y => $b) {

							$var[$x][$y] = pf($b);

						}
					}

					$var = atc($var, true);


					$param['table'] = $tbl_dash;
					$param['field'] = array(
						array('name' => 'mp_info', 'value' => $var, 'type' => 's'),
					);
					$param['where'] = array('name' => 'id', 'value' => $get_id, 'type' => 'i');

					if($process->update($param)){
					}
					else {
					}

				else:
					return false;
				endif;

				break;

			case '5f': // EDIT INFO PROYEK
				
				if(isset($_POST['data_t060'], $_POST['data_t061'])):
					$var['kontrak'] = filter_input(INPUT_POST, 'data_t060', FILTER_SANITIZE_NUMBER_INT);
					$var['penyerapan'] = filter_input(INPUT_POST, 'data_t061', FILTER_SANITIZE_NUMBER_INT);

					if(strlen($var['kontrak']) > 15 || strlen($var['penyerapan']) > 15) {
						return false;
					}

					$param['table'] = $tbl_dash;
					$param['field'] = array(
						array('name' => 'n_pagu', 'result' => 'pagu'),
						array('name' => 'CONVERT(REPLACE(REPLACE(SUBSTRING_INDEX(SUBSTRING_INDEX(dp_addendum, "/", -2), "\"", -2), "\"", ""), "/", ""), signed) as addendum', 'result' => 'addendum'),
					);
					$param['where'] = array('name' => 'id', 'value' => $get_id, 'type' => 'i');

					$check = $process->select($param);
					unset($param);

					settype($check['pagu'], 'float');
					settype($var['penyerapan'], 'float');
					settype($var['kontrak'], 'float');

					if(!empty($check['addendum']))
						$temp = $check['addendum'];
					else
						$temp = $var['kontrak'];

					if($var['penyerapan'] > $temp || $var['penyerapan'] > $check['pagu'] || $var['kontrak'] > $check['pagu']) {
						return false;
					}

					$param['table'] = $tbl_dash;
					$param['field'] = array(
						array('name' => 'n_kontrak', 'value' => $var['kontrak'], 'type' => 's'),
						array('name' => 'ke_info', 'value' => $var['penyerapan'], 'type' => 's')
					);
					$param['where'] = array('name' => 'id', 'value' => $get_id, 'type' => 'i');

					if($process->update($param)){
					}
					else {
					}

				else:
					return false;
				endif;

				break;


			default:
				return false;
				exit();
				break;
		}

	else:

	endif;

else:

	errCode("EC001", "Lagi ngapain disini? tersesat ya mas/mbak?", true);

endif;
