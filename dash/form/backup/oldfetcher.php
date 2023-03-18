<?php
require_once "../dir-conf.php";

require_once $access_main;
require_once $func_login;

$session = new session();

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


if($s_level > 3):

	// check var
	// statistic = mode
	if(isset($_POST["statistic"])):
		$code = codeCrypt($_POST["statistic"]);

		if(!$code){
			errCode("SB003");
		}

		// check var
		// status = id
		if(isset($_POST["status"])){
			$get_id = codeCrypt($_POST["status"], 1);

			if(!$get_id){
				errCode("SB003");
			}
		}
		else {
			errCode("404", "Page not found");
		}

		switch ($code) {
			case '59':
				$m = 0; // FETCH FOR EDIT KATEGORI
				break;

			case '94':
				$m = 1; // DELETE KATEGORI
				break;

			case '67':
				$m = 2;	// FETCH FOR EDIT PEKERJAAN
				break;

			case '30':
				$m = 3;	// HAPUS PEKERJAAN
				break;
			
			default:
				
				break;
		}

		require_once '../function.php';

		$dash = 'dash';
		$cat = 'cat';
		$mem = 'member';

		$exec = new dbExec($query);

		switch ($m) {
			case 0:
				$param['field'] = array(
				    array('name' => 'id', 'result' => 'id_kegiatan'),
				    array('name' => 'title', 'result' => 'nama_kegiatan')
				);
				$param['table'] = $cat;
				$param['where'] = array('name' => 'id', 'value' => $get_id, 'type' => 'i');

				$result = $exec->select($param);
				unset($param);

				$send['load'] = codeGen($result['id_kegiatan'], "", 1);
				$send['data'] = $result['nama_kegiatan'];
				$send['access_mode'] = codeGen("3","1");
				$send['data_0'] = codeGen(random_int(1,9),random_int(1,9));
				$send['id'] = codeGen(random_int(1,9),random_int(1,9));
				echo json_encode($send);
				break;

			case 1:
				$param['table'] = $cat;
				$param['where'] = 'id';
				$param['param'] = array('value' => $get_id, 'type' => 'i');

				$result = $exec->delete($param);

				if($result){
					echo "true";
				}
				else {
					echo "false";
				}
				break;

			case 2:

				$param['field'] = array(
				    array('name' => $dash.'.lead_id', 'result' => 'id_pptk'),
				    array('name' => $dash.'.kategori', 'result' => 'id_kegiatan'),
				    array('name' => $dash.'.dp_info', 'result' => 'info_proyek'),
				    array('name' => $cat.'.title', 'result' => 'nama_kegiatan'),
				    array('name' => $dash.'.n_pagu', 'result' => 'nilai_pagu'),
				    array('name' => $dash.'.id', 'result' => 'id_proyek'),
				);
				$param['table'] = array($dash, $cat);
				$param['join_operator'] = 'left join';
				$param['where'] = array('name' => $dash.'.id', 'value' => $get_id, 'type' => 'i');

				$param['on'] = array('name' => $dash.'.kategori', 'target' => $cat.'.id');

				$result = $exec->select($param);

				$send['load'] = codeGen($result['id_proyek'], "", 1);
				$send['data_0'] = $result['nilai_pagu'];
				$send['data_1'] = $result['id_pptk'];
				$send['data_2'] = cta($result['info_proyek'])[0];
				$send['data_x'] = codeGen(random_int(1,9),random_int(1,9));
				$send['id'] = codeGen(random_int(1,9),random_int(1,9));
				$send['access_mode'] = codeGen("6","6");

				echo json_encode($send);
				break;

			case 3:
				$param['table'] = $dash;
				$param['where'] = 'id';
				$param['param'] = array('value' => $get_id, 'type' => 'i');

				$result = $exec->delete($param);

				if($result){
					echo "true";
				}
				else {
					echo "false";
				}
				break;
			
			default:
				# code...
				break;
		}

	else:
		errCode("404", "Page not found");
	endif;

else:
	errCode("404", "Page not found");
endif;
?>