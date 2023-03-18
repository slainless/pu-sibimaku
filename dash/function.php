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

                if($stmt->num_rows() == 1 && !isset($param['option']['force_array'])){ 
                    $stmt->fetch();
                    $array = $temp;
                }
                else {
                    $x = 0;
                    $z = count($param['result']);
                    while($stmt->fetch()){
                        for($y=0;$y<$z;$y++){
                            if(!isset($param['option']['transpose']))
                                $array[$param['result'][$y]][$x] = ${$param['result'][$y]};
                            else
                                $array[$x][$param['result'][$y]] = ${$param['result'][$y]};
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

            if($stmt->num_rows() == 1 && !isset($param['option']['force_array'])){
            	$stmt->fetch();
            	$array = $temp;
            }
            else {
            	$x = 0;
            	$z = count($param['field']);
            	while($stmt->fetch()){
            		for($y=0;$y<$z;$y++){
                        if(!isset($param['option']['transpose']))
                            $array[$param['field'][$y]['result']][$x] = ${$param['field'][$y]['result']};
                        else
                            $array[$x][$param['field'][$y]['result']] = ${$param['field'][$y]['result']};
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
