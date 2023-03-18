<?php

class inbox {
	protected $query;
    protected $table = "inbox";

    function __construct($db) {
        $this->query = $db;
    }

	function checkInbox($id) {

    	$stmtq = "SELECT ls_id, rel_id FROM " . $this->table . " WHERE s_id = ?";

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

class inboxProcess extends inbox {

	private $timezone = "Asia/Makassar";
	private $table2 = "member";

	function submit($s_id, $q_rel_id, $q_title, $q_main, $q_attach) {

		$ls_id = $this->checkInbox($s_id);
		if($ls_id) { 
			if($ls_id[0] != $s_id && $ls_id[1] == $s_id) {
				$stmtq = "UPDATE ".$this->table." SET rel_id = ?, ls_id = ?, ls_time = ?, subject = ?, main = ?, attachment = ? WHERE s_id = ?";

				date_default_timezone_set($this->timezone);
			    $q_time = date("Y-m-d H:i:s");

			    if ($stmt = $this->query->prepare($stmtq)) {
			        $stmt->bind_param("sssssss", $q_rel_id, $s_id, $q_time, $q_title, $q_main, $q_attach, $s_id); 
			        $stmt->execute();

			        $stmt->close();
			        return true;
			    }
			    else {
			    	return false;
			    }
			}
			else {
				return false;
			}
		}
		else { 
			$stmtq = "INSERT INTO ".$this->table." (`rel_id`, `s_id`, `ls_id`, `subject`, `main`, `attachment`, `time`, `ls_time`) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

			date_default_timezone_set($this->timezone);
		    $q_time = date("Y-m-d H:i:s");

		    if ($stmt = $this->query->prepare($stmtq)) {
		        $stmt->bind_param("ssssssss", $q_rel_id, $s_id, $s_id, $q_title, $q_main, $q_attach, $q_time, $q_time); 
		        $stmt->execute();

		        $stmt->close();
		        return true;
		    }
		    else {
		    	return false;
	    	}
		}
	}

	function fetch($s_id, $special = false, $special2 = false) {

		if($special == true){
			$q_rel_id = "s_id";
		}
		else {
			$q_rel_id = "rel_id";
		}
		
		$stmtq = "SELECT ".$this->table.".rel_id, ".$this->table.".s_id, ".$this->table.".r_id, ".$this->table.".comment, ".$this->table.".subject, ".$this->table.".main, ".$this->table.".attachment, ".$this->table.".status, ".$this->table.".time, ".$this->table.".r_time, ".$this->table2.".name, ".$this->table.".ls_id, ".$this->table.".ls_time, ".$this->table.".r_status, ".$this->table2.".rel_id FROM ".$this->table." LEFT JOIN ".$this->table2." ON ".$this->table.".s_id = ".$this->table2.".id WHERE ".$this->table.".".$q_rel_id." = ? OR ".$this->table.".ls_id = ?";

		if ($stmt = $this->query->prepare($stmtq)){
		    $stmt->bind_param("ss", $s_id, $s_id); 
		    $stmt->execute();
		    $stmt->bind_result($r_rel_id, $r_s_id, $r_r_id, $r_comment, $r_subject, $r_main, $r_attachment, $r_status, $r_time, $r_r_time, $r_name, $r_ls_id, $r_ls_time, $r_r_status, $r2_rel_id);

		    $x = 0;
		    while($stmt->fetch()):
		    	$array[$x][0] = $r_rel_id;
		    	$array[$x][1] = $r_s_id;
		    	$array[$x][2] = $r_r_id;
		    	$array[$x][3] = $r_comment;
		    	$array[$x][4] = $r_subject;
		    	$array[$x][5] = $r_main;
		    	$array[$x][6] = $r_attachment;
		    	$array[$x][7] = $r_status;
		    	$array[$x][8] = $r_time;
		    	$array[$x][9] = $r_r_time;
		        $array[$x][10] = $r_name;
		        $array[$x][11] = $r_ls_id;
		        $array[$x][12] = $r_ls_time;
		        $array[$x][13] = $r_r_status;
		        $array[$x][14] = $r2_rel_id;

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

	function fetchReply($r_id) {

		$r_id = str_getcsv($r_id);

		$limit = count($r_id);
    	for($x=0; $x<$limit; $x++):
    		if($limit == 1){
    			$rlike = $r_id[0];
    		}
    		else {
	    		if($x == 0){
	    			$rlike = $r_id[0];
	    		}
	    		else {
	    			$rlike = "|".$r_id[$x];
	    		}
    		}
    	endfor;

		$stmtq = "SELECT id, name FROM ".$this->table2." WHERE id RLIKE ?";
		// printf(str_replace("?", "%s", $stmtq), $rlike);

		if ($stmt = $this->query->prepare($stmtq)){
		    $stmt->bind_param("s", $rlike); 
		    $stmt->execute();
		    $stmt->bind_result($r_id, $r_name);

		    $x = 0;
		    while($stmt->fetch()):
		    	$array[$x][0] = $r_id;
		    	$array[$x][1] = $r_name;

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

	function update($q_rel_id, $s_id, $q_status, $q_komentar, $q_s_id, $s_level) {

		$array = $this->fetch($q_s_id, true);
		$ls_id = $this->checkInbox($q_s_id);

		$r2_rel_id = explode(",",$array[0][14]);

		if($array[0][11] == $s_id){
			return false;
		}

		if($ls_id[0] == $s_id && $ls_id[1] != $s_id){
			return false;
		}

		if($q_status != 2 && $q_status != 1){
			return false;
		}

		if($s_level == 3){
			if($q_status == 1) {
		        $s_rel_id_ex = explode(",",$q_rel_id);
		        $q_rel_id = $s_rel_id_ex[0];
		    }
		    elseif($q_status == 2) {
		    	$q_rel_id = $array[0][1];
		    }

		    if($r2_rel_id[0] != $s_id){
		    	return false;
		    }
	    }
	    elseif($s_level > 3){ 
	    	if($q_status == 1) {
	        	$q_rel_id = $s_id;
	        	$q_status = 3;
		    }
		    elseif($q_status == 2){
		    	$q_rel_id = $array[0][11];
		    }

		    if($r2_rel_id[1] != $s_id){
		    	return false;
		    }
	    }

		date_default_timezone_set($this->timezone);
	    $q_time = date("Y-m-d H:i:s");

		if(empty($array[0][2])) {
			$q_r_time = '"'.$q_time.'"';
			$q_komentar = '"'.$q_komentar.'"';
			$q_r_id = '"'.$s_id.'"';
			$q_r_status = '"'.$q_status.'"';
		}
		elseif(!empty($array[0][2])) {
			$q_r_time = $array[0][9].', "'.$q_time.'"';
			$q_komentar = $array[0][3].', "'.$q_komentar.'"';
			$q_r_id = $array[0][2].', "'.$s_id.'"';
			$q_r_status = $array[0][13].', "'.$q_status.'"';
		}

		$stmtq = "UPDATE ".$this->table." SET rel_id = ?, ls_id = ?, r_id = ?, r_time = ?, status = ?, comment = ?, ls_time = ?, r_status = ? WHERE s_id = ?";

//		printf(str_replace("?", "%s", $stmtq), $q_rel_id, $s_id, $q_r_id, $q_r_time, $q_status, $q_komentar, $q_time, $q_r_status, $q_s_id);

		date_default_timezone_set($this->timezone);
	    $q_time = date("Y-m-d H:i:s");

	    if ($stmt = $this->query->prepare($stmtq)) {
	        $stmt->bind_param("sssssssss", $q_rel_id, $s_id, $q_r_id, $q_r_time, $q_status, $q_komentar, $q_time, $q_r_status, $q_s_id); 
	        $stmt->execute();

	        $stmt->close();
	        return true;
	    }
	    else {
	    	return false;
	    }
	}
}