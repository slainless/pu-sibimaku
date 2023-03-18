<?php
$get_in = filter_input(INPUT_GET, 'm', FILTER_SANITIZE_NUMBER_INT);

require_once 'function.php';

if(isset($_POST["submit"])){
	require 'process.php';
}

if(isset($_GET['compose'])){

	require 'compose.php';

}
else {

	if(isset($_GET["id"])){
		require 'detail.php';
	}
	else {
		require 'display.php';
	}

}