<?php
$get_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
$get_in = filter_input(INPUT_GET, 'm', FILTER_SANITIZE_NUMBER_INT);

if($s_level > 2):
	if(isset(
		$_POST['dp_info_0'],
		$_POST['dp_info_1'],
		$_POST['dp_info_2'],
		$_POST['dp_info_3'],
		$_POST['dp_kontrak_0'],
		$_POST['dp_kontrak_1'],
		$_POST['dp_kontrak_2'],
		$_POST['dp_kontrak_3'],
		$_POST['dp_pelaksana_0'],
		$_POST['dp_pelaksana_1'],
		$_POST['dp_pelaksana_2'],
		$_POST['dp_pelaksana_3'],
		$_POST['dp_pelaksana_4'],
		$_POST['dp_surat_0'],
		$_POST['dp_surat_1'],
		$_POST['dp_surat_2'],
		$_POST['dp_surat_3'],
		$_POST['dp_surat_4'],
		$_GET['id'],
		$_GET['m']
	) && $_GET['m'] == 1):

	    $q_dp_info_0 = filter_input(INPUT_POST, 'dp_info_0', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_FLAG_ENCODE_HIGH);
	    $q_dp_info_1 = filter_input(INPUT_POST, 'dp_info_1', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_FLAG_ENCODE_HIGH);
	    $q_dp_info_2 = filter_input(INPUT_POST, 'dp_info_2', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_FLAG_ENCODE_HIGH);
	    $q_dp_info_3 = filter_input(INPUT_POST, 'dp_info_3', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_FLAG_ENCODE_HIGH);

	    $q_dp_kontrak_0 = filter_input(INPUT_POST, 'dp_kontrak_0', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_FLAG_ENCODE_HIGH);
	    $q_dp_kontrak_1 = filter_input(INPUT_POST, 'dp_kontrak_1', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_FLAG_ENCODE_HIGH);
	    $q_dp_kontrak_2 = filter_input(INPUT_POST, 'dp_kontrak_2', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_FLAG_ENCODE_HIGH);
	    $q_dp_kontrak_3 = filter_input(INPUT_POST, 'dp_kontrak_3', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_FLAG_ENCODE_HIGH);

		$q_dp_pelaksana_0 = filter_input(INPUT_POST, 'dp_pelaksana_0', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_FLAG_ENCODE_HIGH);
	    $q_dp_pelaksana_1 = filter_input(INPUT_POST, 'dp_pelaksana_1', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_FLAG_ENCODE_HIGH);
	    $q_dp_pelaksana_2 = filter_input(INPUT_POST, 'dp_pelaksana_2', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_FLAG_ENCODE_HIGH);
	    $q_dp_pelaksana_3 = filter_input(INPUT_POST, 'dp_pelaksana_3', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_FLAG_ENCODE_HIGH);
	    $q_dp_pelaksana_4 = filter_input(INPUT_POST, 'dp_pelaksana_4', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_FLAG_ENCODE_HIGH);

	    $q_dp_surat_0 = filter_input(INPUT_POST, 'dp_surat_0', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_FLAG_ENCODE_HIGH);
	    $q_dp_surat_1 = filter_input(INPUT_POST, 'dp_surat_1', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_FLAG_ENCODE_HIGH);
	    $q_dp_surat_2 = filter_input(INPUT_POST, 'dp_surat_2', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_FLAG_ENCODE_HIGH);
	    $q_dp_surat_3 = filter_input(INPUT_POST, 'dp_surat_3', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_FLAG_ENCODE_HIGH);
	    $q_dp_surat_4 = filter_input(INPUT_POST, 'dp_surat_4', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_FLAG_ENCODE_HIGH);

	    $q_dp_info = array(
	    	$q_dp_info_0,
	    	$q_dp_info_1,
	    	$q_dp_info_2,
	    	$q_dp_info_3
	    );

	    $q_dp_kontrak = array(
			array(
				$q_dp_kontrak_0,
				$q_dp_kontrak_1
			),
			array(
				$q_dp_kontrak_2,
				$q_dp_kontrak_3
			)
	    );

	    $q_dp_pelaksana = array(
		    $q_dp_pelaksana_0,
			$q_dp_pelaksana_1,
			$q_dp_pelaksana_2,
			$q_dp_pelaksana_3,
			$q_dp_pelaksana_4
		);

	    $q_dp_surat = array(
			$q_dp_surat_0,
			$q_dp_surat_1,
			$q_dp_surat_2,
			$q_dp_surat_3,
			$q_dp_surat_4
		);

	    $q_dp_info = atc($q_dp_info);
	    $q_dp_kontrak = atc($q_dp_kontrak, true);
	    $q_dp_pelaksana = atc($q_dp_pelaksana);
	    $q_dp_surat = atc($q_dp_surat);

	    $process = new dashbProcess($query);

	    $process->setInfo($q_dp_info, $q_dp_kontrak, $q_dp_pelaksana, $q_dp_surat, $get_id);
	    if($process->update(1)){

	    }
	    else {

	    }

	elseif(isset(
		$_POST['pf_div'],
		$_POST['pf_title'],		
		$_POST['pf_kontrak'],		
		$_POST['pf_bln_1'],		
		$_POST['pf_bln_2'],		
		$_POST['pf_bln_3'],
		$_GET['id'],
		$_GET['m']
	) && $_GET['m'] == 2):
		$q_pf_div = filter_input(INPUT_POST, 'pf_div', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY | FILTER_FLAG_ENCODE_HIGH);
		$q_pf_title = filter_input(INPUT_POST, 'pf_title', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY | FILTER_FLAG_ENCODE_HIGH);
		$q_pf_kontrak = filter_input(INPUT_POST, 'pf_kontrak', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_REQUIRE_ARRAY | FILTER_FLAG_ALLOW_THOUSAND | FILTER_FLAG_ALLOW_FRACTION);
		$q_pf_bln_1 = filter_input(INPUT_POST, 'pf_bln_1', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_REQUIRE_ARRAY | FILTER_FLAG_ALLOW_THOUSAND | FILTER_FLAG_ALLOW_FRACTION);
		$q_pf_bln_2 = filter_input(INPUT_POST, 'pf_bln_2', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_REQUIRE_ARRAY | FILTER_FLAG_ALLOW_THOUSAND | FILTER_FLAG_ALLOW_FRACTION);
		$q_pf_bln_3 = filter_input(INPUT_POST, 'pf_bln_3', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_REQUIRE_ARRAY | FILTER_FLAG_ALLOW_THOUSAND | FILTER_FLAG_ALLOW_FRACTION);

		$compare = array(count($q_pf_title), count($q_pf_kontrak), count($q_pf_bln_1), count($q_pf_bln_2), count($q_pf_bln_3), count($q_pf_div));
		if(count(array_unique($compare)) !== 1) {
		    return false;
		}

		$q_pf_kontrak = str_replace(",",".",$q_pf_kontrak);
		$q_pf_bln_1 = str_replace(",",".",$q_pf_bln_1);
		$q_pf_bln_2 = str_replace(",",".",$q_pf_bln_2);
		$q_pf_bln_3 = str_replace(",",".",$q_pf_bln_3);

		if(!(af($q_pf_kontrak) && af($q_pf_bln_1) && af($q_pf_bln_2) && af($q_pf_bln_3))){
			return false;
		}

		$q_pf_kontrak = str_replace(".",",",$q_pf_kontrak);
		$q_pf_bln_1 = str_replace(".",",",$q_pf_bln_1);
		$q_pf_bln_2 = str_replace(".",",",$q_pf_bln_2);
		$q_pf_bln_3 = str_replace(".",",",$q_pf_bln_3);

    	$limit = count($q_pf_title);
	    for($x=0;$x<$limit;$x++){
	    	$q_pf_info[$x][0] = $q_pf_div[$x];
	    	$q_pf_info[$x][1] = $q_pf_title[$x];
	    	$q_pf_info[$x][2] = $q_pf_kontrak[$x];
	    	$q_pf_info[$x][3] = $q_pf_bln_1[$x];
	    	$q_pf_info[$x][4] = $q_pf_bln_2[$x];
	    	$q_pf_info[$x][5] = $q_pf_bln_3[$x];
	    }

	    $q_pf_info = atc($q_pf_info, true);

	    $process = new dashbProcess($query);

	    $process->setPf($q_pf_info, $get_id);
	    if($process->update(2)){

	    }
	    else {

	    }

	elseif(isset(
		$_POST['startm'],
		$_POST['endm'],		
		$_POST['mp_kum_1'],			
		$_POST['mp_kum_2'],		
		$_POST['mp_kum_3'],
		$_GET['id'],
		$_GET['m']
	) && $_GET['m'] == 3):
		$q_startm = filter_input(INPUT_POST, 'startm', FILTER_SANITIZE_NUMBER_INT);
		$q_endm = filter_input(INPUT_POST, 'endm', FILTER_SANITIZE_NUMBER_INT);
		$q_mp_kum_1 = filter_input(INPUT_POST, 'mp_kum_1', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_REQUIRE_ARRAY | FILTER_FLAG_ALLOW_THOUSAND | FILTER_FLAG_ALLOW_FRACTION);
		$q_mp_kum_2 = filter_input(INPUT_POST, 'mp_kum_2', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_REQUIRE_ARRAY | FILTER_FLAG_ALLOW_THOUSAND | FILTER_FLAG_ALLOW_FRACTION);
		$q_mp_kum_3 = filter_input(INPUT_POST, 'mp_kum_3', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_REQUIRE_ARRAY | FILTER_FLAG_ALLOW_THOUSAND | FILTER_FLAG_ALLOW_FRACTION);

		$compare = array(count($q_mp_kum_1), count($q_mp_kum_2), count($q_mp_kum_3));
		if(count(array_unique($compare)) !== 1) {
		    return false;
		}

		$q_mp_kum_1 = str_replace(",",".",$q_mp_kum_1);
		$q_mp_kum_2 = str_replace(",",".",$q_mp_kum_2);
		$q_mp_kum_3 = str_replace(",",".",$q_mp_kum_3);

		if(!(af($q_mp_kum_1) && af($q_mp_kum_2) && af($q_mp_kum_3))){
			return false;
		}

		$q_mp_kum_1 = str_replace(".",",",$q_mp_kum_1);
		$q_mp_kum_2 = str_replace(".",",",$q_mp_kum_2);
		$q_mp_kum_3 = str_replace(".",",",$q_mp_kum_3);


		$q_startm--;
		$y=1;
		for($x=$q_startm;$x<$q_endm;$x++){
			$q_mp_kum_0[$x] = $y;
			$y++;
		}

    	$q_mp_info = array(
    		$q_mp_kum_0,
    		$q_mp_kum_1,
    		$q_mp_kum_2,
    		$q_mp_kum_3,
    	);

    	$q_mp_info = array_map(null, ...$q_mp_info);
	    $q_mp_info = atc($q_mp_info, true);

	    $process = new dashbProcess($query);

	    $process->setMp($q_mp_info, $get_id);
	    if($process->update(3)){

	    }
	    else {

	    }

	elseif(isset(
		$_POST['rp_info_prg'],			
		$_POST['rp_info_prg_c'],		
		$_POST['rp_info_waktu'],
		$_POST['rp_info_waktu_c'],	
		$_GET['id'],
		$_GET['m']
	) && $_GET['m'] == 4):
		$q_rp_info_prg = filter_input(INPUT_POST, 'rp_info_prg', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_REQUIRE_ARRAY | FILTER_FLAG_ALLOW_THOUSAND | FILTER_FLAG_ALLOW_FRACTION);
		$q_rp_info_prg_c = filter_input(INPUT_POST, 'rp_info_prg_c', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_REQUIRE_ARRAY | FILTER_FLAG_ALLOW_THOUSAND | FILTER_FLAG_ALLOW_FRACTION);
		$q_rp_info_waktu_c = filter_input(INPUT_POST, 'rp_info_waktu_c', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_REQUIRE_ARRAY | FILTER_FLAG_ALLOW_THOUSAND | FILTER_FLAG_ALLOW_FRACTION);
		$q_rp_info_waktu = filter_input(INPUT_POST, 'rp_info_waktu', FILTER_SANITIZE_NUMBER_INT, FILTER_REQUIRE_ARRAY);

		if(count($q_rp_info_prg) != 4 || count($q_rp_info_prg_c) != 2 || count($q_rp_info_waktu) != 3 || count($q_rp_info_waktu_c) != 2) {
		    return false;
		}

		$q_rp_info_prg = str_replace(",",".",$q_rp_info_prg);
		$q_rp_info_prg_c = str_replace(",",".",$q_rp_info_prg_c);
		$q_rp_info_waktu_c = str_replace(",",".",$q_rp_info_waktu_c);

		if(!(af($q_rp_info_prg) && af($q_rp_info_prg_c) && af($q_rp_info_waktu_c))){
			return false;
		}

		$q_rp_info_prg = str_replace(".",",",$q_rp_info_prg);
		$q_rp_info_prg_c = str_replace(".",",",$q_rp_info_prg_c);
		$q_rp_info_waktu_c = str_replace(".",",",$q_rp_info_waktu_c);

	    $q_rp_info_prg = atc($q_rp_info_prg);
	    $q_rp_info_prg_c = atc($q_rp_info_prg_c);
	    $q_rp_info_waktu = atc($q_rp_info_waktu);
	    $q_rp_info_waktu_c = atc($q_rp_info_waktu_c);

	    $process = new dashbProcess($query);

	    $process->setRp($q_rp_info_prg, $q_rp_info_prg_c, $q_rp_info_waktu, $q_rp_info_waktu_c, $get_id);
	    if($process->update(4)){

	    }
	    else {

	    }

	elseif(isset(
		$_POST['ke_info'],			
		$_POST['ke_c'],		
		$_GET['id'],
		$_GET['m']
	) && $_GET['m'] == 5):
		$q_ke_info = filter_input(INPUT_POST, 'ke_info', FILTER_SANITIZE_NUMBER_INT, FILTER_REQUIRE_ARRAY);
		$q_ke_c = filter_input(INPUT_POST, 'ke_c', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_REQUIRE_ARRAY | FILTER_FLAG_ALLOW_THOUSAND | FILTER_FLAG_ALLOW_FRACTION);

		if(count($q_ke_info) != 3 || count($q_ke_c) != 2) {
		    return false;
		}

		$q_ke_c = str_replace(",",".",$q_ke_c);

		if(!(af($q_ke_c))){
			return false;
		}

		$q_ke_c = str_replace(".",",",$q_ke_c);

	    $q_ke_info = atc($q_ke_info);
	    $q_ke_c = atc($q_ke_c);

	    $process = new dashbProcess($query);

	    $process->setKe($q_ke_info, $q_ke_c, $get_id);
	    if($process->update(5)){

	    }
	    else {

	    }

	elseif(isset(
		$_POST['mk_info'],				
		$_GET['id'],
		$_GET['m']
	) && $_GET['m'] == 7):
		$q_mk_info = filter_input(INPUT_POST, 'mk_info', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_REQUIRE_ARRAY | FILTER_FLAG_ALLOW_THOUSAND | FILTER_FLAG_ALLOW_FRACTION);

		if(count($q_mk_info) != 3) {
		    return false;
		}

		$q_mk_info = str_replace(",",".",$q_mk_info);

		if(!(af($q_mk_info))){
			return false;
		}

		$q_mk_info = str_replace(".",",",$q_mk_info);

	    $q_mk_info = atc($q_mk_info);

	    $process = new dashbProcess($query);

	    $process->setMk($q_mk_info, "", "", $get_id);
	    if($process->update(7)){

	    }
	    else {

	    }

	elseif(isset(
		$_POST['na_nama'],
		$_POST['na_ket'],		
		$_GET['id'],
		$_GET['m']
	) && $_GET['m'] == 8):
		$q_na_nama = filter_input(INPUT_POST, 'na_nama', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY | FILTER_FLAG_ENCODE_HIGH);
		$q_na_ket = filter_input(INPUT_POST, 'na_ket', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY | FILTER_FLAG_ENCODE_HIGH);

		$compare = array(count($q_na_nama), count($q_na_ket));
		if(count(array_unique($compare)) !== 1) {
		    return false;
		}

	    $process = new dashbProcess($query);

	    $array = $process->fetch($get_id, 8)[0][2];
	    $array = cta($array, true);

	    $limit = count($array);
	    for($x=0;$x<$limit;$x++){
	    	$q_na_pos[$x] = $array[$x][0];
	    }

	    $q_na_info = array(
    		$q_na_pos,
    		$q_na_nama,
    		$q_na_ket,
    	);

    	$q_na_info = array_map(null, ...$q_na_info);
    	$q_na_info = atc($q_na_info, true);

		$process->setNa($q_na_info, $get_id);
	    if($process->update(8)){

	    }
	    else {

	    }

	 elseif(isset(
		$_POST['kontraktor'],	
		$_GET['id']
	) && $_GET['m'] == 9):
		$q_kontraktor = filter_input(INPUT_POST, 'kontraktor', FILTER_SANITIZE_NUMBER_INT);

	    $process = new dashbProcess($query);
	    if($process->updateKontraktor($get_id, $q_kontraktor)){

	    }
	    else {

	    }

	else:
		echo "ERROR";
	endif;

else:

endif;
