<?php
$get_in = filter_input(INPUT_GET, 'm', FILTER_SANITIZE_NUMBER_INT);

require_once 'function.php';
/*
if(isset($_POST["submit"]) || isset($_GET["del"])){
    require 'cat/process.php';
    if($get_in == 1 || isset($_GET["del"])){
    	require 'cat/display.php';
    }
    elseif($get_in == 2){
    	require 'cat/edit.php';
    }
}
else {

	if($get_in == 2){
		require 'cat/edit.php';
	}
	elseif($get_in == 1){
		require 'cat/insert.php';
	}
	else {
		require 'cat/display.php';

	}

}
*/
if(isset($_POST["submit"])){
	require 'process.php';
}

if(isset($_GET["id"])){
	require 'detail.php';
}
else {
	require 'display.php';
}

