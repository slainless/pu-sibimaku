<?php

class dbExec {

    private $query;

    function __construct($query){
        $this->query = $query;
    }

    function freeQuery($stmtq, $param, $debug = false){
        if($debug) echo $stmtq;
        if ($stmt = $this->query->prepare($stmtq)){

            if(isset($param['param'])):
                $var['bind_param'][] = $param['param']['type'];
                if(!isset($param['param']['value'][0])) $param['param']['value'] = array($param['param']['value']);

                $y = count($param['param']['value']);
                for($x=0;$x<$y;$x++){

                    $temp[$x] = $param['param']['value'][$x];
                    $var['bind_param'][] = &$temp[$x];

                }

                if($debug) var_dump($var['bind_param']);
                call_user_func_array(array($stmt,'bind_param'),$var['bind_param']);

            endif;

            $stmt->execute();
            $stmt->store_result();

            if($debug) var_dump($stmt);

            if($stmt->affected_rows === 0){
                $stmt->close();
                return false;
            }

            if(isset($param['result'])):

                if(!isset($param['result'][0])) $param['result'] = array($param['result']);

                unset($temp);
                $y = count($param['result']);
                for($x=0;$x<$y;$x++){
                    ${$param['result'][$x]} = null;
                    $temp[$param['result'][$x]] = &${$param['result'][$x]};
                }

                if($debug) var_dump($temp);
                call_user_func_array(array($stmt,'bind_result'),$temp);

                if($stmt->num_rows() == 1){
                    $stmt->fetch();
                    $array = $temp;
                }
                else {
                    $x = 0;
                    $z = count($param['result']);

                    while($stmt->fetch()){

                        for($y=0;$y<$z;$y++){
                            $array[$param['result'][$y]][$x] = ${$param['result'][$y]};
                        }
                        $x++;

                    }
                }

                
                if(is_arr_empty($array)){
                    $stmt->close();
                    return false;
                }
                else {
                    $stmt->close();
                    return $array; 
                }
            endif;

            $stmt->close();
            return true;
        }
        else {
            return false;
        }

    }

    function select($param, $debug = false){

        // QUERY CONSTRUCTOR
        $stmtq = "SELECT";

        // ############################################### //
        // QUERY CONSTRUCTOR START
        //
        
        if(!isset($param['table'])) return "isset(table) return false!";
        if(!isset($param['field'])) return "isset(field) return false!";
        
        if(is_array($param['table']) && !isset($param['join_op'], $param['on'])){
            return "is_array(table) && isset(join_op, on) return false!";
        }

        if(!isset($param['field'][0])) $param['field'] = array($param['field']);


        $y = count($param['field']);
        $z = ', ';
        for($x=0;$x<$y;$x++){
            if($x == $y - 1){
                $z = '';
            }
            $stmtq .= " ".$param['field'][$x]['name'].$z;
        }

        if(is_array($param['table']) && isset($param['join_op'], $param['on'])){

            if(!isset($param['join_op'][0]) || !is_array($param['join_op'])) $param['join_op'] = array($param['join_op']);
            if(!isset($param['on'][0])) $param['on'] = array($param['on']);

        	$y = count($param['table']);
        	$stmtq .= " FROM ".$param['table'][0];
	        for($x=1;$x<$y;$x++){
	           $stmtq .=  " ".$param['join_op'][$x-1]." ".$param['table'][$x]." ON ".$param['on'][$x-1]['name']." = ".$param['on'][$x-1]['target'];
	        }
	    }
	    else {
        	$stmtq .= " FROM ".$param['table'];	    	
	    }

        if(isset($param['where'])){
            	
            if(isset($param['where_op'])){
            	$y = count($param['where']);
            	$stmtq .= " WHERE ".$param['where'][0]['name']." = ?";
            	for($x=1;$x<$y;$x++){
            		$stmtq .= " ".$param['where_op'][$x-1]." ".$param['where'][$x]['name']." = ?";
            	}
            }
            else {
            	$stmtq .= " WHERE ".$param['where']['name']." = ?";
            }

        }

        if(isset($param['where_in'])){

        	if(!isset($param['where_in']['operator'])){
        		$stmtq .= " WHERE ";
        	}
        	else {
        		$stmtq .= " ".$param['where_in']['operator'];
        	}

        	$stmtq .= " ".$param['where_in']['name']." IN (".$param['where_in']['query'].")";

        }

        if(isset($param['where_like'])){

            if(!isset($param['where_like']['operator'])){
                $stmtq .= " WHERE ";
            }
            else {
                $stmtq .= " ".$param['where_like']['operator'];
            }

            $stmtq .= " ".$param['where_like']['name']." LIKE ?";

        }

        if(isset($param['group'])){
            $stmtq .= " GROUP BY ".$param['group'];
        }

        if(isset($param['order'])){
            $stmtq .= " ORDER BY ".$param['order']['name']." ".$param['order']['sort'];
        }

        if(isset($param['limit'])){
            $stmtq .= " LIMIT ?";
        }

        if(isset($param['offset'])){
            $stmtq .= ", ?";
        }

        if($debug === true):
            echo $stmtq;
            var_dump($param);
        endif;

        if ($stmt = $this->query->prepare($stmtq)){
            if($debug === true){ echo "query success"; }

            $param['bind_param'][0] = '';

            if(isset($param['where'])){

                if(!isset($param['where'][0])) $param['where'] = array($param['where']);

                $y = count($param['where']);
                for($x=0;$x<$y;$x++){
                    $param['bind_param'][0] .= $param['where'][$x]['type']; 
                }

                for($x=0;$x<$y;$x++){
                    $temp[$x] = $param['where'][$x]['value']; 
                    $param['bind_param'][] = &$temp[$x];
                }

            }

            if(isset($param['where_in'])){

                $param['bind_param'][0] .= $param['where_in']['type']; 

                
                $temp[] = $param['where_in']['value']; 
                $y = count($temp)-1;
                $param['bind_param'][] = &$temp[$y];

            }

            if(isset($param['where_like'])){

                $param['bind_param'][0] .= $param['where_like']['type']; 

                
                $temp[] = $param['where_like']['value']; 
                $y = count($temp)-1;
                $param['bind_param'][] = &$temp[$y];

            }

            if(isset($param['limit'])){

                $param['bind_param'][0] .= $param['limit']['type']; 

                $temp[] = $param['limit']['value'];
                $y = count($temp)-1;
                $param['bind_param'][] = &$temp[$y];

            }

            if(isset($param['offset'])){

                $param['bind_param'][0] .= $param['offset']['type']; 

                $temp[] = $param['offset']['value'];
                $y = count($temp)-1;
                $param['bind_param'][] = &$temp[$y];

            }

            if($debug === true):
                var_dump($temp);
                var_dump($param['bind_param']);
            endif;

            if(!empty($param['bind_param'][0])) call_user_func_array(array($stmt,'bind_param'),$param['bind_param']);


            $stmt->execute();
            $stmt->store_result();

            unset($temp);
            $y = count($param['field']);
            for($x=0;$x<$y;$x++){
                ${$param['field'][$x]['result']} = null;
                $temp[$param['field'][$x]['result']] = &${$param['field'][$x]['result']};
            }

            call_user_func_array(array($stmt,'bind_result'),$temp);

            if($stmt->num_rows() == 1 && !isset($param['force_array'])){
            	$stmt->fetch();
            	$array = $temp;
            }
            else {
            	$x = 0;
            	$z = count($param['field']);
            	while($stmt->fetch()){
            		for($y=0;$y<$z;$y++){
            			$array[$param['field'][$y]['result']][$x] = ${$param['field'][$y]['result']};
            		}
    				$x++;
				}
            }

            if($debug === true){ var_dump($stmt); }

            $stmt->close();
            if(is_arr_empty($temp)){
                return false;
            }
            else {
                return $array;

            }
        }
        else {
        	return false;
        }

    }


    function update($param){

        $stmtq = "UPDATE";

        if(isset($param['where']) && !isset($param['where'][0])){
        	$param['where'] = array($param['where']);
        }

        if(!isset($param['table'][0])){
        	$param['table'] = array($param['table']);
        }

        if(!isset($param['field'][0])){
        	$param['field'] = array($param['field']);
        }

        if(isset($param['on']) && !isset($param['on'][0])){
        	$param['on'] = array($param['on']);
        }

        if(isset($param['join_operator']) && !is_array($param['join_operator'])){
        	$param['join_operator'] = array($param['join_operator']);
        }

        if(isset($param['on'])){

        	$y = count($param['table']);
        	$stmtq .= " ".$param['table'][0];
	        for($x=1;$x<$y;$x++){
	            $stmtq .=  " ".$param['join_operator'][$x-1]." ".$param['table'][$x]." ON ".$param['on'][$x-1]['name']." = ".$param['on'][$x-1]['target'];
	        }
	    }
	    else {
        	$stmtq .= " ".$param['table'];	    	
	    }

        $stmtq .= " SET ";

        $y = count($param['field']);
        $z = ', ';
        for($x=0;$x<$y;$x++){
            if($x == $y - 1){
                $z = '';
            }
            $stmtq .= " ".$param['field'][$x]['name']." = ? ".$z;
        }

        if(isset($param['where'])){
            	
            if(isset($param['where_operator'])){
            	$y = count($param['where']);
            	$stmtq .= " WHERE ".$param['where'][0]['name']." = ?";
            	for($x=1;$x<$y;$x++){
            		$stmtq .= " ".$param['where_operator'][$x-1]." ".$param['where'][$x]['name']." = ?";
            	}
            }
            else {
            	$stmtq .= " WHERE ".$param['where'][0]['name']." = ?";
            }

        }

        if ($stmt = $this->query->prepare($stmtq)){
        	$y = count($param['field']);
        	$param['param_type'] = '';
    		for($x=0;$x<$y;$x++){
    			$param['field'][$x]['type'];
    			$param['param_type'] .= $param['field'][$x]['type'];
    			$z++;
    		}

        	if(isset($param['where'])){
        		$y = count($param['where']);

        		for($x=0;$x<$y;$x++){
        			$param['param_type'] .= $param['where'][$x]['type'];
        		}
	        }

	        $param['param_array'][0] = $param['param_type'];

        	$y = count($param['field']);
	        for($x=0;$x<$y;$x++){
        		$temp[$x] = $param['field'][$x]['value'];
        		$param['param_array'][] = &$temp[$x];
    		}

    		unset($temp);

	        $y = count($param['where']);
    		for($x=0;$x<$y;$x++){

        		$temp[$x] = $param['where'][$x]['value'];
        		$param['param_array'][] = &$temp[$x];
    		}

	   	    call_user_func_array(array($stmt,'bind_param'),$param['param_array']);     	

            $stmt->execute();
            $stmt->store_result();

            if($stmt->affected_rows === 0){
                return false;
            }

            $stmt->close();
            return true;
        }
        else {
        	return false;
        }

    }


    function insert($param){

        $stmtq = "INSERT INTO ";

/*        if(!isset($param['table'][0])){
        	$param['table'] = array($param['table']);
        }*/

        if(!isset($param['field'][0])){
        	$param['field'] = array($param['field']);
        }

/*        if(isset($param['on']) && !isset($param['on'][0])){
        	$param['on'] = array($param['on']);
        }*/
/*
        if(isset($param['join_operator']) && !is_array($param['join_operator'])){
        	$param['join_operator'] = array($param['join_operator']);
        }*/

/*        if(isset($param['on'])){

        	$y = count($param['table']);
        	$stmtq .= " ".$param['table'][0];
	        for($x=1;$x<$y;$x++){
	            $stmtq .=  " ".$param['join_operator'][$x-1]." ".$param['table'][$x]." ON ".$param['on'][$x-1]['name']." = ".$param['on'][$x-1]['target'];
	        }
	    }
	    else {
        	$stmtq .= " ".$param['table'];	    	
	    }*/

        $stmtq .= $param['table']." (";

        $y = count($param['field']);
        $z = ', ';
        for($x=0;$x<$y;$x++){
            if($x == $y - 1){
                $z = ')';
            }
            $stmtq .= $param['field'][$x]['name'].$z;
        }

        $stmtq .= " VALUES (";

        $y = count($param['field']);
        $z = ', ';
        for($x=0;$x<$y;$x++){
            if($x == $y - 1){
                $z = ')';
            }
            $stmtq .= "?".$z;
        }

        if ($stmt = $this->query->prepare($stmtq)){
        	$y = count($param['field']);
        	$param['param_type'] = '';
    		for($x=0;$x<$y;$x++){
    			$param['field'][$x]['type'];
    			$param['param_type'] .= $param['field'][$x]['type'];
    			$z++;
    		}

	        $param['param_array'][0] = $param['param_type'];

        	$y = count($param['field']);
	        for($x=0;$x<$y;$x++){
        		$temp[$x] = $param['field'][$x]['value'];
        		$param['param_array'][] = &$temp[$x];
    		}
    		unset($temp);

	   	    call_user_func_array(array($stmt,'bind_param'),$param['param_array']);     	

            $stmt->execute();

            if($stmt->affected_rows === 0){
                return false;
            }

            $stmt->close();
            return true;
        }
        else {
        	return false;
        }

    }

    function delete($param){

        $stmtq = "DELETE FROM ";

        $stmtq .= $param['table']." WHERE ".$param['where']['name']." = ?";

        if ($stmt = $this->query->prepare($stmtq)){
            $stmt->bind_param($param['where']['type'], $param['where']['value']); 
            $stmt->execute();

            if($stmt->affected_rows === 0){
                return false;
            }

            $stmt->close();
            return true;
        }
        else {
            return false;
        }

    }
}

class dashb {
	protected $query;
    protected $table = "dash";

    function __construct($db) {
        $this->query = $db;
    }
}

class dashbProcess extends dashb {

	private $timezone = "Asia/Makassar";
	private $table2 = "member";
	private $table3 = "cat";

	private $n_kontrak;
	private $n_pagu;
	private $dp_info;
	private $dp_kontrak;
	private $dp_pelaksana;
	private $dp_surat;
	private $q_id;

	private $pf_info;
	private $mp_info;

	private $rp_info_prg;
	private $rp_info_prg_c;
	private $rp_info_waktu;
	private $rp_info_waktu_c;

	private $ke_info; 
	private $ke_c; 

	private $gallery; 

	private $mk_info; 
	private $mk_c_xy; 
	private $mk_c_info; 

	private $na_info;

	function setInfo($q_kontrak, $q_pagu, $q_dp_info, $q_dp_kontrak, $q_dp_pelaksana, $q_dp_surat, $q_q_id) {
		$this->dp_info = $q_dp_info;		
		$this->dp_kontrak = $q_dp_kontrak;		
		$this->dp_pelaksana = $q_dp_pelaksana;		
		$this->dp_surat = $q_dp_surat;		
		$this->q_id = $q_q_id;
		$this->$n_kontrak = $q_kontrak;
		$this->$n_pagu = $q_pagu;		
	}

	function setPf($q_pf_info, $q_q_id) {
		$this->pf_info = $q_pf_info;
		$this->q_id = $q_q_id;
	}

	function setMp($q_mp_info, $q_q_id) {
		$this->mp_info = $q_mp_info;
		$this->q_id = $q_q_id;
	}

	function setRp($q_rp_info_prg, $q_rp_info_prg_c, $q_rp_info_waktu, $q_rp_info_waktu_c, $q_q_id) {
		$this->rp_info_prg = $q_rp_info_prg;
		$this->rp_info_prg_c = $q_rp_info_prg_c;
		$this->rp_info_waktu = $q_rp_info_waktu;
		$this->rp_info_waktu_c = $q_rp_info_waktu_c;
		$this->q_id = $q_q_id;		
	}

	function setKe($q_ke_info, $q_ke_c, $q_q_id) {
		$this->ke_info = $q_ke_info;
		$this->ke_c = $q_ke_c;
		$this->q_id = $q_q_id;
	}

	function setGa($q_gallery, $q_q_id) {
		$this->gallery = $q_gallery;
		$this->q_id = $q_q_id;
	}

	function setMk($q_mk_info, $q_mk_c_xy, $q_mk_c_info, $q_q_id) {
		$this->mk_info = $q_mk_info;
		$this->mk_c_xy = $q_mk_c_xy;
		$this->mk_c_info = $q_mk_c_info;
		$this->q_id = $q_q_id;
	}

	function setNa($q_na_info, $q_q_id) {
		$this->na_info = $q_na_info;
		$this->q_id = $q_q_id;
	}

	function fetchMember($level) {

		$stmtq = "SELECT id, name FROM ".$this->table2." WHERE level = ?";

		if ($stmt = $this->query->prepare($stmtq)){

			$stmt->bind_param("i", $level);
		    $stmt->execute();
		    $stmt->bind_result($r_id, $r_name);

		    $x = 0;
		    while($stmt->fetch()){
		    	$array[$x][0] = $r_id;
		    	$array[$x][1] = $r_name;

		    	$x++;
		    }
		    $stmt->close();
		    return $array;
		}

	}

	function insert($n_kontrak, $n_pagu, $dp_info, $pptk, $kategori){

		$stmtq = "INSERT INTO ".$this->table." (n_kontrak, n_pagu, dp_info, lead_id, kategori) VALUES (?, ?, ?, ?, ?)";
	    if($stmt = $this->query->prepare($stmtq)) {

			$stmt->bind_param('sssss', $n_kontrak, $n_pagu, $dp_info, $pptk, $kategori);
			$stmt->execute();

	    	$stmt->close();
	    	return true;
		}
		else {
			return false;
		}

	}

	function updateSpecial($id, $title, $pptk, $pagu){

		$stmtq = "SELECT dp_info FROM ".$this->table." WHERE id = ?";

		if ($stmt = $this->query->prepare($stmtq)){

			$stmt->bind_param("i", $id);
		    $stmt->execute();

		    $stmt->bind_result($dp_info);
		    $stmt->fetch();
		    $stmt->close();

		}

		$stmtq = "SELECT id FROM ".$this->table2." WHERE level = 3";

		if ($stmt = $this->query->prepare($stmtq)){

		    $stmt->execute();

		    $stmt->bind_result($id_pptk);
		    $flag = 0;
		    while($stmt->fetch()):
		    	if($id_pptk == $pptk){
		    		$flag = 1;
		    	}
		    endwhile;
		    $stmt->close();

		}

		if($flag == 0){
			return false;
		}

		$array = cta($dp_info);
		$array[0] = $title;

		$dp_info = atc($array);

		$stmtq = "UPDATE ".$this->table." SET lead_id = ?, dp_info = ?, n_pagu = ? WHERE id = ?";

		if ($stmt = $this->query->prepare($stmtq)){

			$stmt->bind_param("isii", $pptk, $dp_info, $pagu, $id);
		    $stmt->execute();

		    $stmt->close();
		}

		return true;

	}

	function updateKontraktor($p_id, $k_id) {

		$stmtq = "SELECT lead_id, rel_id FROM ".$this->table." WHERE id = ?";

		if ($stmt = $this->query->prepare($stmtq)){

			$stmt->bind_param("i", $p_id);
		    if(!$stmt->execute()){
		    	return false;
		    }

		    $stmt->bind_result($rel_id, $q_rel_id);
		    $stmt->fetch();
		    $stmt->close();
		}

		$stmtq = "UPDATE ".$this->table." SET rel_id = ? WHERE id = ?";

		if ($stmt = $this->query->prepare($stmtq)){

			$stmt->bind_param("ii", $k_id, $p_id);
		    if(!$stmt->execute()){
		    	return false;
		    }

		    $stmt->close();
		}

		$stmtq = "UPDATE ".$this->table2." SET rel_id = 0 WHERE id = ?";

		if ($stmt = $this->query->prepare($stmtq)){

			$stmt->bind_param("i", $q_rel_id);
		    if(!$stmt->execute()){
		    	return false;
		    }

		    $stmt->close();
		}

		$stmtq = "UPDATE ".$this->table2." SET rel_id = ? WHERE id = ?";

		if ($stmt = $this->query->prepare($stmtq)){

			$stmt->bind_param("ii", $rel_id, $k_id);
		    if(!$stmt->execute()){
		    	return false;
		    }

		    $stmt->close();
		}

		return true;	

	}

	function fetch($s_id, $special = 0) {

		switch ($special) {
			case 0:
				$stmtq = "SELECT ".$this->table.".rel_id, ".$this->table.".kategori, ".$this->table.".dp_info, ".$this->table.".dp_kontrak, ".$this->table.".dp_pelaksana, ".$this->table.".dp_surat, ".$this->table.".pf_info, ".$this->table.".mp_info, ".$this->table.".rp_info_prg, ".$this->table.".rp_info_waktu, ".$this->table.".rp_info_prg_c, ".$this->table.".rp_info_waktu_c, ".$this->table.".ke_info, ".$this->table.".ke_c, ".$this->table.".gallery, ".$this->table.".mk_info, ".$this->table.".mk_c_xy, ".$this->table.".mk_c_info, ".$this->table.".na_info, ".$this->table.".lead_id, ".$this->table.".id, ".$this->table2.".name, ".$this->table3.".title, ".$this->table.".n_kontrak, ".$this->table.".n_pagu FROM ".$this->table." LEFT JOIN ".$this->table2." ON ".$this->table.".rel_id = ".$this->table2.".id LEFT JOIN ".$this->table3." ON ".$this->table.".kategori = ".$this->table3.".id WHERE ".$this->table.".id = ?";
				break;

			case 1:
				$qattach = $this->table.".dp_info, ".$this->table.".dp_kontrak, ".$this->table.".dp_pelaksana, ".$this->table.".dp_surat, ".$this->table.".n_kontrak, ".$this->table.".n_pagu";
				break;

			case 2:
				$qattach = $this->table.".pf_info";
				break;

			case 3:
				$qattach = $this->table.".mp_info";
				break;

			case 4:
				$qattach = $this->table.".rp_info_prg, ".$this->table.".rp_info_waktu, ".$this->table.".rp_info_prg_c, ".$this->table.".rp_info_waktu_c";
				break;

			case 5:
				$qattach = $this->table.".ke_info, ".$this->table.".ke_c";
				break;

			case 6:
				$qattach = $this->table.".gallery";
				break;

			case 7:
				$qattach = $this->table.".mk_info, ".$this->table.".mk_c_xy, ".$this->table.".mk_c_info";
				break;

			case 8:
				$qattach = $this->table.".na_info";
				break;

			case 9:
				$stmtq = "SELECT ".$this->table.".lead_id, ".$this->table.".kategori, ".$this->table.".dp_info, ".$this->table3.".title, ".$this->table.".n_pagu, ".$this->table.".id FROM ".$this->table." LEFT JOIN ".$this->table3." ON ".$this->table.".kategori = ".$this->table3.".id WHERE ".$this->table.".id = ?";
				break;

			case 10:
				$s_id = 1;
				$stmtq = "SELECT kategori, SUM(n_pagu) AS n_pagu, COUNT(id) AS jumlah FROM ".$this->table." WHERE ? GROUP BY kategori";
				break;

			case 11:
				$stmtq = "SELECT ".$this->table.".dp_info, ".$this->table.".lead_id, ".$this->table.".n_pagu, ".$this->table.".n_kontrak, ".$this->table.".dp_kontrak, ".$this->table.".pf_info, ".$this->table.".ke_info, ".$this->table2.".name, ".$this->table.".id FROM ".$this->table." LEFT JOIN ".$this->table2." ON ".$this->table.".lead_id = ".$this->table2.".id WHERE ".$this->table.".kategori = ?";
				break;

			default:
				return false;
				break;
		}

		if(!isset($stmtq)):
		$stmtq = "SELECT ".$this->table.".rel_id, ".$this->table.".kategori, ".$qattach.", ".$this->table2.".rel_id, ".$this->table.".id, ".$this->table2.".name FROM ".$this->table." LEFT JOIN ".$this->table2." ON ".$this->table.".rel_id = ".$this->table2.".id WHERE ".$this->table.".id = ?";
		endif;

		if ($stmt = $this->query->prepare($stmtq)){
		    $stmt->bind_param("s", $s_id); 
		    $stmt->execute();

		   	switch ($special) {
				case 0:
		    		$stmt->bind_result($r_rel_id, $r_kategori, $r_dp_info, $r_dp_kontrak, $r_dp_pelaksana, $r_dp_surat, $r_pf_info, $r_mp_info, $r_rp_info_prg, $r_rp_info_waktu, $r_rp_info_prg_c, $r_rp_info_waktu_c, $r_ke_info, $r_ke_c, $r_gallery, $r_mk_info, $r_mk_c_xy, $r_mk_c_info, $r_na_info, $r_rel2_id, $r_id, $r_name, $kat_name, $r_n_kontrak, $r_n_pagu);
					break;

				case 1:
		    		$stmt->bind_result($r_rel_id, $r_kategori, $r_dp_info, $r_dp_kontrak, $r_dp_pelaksana, $r_dp_surat, $r_n_kontrak, $r_n_pagu, $r_rel2_id, $r_id, $r_name);
					break;

				case 2:
		    		$stmt->bind_result($r_rel_id, $r_kategori, $r_pf_info, $r_rel2_id, $r_id, $r_name);
					break;

				case 3:
		    		$stmt->bind_result($r_rel_id, $r_kategori, $r_mp_info, $r_rel2_id, $r_id, $r_name);
					break;

				case 4:
		    		$stmt->bind_result($r_rel_id, $r_kategori, $r_rp_info_prg, $r_rp_info_waktu, $r_rp_info_prg_c, $r_rp_info_waktu_c, $r_rel2_id, $r_id, $r_name);
					break;

				case 5:
					$stmt->bind_result($r_rel_id, $r_kategori, $r_ke_info, $r_ke_c, $r_rel2_id, $r_id, $r_name);
					break;

				case 6:
					$stmt->bind_result($r_rel_id, $r_kategori, $r_gallery, $r_rel2_id, $r_id, $r_name);
					break;

				case 7:
					$stmt->bind_result($r_rel_id, $r_kategori, $r_mk_info, $r_mk_c_xy, $r_mk_c_info, $r_rel2_id, $r_id, $r_name);
					break;

				case 8:
					$stmt->bind_result($r_rel_id, $r_kategori, $r_na_info, $r_rel2_id, $r_id, $r_name);
					break;

				case 9:
					$stmt->bind_result($r_rel_id, $r_kategori, $r_dp_info, $r_title, $r_n_pagu, $r_id);
					break;

				case 10:
					$stmt->bind_result($r_kategori, $r_n_kontrak, $r_jumlah);
					break;

				case 11:
					$stmt->bind_result($r_dp_info, $r_lead_id, $r_n_pagu, $r_n_kontrak, $r_dp_kontrak, $r_pf_info, $r_ke_info, $r_name, $r_id);
					break;

				default:
					return false;
					break;
			}


		    $x = 0;
		    while($stmt->fetch()):

		    switch ($special) {
				case 0:
			    	$array['id_kontraktor'] = $r_rel_id; 
					$array['id_kategori'] = $r_kategori; 
					$array['info'] = $r_dp_info; 
					$array['info_kontrak'] = $r_dp_kontrak; 
					$array['info_pelaksana'] = $r_dp_pelaksana; 
					$array['info_surat'] = $r_dp_surat; 
					$array['info_fisik'] = $r_pf_info; 
					$array['info_monitoring_p'] = $r_mp_info; 
					$array['info_ringkasan'] = $r_rp_info_prg; 
					$array['info_waktu'] = $r_rp_info_waktu; 
					$array['info_keuangan'] = $r_ke_info; 
					$array['info_monitoring_k'] = $r_mk_info;
					$array['chart_keuangan'] = $r_ke_c; 
					$array['chart_ringkasan'] = $r_rp_info_prg_c; 
					$array['chart_waktu'] = $r_rp_info_waktu_c; 
					$array['gallery'] = $r_gallery; 

					$array['chart_monitoring_k_xy'] = $r_mk_c_xy; 
					$array['chart_monitoring_k_info'] = $r_mk_c_info; 
					$array['info_nama'] = $r_na_info; 
					$array['id_kontraktor'] = $r_rel2_id;
					$array[0][20] = $r_id;
					$array[0][21] = $r_name;
					$array[0][22] = $kat_name;
					$array[0][23] = $r_n_kontrak;
					$array[0][24] = $r_n_pagu;			
					break;

				case 1:
			    	$array[0][0] = $r_rel_id; 
					$array[0][1] = $r_kategori; 
					$array[0][2] = $r_dp_info; 
					$array[0][3] = $r_dp_kontrak; 
					$array[0][4] = $r_dp_pelaksana; 
					$array[0][5] = $r_dp_surat;
					$array[0][6] = $r_n_kontrak;
					$array[0][7] = $r_n_pagu;
					$array[0][19] = $r_rel2_id;
					$array[0][20] = $r_id;
					$array[0][21] = $r_name;
					break;

				case 2:
			    	$array[0][0] = $r_rel_id; 
					$array[0][1] = $r_kategori; 
					$array[0][2] = $r_pf_info; 
					$array[0][19] = $r_rel2_id;
					$array[0][20] = $r_id;
					$array[0][21] = $r_name;
					break;

				case 3:
			    	$array[0][0] = $r_rel_id; 
					$array[0][1] = $r_kategori; 
					$array[0][2] = $r_mp_info; 
					$array[0][19] = $r_rel2_id;
					$array[0][20] = $r_id;
					$array[0][21] = $r_name;
					break;

				case 4:
			    	$array[0][0] = $r_rel_id; 
					$array[0][1] = $r_kategori; 
					$array[0][2] = $r_rp_info_prg; 
					$array[0][3] = $r_rp_info_waktu; 
					$array[0][4] = $r_rp_info_prg_c; 
					$array[0][5] = $r_rp_info_waktu_c;
					$array[0][19] = $r_rel2_id;
					$array[0][20] = $r_id;
					$array[0][21] = $r_name;
					break;

				case 5:
					$array[0][0] = $r_rel_id; 
					$array[0][1] = $r_kategori;
					$array[0][2] = $r_ke_info;
					$array[0][3] = $r_ke_c;
					$array[0][19] = $r_rel2_id;
					$array[0][20] = $r_id;
					$array[0][21] = $r_name;
					break;

				case 6:
					$array[0][0] = $r_rel_id; 
					$array[0][1] = $r_kategori;
					$array[0][2] = $r_gallery;
					$array[0][19] = $r_rel2_id;
					$array[0][20] = $r_id;
					$array[0][21] = $r_name;
					break;

				case 7:
					$array[0][0] = $r_rel_id; 
					$array[0][1] = $r_kategori;
					$array[0][2] = $r_mk_info;
					$array[0][3] = $r_mk_c_xy;
					$array[0][4] = $r_mk_c_info;
					$array[0][19] = $r_rel2_id;
					$array[0][20] = $r_id;
					$array[0][21] = $r_name;
					break;

				case 8:
					$array[0][0] = $r_rel_id; 
					$array[0][1] = $r_kategori; 
					$array[0][2] = $r_na_info;
					$array[0][19] = $r_rel2_id;
					$array[0][20] = $r_id;
					$array[0][21] = $r_name;
					break;

				case 9:
					$array['id_pptk'] = $r_rel_id;
					$array['kategori'] = $r_kategori;
					$array['info'] = $r_dp_info;
					$array['title_kategori'] = $r_title;
					$array['nilai_pagu'] = $r_n_pagu;
					$array['id'] = $r_id;
					break;

				case 10:
					$array[$x]['id'] = $r_kategori;
					$array[$x]['dana'] = $r_n_kontrak;
					$array[$x]['jumlah'] = $r_jumlah;
					break;

				case 11:
					$array[$x]['info'] = $r_dp_info;
					$array[$x]['id_pptk'] = $r_lead_id;
					$array[$x]['nilai_pagu'] = $r_n_pagu;
					$array[$x]['nilai_kontrak'] = $r_n_kontrak;
					$array[$x]['info_add'] = $r_dp_kontrak;
					$array[$x]['info_fisik'] = $r_pf_info;
					$array[$x]['info_uang'] = $r_ke_info;
					$array[$x]['nama_pptk'] = $r_name;
					$array[$x]['id'] = $r_id;
					break;
				
				default:
					return false;
					break;
				}


		    	$x++;

			endwhile;
		    $stmt->close();

			if(!empty($array)){ 
				return $array;
			}
			else {
				return false;
			}
		}
	}

	function delete($id){

		$stmtq = "DELETE FROM ".$this->table." WHERE id = ?";
	    if($stmt = $this->query->prepare($stmtq)) {

			$stmt->bind_param('s', $id);
			$stmt->execute();

	    	$stmt->close();
	    	return true;
		}
		else {
			return false;
		}
	
	}

	function update($q_m) {

		switch ($q_m) {
			case 1:
				$qattach = "dp_info = ?, dp_kontrak = ?, dp_pelaksana = ?, dp_surat = ?";
				break;

			case 2:
				$qattach = "pf_info = ?";
				break;

			case 3:
				$qattach = "mp_info = ?";
				break;

			case 4:
				$qattach = "rp_info_prg = ?, rp_info_prg_c = ?, rp_info_waktu = ?, rp_info_waktu_c = ?";
				break;

			case 5:
				$qattach = "ke_info = ?, ke_c = ?";
				break;

			case 6:
				$qattach = "gallery = ?";
				break;

			case 7:
				$qattach = "mk_info = ?, mk_c_xy = ?, mk_c_info = ?";
				break;

			case 8:
				$qattach = "na_info = ?";
				break;
			
			default:
				return false;
				break;
		}

		$stmtq = "UPDATE ".$this->table." SET ".$qattach." WHERE rel_id = ?";

//		printf(str_replace("?", "%s", $stmtq), $q_rel_id, $s_id, $q_r_id, $q_r_time, $q_status, $q_komentar, $q_time, $q_r_status, $q_s_id);

		date_default_timezone_set($this->timezone);
	    $q_time = date("Y-m-d H:i:s");

	    if ($stmt = $this->query->prepare($stmtq)) {

		   	switch ($q_m) {
				case 1:
	        		$stmt->bind_param("sssss", $this->dp_info, $this->dp_kontrak, $this->dp_pelaksana, $this->dp_surat, $this->q_id);
					break;

				case 2:
					$stmt->bind_param("ss", $this->pf_info, $this->q_id);
					break;

				case 3:
					$stmt->bind_param("ss", $this->mp_info, $this->q_id);
					break;

				case 4:
					$stmt->bind_param("sssss", $this->rp_info_prg, $this->rp_info_prg_c, $this->rp_info_waktu, $this->rp_info_waktu_c, $this->q_id);
					break;
				
				case 5:
					$stmt->bind_param("sss", $this->ke_info, $this->ke_c, $this->q_id);
					break;

				case 6:
					$stmt->bind_param("ss", $this->gallery, $this->q_id);
					break;

				case 7:
					$stmt->bind_param("ssss", $this->mk_info, $this->mk_c_xy, $this->mk_c_info, $this->q_id);
					break;

				case 8:
					$stmt->bind_param("ss", $this->na_info, $this->q_id);
					break;
			
				default:
					return false;
					break;
			}

	        $stmt->execute();

	        $stmt->close();
	        return true;
	    }
	    else {
	    	return false;
	    }
	}

}

class catProcess extends dashb {

	protected $table = "cat";

	private $q_cat_title;
	private $q_id;

	function setInfo($q_cat_title, $q_q_id = "") {
		$this->cat_title = $q_cat_title;
		$this->q_id = $q_q_id;
	}

	function delete($id){

		$stmtq = "DELETE FROM ".$this->table." WHERE id = ?";
	    if($stmt = $this->query->prepare($stmtq)) {

			$stmt->bind_param('s', $id);
			$stmt->execute();

	    	$stmt->close();
	    	return true;
		}
		else {
			return false;
		}
	
	}

	function insert(){
		$stmtq = "INSERT INTO ".$this->table." (title) VALUES (?)";
	    if($stmt = $this->query->prepare($stmtq)) {

			$stmt->bind_param('s', $this->cat_title);
			$stmt->execute();

	    	$stmt->close();
		}
	
	}

	function update(){

		$stmtq = "UPDATE ".$this->table." SET title = ? WHERE id = ?";
	    if($stmt = $this->query->prepare($stmtq)) {

			$stmt->bind_param('ss', $this->cat_title, $this->q_id);
			$stmt->execute();

	    	$stmt->close();
		}
	}

	function fetch($special = 0, $id = "") {

		switch ($special) {
			case 0:
				$qattach = 1;
				break;

			case 1:
				$qattach = "id = ?";
				break;

			default:
				return false;
				break;
		}

		$stmtq = "SELECT id, title FROM ".$this->table." WHERE ".$qattach;

		if ($stmt = $this->query->prepare($stmtq)){ 
			switch ($special) {
				case 0:
					break;

				case 1:
					$stmt->bind_param("s", $id);
					break;

				default:
					return false;
					break;
			}

		    $stmt->execute();

		   	switch ($special) {
				case 0:
		    		$stmt->bind_result($r_id, $r_title);
					break;

				case 1:
		    		$stmt->bind_result($r_id, $r_title);
					break;

				default:
					return false;
					break;
			}


		    $x = 0;
		    while($stmt->fetch()):

		    	$array[$x]['id'] = $r_id; 
				$array[$x]['title'] = $r_title;

			$x++;
			endwhile;
		    $stmt->close();

			if(!empty($array)){ 
				return $array;
			}
			else {
				return false;
			}
		}
	}

}