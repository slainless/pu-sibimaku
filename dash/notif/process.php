<?php
if($s_level > 1):

	$dash = 'dash';
	$cat = 'cat';
	$mem = 'member';
	$mail = 'mail';
	date_default_timezone_set("Asia/Makassar");

	$process = new dbExec($query);


	if(isset($_POST["device"], $_POST["device_statistic"], $_POST['status'], $_POST['id'], $_POST['data_mark']) && codeCrypt($_POST["device"]) == '90'):
		
		$code = codeCrypt($_POST["device_statistic"]);
		$get_id = codeCrypt($_POST["status"], 1);

		$param['table'] = $dash;
		$param['field']['name'] = 'lead_id';
		$param['field']['result'] = 'id_pptk';

		$param['where']['name'] = 'id';
		$param['where']['type'] = 'i';
		$param['where']['value'] = $get_id;

		$check = $process->select($param);
		unset($param);

		if($s_level == 3 && $check['id_pptk'] != $s_id){
			errCode("EC001", "Lagi ngapain disini? tersesat ya mas/mbak?", true);
		}

		if($s_level < 3){
			errCode("EC001", "Lagi ngapain disini? tersesat ya mas/mbak?", true);			
		}


		switch ($code) {
			case '19': // PROCESS MAIL FROM KONTRAKTOR // STATUS 0
				
				if(isset($_POST['data_x'], $_POST['data_y'])):
					$status = filter_input(INPUT_POST, 'data_y', FILTER_SANITIZE_NUMBER_INT);

					if($status !== '1' && $status !== '2'){
						errCode("EC004", "Lagi ngapain disini? tersesat ya mas/mbak?", true);	
					}

					$param['comment'] = 1;
					$param['css_expression'] = 1;
					$param['tidy'] = -1;
					$param['elements'] = 'p, span, ul, li, ol, strong, em';
					$comment = htmLawed($_POST['data_x'], $param);

					unset($param);

					$param['table'] = $mail;
					$param['where'] = array('name' => 'rel_id', 'value' => $get_id, 'type' => 'i');
					$param['field'] = array(
						array('name' => 'status', 'result' => 'status'),
						array('name' => 'target_list', 'result' => 'target_list'),
						array('name' => 'rel_id', 'result' => 'rel_id'),
						array('name' => 'target', 'result' => 'target'),
						array('name' => 'time', 'result' => 'time'),
						array('name' => 'comment', 'result' => 'comment'),
						array('name' => 'c_time', 'result' => 'c_time')
					);

					$check = $process->select($param);
					unset($param);

					if($check['status'] !== 0 && $check['status'] !== 3){
						errCode("EC004", "Lagi ngapain disini? tersesat ya mas/mbak?", true);
					}

					$temp['t_list'] = cta($check['target_list'], true);
					if($status === '1'){ // TERIMA PPTK -> PPK

						// PPK SET READ = 0, TIME NOW
						// PPTK SET READ = 1, TIME NOW

					    $temp['t_list'][1] = array(1, 1, date("Y-m-d H:i:s"));
					    $temp['t_list'][2] = array(2, 0, date("Y-m-d H:i:s"));
					    $temp['target'] = 2;

					}
					elseif($status === '2'){ // TOLAK PPTK -> KONTRAKTOR

						// PPTK SET READ = 1, TIME NOW
						// KONTRAKTOR SET READ = 0, TIME NOW

						$temp['t_list'][0] = array(0, 0, date("Y-m-d H:i:s"));
					    $temp['t_list'][1] = array(1, 1, date("Y-m-d H:i:s"));
					    $temp['target'] = 0;

					}
				    $temp['target_list'] = atc($temp['t_list'], true);

				    if($comment == ' (*) Opsional '){
				    	$comment = '';
				    }

				    $param['table'] = $mail;
				    $param['field'] = array(
				    	array('name' => 'target', 'value' => $temp['target'], 'type' => 'i'),
				    	array('name' => 'status', 'value' => $status, 'type' => 'i'),
				    	array('name' => 'target_list', 'value' => $temp['target_list'], 'type' => 's'),
				    	array('name' => 'comment', 'value' => $comment, 'type' => 's'),
				    	array('name' => 'c_time', 'value' => date("Y-m-d H:i:s"), 'type' => 's'), // UNIVERSAL TIME FOR ADMIN
				    );

				    if($status == '2'){
					    $param['field'][] = array('name' => 'f_pptk', 'value' => 1, 'type' => 'i');
					    // TOLAK, ADD FLAG PPTK = 1 / FLAG REVISI
				    }

				    $param['where'] = array('name' => 'rel_id', 'value' => $get_id, 'type' => 'i');


				    if(!$process->update($param)){
				    	errCode("EC003", "Terjadi kesalahan pada sistem.");
				    }
				    else {

				    };

				endif;

				break;


			default:
				return false;
				break;
		}

	else:

	endif;

else:

	errCode("EC001", "Lagi ngapain disini? tersesat ya mas/mbak?", true);

endif;
