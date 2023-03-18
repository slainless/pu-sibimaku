<?php
$get_in = filter_input(INPUT_GET, 'm', FILTER_SANITIZE_NUMBER_INT);

require_once 'function.php';

if(isset($_POST["submit"])){
	require 'process.php';
}

	require 'display-info.php';
