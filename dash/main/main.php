<?php

require_once 'function.php';

if(isset($_POST["submit"])){
	require 'main/process.php';
}

if($s_level > 2){

	if(isset($_GET["id"])){
		require 'main/display-info.php';
	}
	else {
		$url = urlc("dash/?d=catman", $tlimit); 
	    header('Location: '.$url);
	    exit();
	}

}
elseif($s_level == 2){
	require 'main/display-info.php';
}
