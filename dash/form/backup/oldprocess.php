<?php
$get_in = filter_input(INPUT_GET, 'm', FILTER_SANITIZE_NUMBER_INT);

if($s_level > 3):

	$dash = 'dash';
	$cat = 'cat';
	$mem = 'member';

	$process = new dbExec($query);

	if(isset($_POST["device"])){
		$code = codeCrypt($_POST["device"]);

		switch ($code) {
			case '18': // INSERT KEGIATAN
				$get_in = 1;
				break;

			case '31': // EDIT KEGIATAN
				$get_in = 2;
				break;

			case '24': // INSERT PEKERJAAN
				$get_in = 3;
				break;

			case '66': // EDIT PEKERJAAN
				$get_in = 4;
				break;

			default:
				errCode("SB003");
				break;
		}
		

	}
	else {
		errCode("SB003");
	}

	// REQ : TITLE, STATUS = ID
	if(isset($_POST["title"], $_POST["status"]) && $get_in == 2): // UPDATE KEGIATAN

		$get_id = codeCrypt($_POST["status"], 1);
		$title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_FLAG_ENCODE_HIGH);

		if($title == "" || !$get_id){
			errCode("SB003");
		}

		$param['table'] = $cat;
		$param['field'] = array('name' => 'title', 'value' => $title, 'type' => 's');
		$param['where'] = array('name' => 'id', 'value' => $get_id, 'type' => 'i');

		if($process->update($param)){
		}
		else {
		}

		unset($param);

	elseif(isset($_POST["title"], $_POST['year']) && $get_in == 1): // INSERT KEGIATAN
		$q_cat_title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_FLAG_ENCODE_HIGH);
		$year = filter_input(INPUT_POST, 'year', FILTER_SANITIZE_NUMBER_INT);

		if($q_cat_title == "" || empty($year) || strlen($year) != 4){
			errCode("SB003");
		}

		$process = new catProcess($query);

		$process->setInfo($q_cat_title);
		if($process->insert()){

		}
		else {

		}

	// REQ : PPTK = DATA_1, PAGU = DATA_0
	elseif(isset($_POST["title"], $_POST["data_1"], $_POST["data_0"]) && $get_in == 3):	// INSERT PEKERJAAN

		$kategori = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
		$title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_FLAG_ENCODE_HIGH);
		$pptk = filter_input(INPUT_POST, 'data_1', FILTER_SANITIZE_NUMBER_INT);
		$pagu = filter_input(INPUT_POST, 'data_0', FILTER_SANITIZE_NUMBER_INT);


		if(!$get_id || $title == "" || $pptk == "" || $pagu == ""){
			errCode("SB003");
		}

		$dp_info = '"'.$title.'",""';
		$kontrak = 0;

		$param['table'] = $dash;
		$param['field'] = array(
			array('name' => 'dp_info', 'value' => $dp_info, 'type' => 's'),
			array('name' => 'lead_id', 'value' => $pptk, 'type' => 'i'),
			array('name' => 'n_pagu', 'value' => $pagu, 'type' => 's'),
			array('name' => 'kategori', 'value' => $kategori, 'type' => 'i'),
			array('name' => 'tahun', 'value' => date('Y'), 'type' => 's')
		);

		if($process->insert($param)){

		}
		else {

		}

	// REQ : PPTK = DATA_1, PAGU = DATA_0, STATUS = ID
	elseif(isset($_POST["title"], $_POST["data_0"], $_POST["data_1"], $_POST["status"]) && $get_in == 4):

		$get_id = codeCrypt($_POST["status"], 1);
		$title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_FLAG_ENCODE_HIGH);
		$pptk = filter_input(INPUT_POST, 'data_1', FILTER_SANITIZE_NUMBER_INT);
		$pagu = filter_input(INPUT_POST, 'data_0', FILTER_SANITIZE_NUMBER_INT);


		if(!$get_id || $title == "" || $pptk == "" || $pagu == ""){
			errCode("SB003");
		}

		$param['table'] = $dash;
		$param['field'] = array('name' => 'dp_info', 'result' => 'dp_info');
		$param['where'] = array('name' => 'id', 'value' => $get_id, 'type' => 'i');

		$array = cta($process->select($param)['dp_info']);
		unset($param);
		$array[0] = $title;

		$dp_info = atc($array);

		$param['table'] = $dash;
		$param['field'] = array(
			array('name' => 'dp_info', 'value' => $dp_info, 'type' => 's'),
			array('name' => 'lead_id', 'value' => $pptk, 'type' => 'i'),
			array('name' => 'n_pagu', 'value' => $pagu, 'type' => 's')
		);
		$param['where'] = array('name' => 'id', 'value' => $get_id, 'type' => 'i');

		if($process->update($param)){

		}
		else {

		}

	else:

	endif;

else:
	errCode("SB001");
endif;
