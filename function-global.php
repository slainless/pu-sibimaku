<?php

	function changeDate($str, $format, $to, $translate = false){

		if(!$result = DateTime::createFromFormat($format, $str)->format($to))
			return false;

		if($translate):
			$table = translateTable('month');
			return str_replace($table['eng'], $table['id'], $result);
		endif;

		return $result;

	}

	function translateTable($str) {
		switch ($str) {
			case 'month':
				$array['eng'] = array(
					'Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec',
					'January','February','March','April','May','June','July','August','September','October','November','December',
				);		
				$array['id'] = array(
					'Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des',
					'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustut','September','Oktober','November','Desember',
				);

				return $array;
			break;
		}
	}

	function translate($str, $mode){

		switch ($mode) {
			case 'day':
				switch ($str) {
					case '1': case 'Mon': return 'Senin'; break;
					case '2': case 'Tue': return 'Selasa'; break;
					case '3': case 'Wed': return 'Rabu'; break;
					case '4': case 'Thu': return 'Kamis'; break;
					case '5': case 'Fri': return 'Jum\'at'; break;
					case '6': case 'Sat': return 'Sabtu'; break;
					case '7': case 'Sun': return 'Minggu'; break;
					default: return false; break;
				}
			break;
			
			case 'month':
				switch ($str) {
					case '1': case 'Jan': return 'Januari'; break;
					case '2': case 'Feb': return 'Februari'; break;
					case '3': case 'Mar': return 'Maret'; break;
					case '4': case 'Apr': return 'April'; break;
					case '5': case 'May': return 'Mei'; break;
					case '6': case 'Jun': return 'Juni'; break;
					case '7': case 'Jul': return 'Juli'; break;
					case '8': case 'Aug': return 'Agustus'; break;
					case '9': case 'Sep': return 'September'; break;
					case '10': case 'Oct': return 'Oktober'; break;
					case '11': case 'Nov': return 'November'; break;
					case '12': case 'Dec': return 'Desember'; break;
					default: return false; break;
				}
			break;
		}

	}

	function numtoText($int, $decimal = false){

		$int = abs($int);
		$num = array('','satu', 'dua', 'tiga', 'empat', 'lima', 'enam', 'tujuh', 'delapan', 'sembilan', 'sepuluh', 'sebelas');

		if(!$decimal):
			switch(true):
				case ($int < 12): return $num[$int]; break;
				case ($int < 100): return $int >= 20 ? $num[$int / 10].' puluh '.$num[$int % 10] : $num[$int % 10].' belas'; break;
				case ($int < 1000): return $int >= 200 ? $num[$int / 100].' ratus '.numtoText($int % 100) : 'seratus '.numtoText($int % 100); break;
				case ($int < 10000): return $int >= 2000 ? $num[$int / 1000].' ribu '.numtoText($int % 1000) : 'seribu '.numtoText($int % 1000); break;
				case ($int < 100000): return 
					$int >= 20000 
						? $num[$int / 10000].' puluh '.($int % 10000 !== 0 
							? numtoText($int % 10000) 
							: 'ribu')
						: numtoText($int / 1000).' ribu '.($int % 1000 !== 0 
							? numtoText($int % 1000) 
							: ''); 
				break;
				case ($int < 1000000): return 
					($int >= 200000 ? $num[$int / 100000].' ratus ' : 'seratus ').($int % 100000 === 0 ? 'ribu' : numtoText($int % 100000)); break;
				case ($int < 1000000000): return numtoText($int / 1000000).' juta '.numtoText(fmod($int, 1000000)); break;
				case ($int < 1000000000000): return numtoText($int / 1000000000).' miliar '.numtoText(fmod($int, 1000000000)); break;
				case ($int < 1000000000000000): return numtoText($int / 1000000000000).' triliun '.numtoText(fmod($int, 1000000000000)); break;
			endswitch;
		else:

			if(strpos($int, '.') !== false):
				$temp = explode('.', $int)[1];

				$string = '';
				$count = strlen($temp);
				for($x=0;$x<$count;$x++):
					$string .= numtoText($temp[$x]).' ';
				endfor;

				return trim(trim(numtoText($int)).' koma '.$string);

			else:
				return trim(numtoText($int));
			endif;

		endif;

		return $int;

	}

	function validate($str, $mode, $special = false){
		switch ($mode) {

			case 'year':
				settype($str, 'int');
				if(strlen($str) !== 4) return false;
				if($str > 3000 || $str < 1900) return false;
				if(empty($str)) return false;
				return true;

			case 'date':
				$str = explode("/", $str);
				if( count($str) !== 3 ||
				    strlen($str[0]) !== 2 ||
				    strlen($str[1]) !== 2 ||
				    strlen($str[2]) !== 4 ||
				    empty($str[0]) ||
				    empty($str[1]) ||
				    empty($str[2]) ||
				    $str[0] > 32 ||
				    $str[0] < 1 ||
				    $str[1] > 12 ||
				    $str[1] < 1 ||
				    $str[2] > 3000 ||
				    $str[2] < 1900
				)
					return false;
				return true;

			case 'money':
				if(strlen($str) > 15) return false;
				if(empty($str) && $str !== 0) return false;
				return true;

			default:
				# code...
			break;
		}
	}

	function tokenGen($int = 3, $int2 = 5){
		$def = 3;
		$def2 = 5;

		if($int2 > $int)
			return bin2hex(random_bytes(random_int($def, $def2)));
		elseif($int2 == $int)
			return bin2hex(random_bytes(random_int($int)));
		else
			return bin2hex(random_bytes(random_int($int, $int2)));
	}

	function interval($time, $time_compare = false, $option = false){
		if(!$option) {
			$option['s'] = "Detik";
			$option['i'] = "Mnt";
			$option['h'] = "Jam";
			$option['d'] = "Hari";
			$option['m'] = "Bln";
			$option['y'] = "Thn";
		}
		if(!$time_compare){
			$time_compare = new DateTime(date("Y-m-d H:i:s"));
		}

        date_default_timezone_set("Asia/Makassar");
        $temp[0] = $time_compare;
        $temp[1] = new DateTime($time);
        $interval = $temp[0]->diff($temp[1]);
        
        if($interval->y == 0){
            if($interval->m == 0){
                if($interval->d == 0){
                    if($interval->h == 0){
                        if($interval->i == 0){
                            if($interval->s == 0){       
                                return "1 ".$option['s'];
                            }
                            else {
                                return $interval->s." ".$option['s'];
                            }

                        }
                        else {
                            return $interval->i." ".$option['i'];
                        }
                    }
                    else {
                        return $interval->h." ".$option['h'];
                    }
                }
                else {
                    return $interval->d." ".$option['d'];
                }
            }
            else {
                return $interval->m." ".$option['m'];
            }
        }
        else {
            return $interval->y." ".$option['y'];
        }
	}
 
	function checkId($id, $ses, $tbl, $query, $debug = false){

		$process = new dbExec($query);
		// check rel_id / lead_id
		$param['table'] = $tbl;
		$param['where'] = array('name' => 'id', 'value' => $id, 'type' => 'i');
		$param['field'] = array(
			array('name' => 'rel_id', 'result' => 'rel_id'),
			array('name' => 'lead_id', 'result' => 'lead_id'),
		); 

		$check = $process->select($param);
		unset($param);
		
		if(!$check){
			if($debug) echo "001";
			else return false;
		}

		if($ses['level'] == 2 && $ses['user_id'] == $check['rel_id']){
			return true;
		}
		elseif($ses['level'] == 3 && $ses['user_id'] == $check['lead_id']){
			return true;
		}
		elseif($ses['level'] > 6){
			return true;
		}
		else {
			if($debug) echo "002";
			else return false;
		}

	}

	function joinString($array, $enclose = ''){

		$y = count($array);
		$join = '';
		for ($x=0; $x < $y; $x++) {
			$join .= $enclose.$array[$x].$enclose;
			if($x < $y){
				$join .= ',';
			}
		}
		return $join;

	}

	function pf($string, $decimal = 2) {

	    if(strpos($string, '.') !== false && $decimal !== 0){

	        $string = explode('.', $string);

	        if(strlen($string[1]) > 2){
	            $string[1] = substr($string[1], 0, $decimal);
	        }

	        if(strlen($string[0]) > 2){
	            $string[0] = substr($string[0], 0, 3);
	            $string[1] = '';

	            settype($string[0], 'int');

	            if($string[0] > 100) {
	                $string[0] = 0;
	            } 
	        }
	        else {
	            $string[1] = '.'.$string[1];
	        }

	        return $string[0].$string[1];

	    }
	    else {
	        if(strlen($string) > 3) $string = substr($string, 0, 3);

            settype($string, 'int');

            if($string > 100) {
                $string = 0;
            } 
	        return $string;
	    }

	}

	function swal($str, $desc = "", $type = "error", $timer = "3000", $token = true, $exit = true, $special = false){

		if($token) $json['token'] = $_SESSION['req_token'];

		if(is_array($type)):
			foreach ($type['param'] as $k => $v) {
				$json[$k] = $v;
			}
			$type = $type['type'];
		endif;

		$json['alert'] = array(
			'type' => $type,
			'title' => $str,
			'text' => $desc,
			'timer' => $timer
		);

		if($special === true){
			$json['alert']['confirm'] = true;
			$json['alert']['timer'] = 0;
		}


		if($exit === true):
			echo json_encode($json);
			exit();
		else:
			return $json['alert'];
		endif;
	}

	function errAjax($str){
		echo $str;
		exit();
	}

	function zeroCount($array){
		if(empty($array)){
			return 0;
		}
		else {
			return count($array);
		}
	}

	function return_bytes ($size_str)
	{
	    switch (substr ($size_str, -1))
	    {
	        case 'M': case 'm': return (int)$size_str * 1048576;
	        case 'K': case 'k': return (int)$size_str * 1024;
	        case 'G': case 'g': return (int)$size_str * 1073741824;
	        default: return $size_str;
	    }
	}


	function fa($var){
		if(!is_array($var)){
			$var = array($var);
			return $var;
		}
		else {
			return $var;
		}
	}

	function errCode($str, $desc = "", $disable = false){
		$errorCode = $str;
		$reason = $desc;
		$nobutton = $disable;
		$logout = false;

		if($nobutton){
			if(substr($errorCode, -2, 1) == '1'){
				$logout = true;
			}
		}
        require 'html/error.php';
        exit();
    }

    function maintenance(){
    	require 'html/maintenance.php';
        exit();
    }

	function customDate($string, $separator = "-"){
		$string = explode($separator, $string);
		settype($string[1], 'int');
		$month = array(
			"Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"
		);
		
		if(!isset($month[$string[1]-1])){
			return false;
		}

		return $string[0]." ".$month[$string[1]-1]." ".$string[2];
	}

	function reformDate($string, $separator = "-"){
		$string = explode($separator, $string);
		return $string[1]."/".$string[0]."/".$string[2];
	}

	function echoalt($param, $altparam, $suffix = ""){

		if(empty($param) || $param == "" || $param === 0 || $param === NULL){
			echo $altparam;
		}
		else {
			echo $param.$suffix;
		}

	}

	function is_arr_empty($array){

		$flag = 0;
		foreach($array as $key => $value){
		    if(!empty($value)){
		    	$flag = 1;
		    	break;
		    }
		}
		
		if($flag == 1){
			return false;
		}
		else {
			return true;
		}
	}

	function codeGen($slip0, $slip1, $special = 0){

		$rand = random_int(1, 4);
        $var0 = bin2hex(random_bytes($rand));
        $strvar0 = strlen($var0);

        if($special == 1):
			$strvar2 = strlen($slip0);
        endif;

        $rand = random_int(1, 4);
        $var1 = bin2hex(random_bytes($rand));
        $strvar1 = strlen($var1);

        $rand = random_int(1, 4);
        $var3 = bin2hex(random_bytes($rand));

        if($special == 0):
        	return $strvar0.$var0.$slip0.$var3.$slip1.$var1.$strvar1;
        elseif ($special == 1):
        	return $strvar2.$strvar0.$var0.$slip0.$var3.$slip0.$var1.$strvar1;
        endif;
	}

	function codeCrypt($code, $special = 0){

		$start1 = substr($code,-1,1);
		settype($start1, 'float');

		if($special == 1):
			$strvar0 = substr($code,0,1);
			$start0 = substr($code,1,1);
			settype($start0, 'float');
			settype($strvar0, 'float');

			$start0 = $start0 + 2;
			$start1 = $start1 + 1 + $strvar0;
			$start1 = 0 - $start1;

			$array[0] = substr($code,$start0,$strvar0);
			$array[1] = substr($code,$start1,$strvar0);

			if($array[0] != $array[1]):
			return false;
			endif;

			return $array[0];
		elseif($special == 0):
			$start0 = substr($code,0,1);
			settype($start0, 'float');

			$start0++;
			$start1 = $start1 + 2;
			$start1 = 0 - $start1;

			$array = substr($code,$start0,1).substr($code,$start1,1);
			return $array;
		endif;

	}

	function urlc($url, $limit){

		for($x=0; $x<$limit; $x++):
			$url = "../".$url;
		endfor;

		return $url;
	}

	function cta($array, $special = false, $filter = false){
		if($array == "" || $array === 0 || $array === NULL){
			return false;
		}

        if($special === false){
            return str_getcsv($array);
        }
        elseif($special === true){
            $array = str_getcsv($array, ";", "/");

            if($filter === true)
            	$array = array_filter($array);


            $limit = count($array);
            for($x=0;$x<$limit;$x++){
                $array2[$x] = str_getcsv($array[$x]);
            }

            return $array2;
        }
        else {
            return false;
        }
    }

	class levelCheck {
		private $margin;
		private $level;

		function __construct($leveltoCheck){
			$this->level = $leveltoCheck;
		}

		function minCheck($levelMargin){
			if($this->level > $levelMargin) {
				return true;
			}
			else {
				return false;
			}
		}

		function checkReq($levelMargin, $if, $elseif, $else = ""){
			if($this->level > $levelMargin){
		        return $if;
			}
		    elseif($this->level == $levelMargin){
		        return $elseif;
		    }
		    else{
		    	return $else;
		    }
		}
	}

	class relCheck {
		private $id;
		private $level;
		private $table = "member";

		function __construct($idtoCheck, $levelMargin){
			$this->id = $idtoCheck;
			$this->level = $levelMargin;
		}

		function check(){

			$stmtq = "SELECT id, rel_id FROM " . $this->table . " WHERE rel_id LIKE ?";

	        if($s_level > 3):
	            $qvar = "%,".$this->id;
	        elseif ($s_level == 3):
	            $qvar = $this->id.",%";
	        endif;

			if ($stmt = $this->query->prepare($stmtq)){
			    $stmt->bind_param("s", $id); 
			    $stmt->execute();
			    $stmt->store_result();
			    $stmt->bind_result($ls_id, $rel_id);
			    $stmt->fetch();

			    if($stmt->num_rows > 0){
			    	$array[0] = $ls_id;
			    	$array[1] = $rel_id;
			    	return $array;
			    }
			    else {
			    	return false;
			    }
			    $stmt->close();
			}
		}
	}

	function atc($array, $special = false){

		if($array == "" || $array == NULL || $array == 0){
			return false;
		}

    	if($special == false){
	    	$limit = count($array);
	    	$return = '';
	    	for($x=0;$x<$limit;$x++){
	    		if($limit - 1 == $x){
	    			$return = $return.'"'.$array[$x].'"';
	    		}
	    		else{
	    			$return = $return.'"'.$array[$x].'",';
	    		}
	    	}
	    	return $return;
	    }
	    elseif($special == true){
	    	$limit = count($array);
	    	$return = '';

		    for($x=0;$x<$limit;$x++){

	    		$return2 = '';
		  		$limit2 = count($array[0]);
		    	for($y=0;$y<$limit2;$y++){
		    		if($limit2 - 1 == $y){
		    			$return2 = $return2.'"'.$array[$x][$y].'"';
		    		}
		    		else{
		    			$return2 = $return2.'"'.$array[$x][$y].'",';
		    		}
		    	}

	    		if($limit - 1 == $x){
	    			$return = $return.'/'.$return2.'/';
	    		}
	    		else{
	    			$return = $return.'/'.$return2.'/;';
	    		}
	    	}
	    	return $return;	    
	    }
	    else{
	    	return false;
	    }
    }

    function af($array, $special = 0){
    	$limit = count($array);
    	$return = true;
	    for($x=0;$x<$limit;$x++){
	    	if($array[$x] != ""){
		    	if(!filter_var($array[$x], FILTER_VALIDATE_FLOAT)){
		    		$return = false;
		    	}
		    }
	    }

	    if($return == false){
	    	return false;
	    }
	    else {
	    	return true;
	    }
    }