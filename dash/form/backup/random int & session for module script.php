<?php

// EXAMPLE 
// STARTER SESSION CHECKER FOR MODULE
// & RANDOM INT SECURITY CODE

// SESSION CHECKER
require_once "../dir-conf.php";

require_once $access_main;

echo $access_main;
require_once $func_login;

$session = new session();

$level = new levelCheck(($_SESSION['level']));
//
if($level->minCheck(1)):

    $s_id = $_SESSION['user_id'];
    $s_username = $_SESSION['username'];
    $s_name = $_SESSION['name'];
    $s_login_string = $_SESSION['login_string'];
    $s_level = $_SESSION['level'];
    $s_status = $_SESSION['status'];
    $s_rel_id = $_SESSION['rel_id'];

endif;

echo $s_level;



// RANDOM INT CODE
// BASIC RANDOM INT
// TO DO : RANDOM_INT($MIN, $MAX);

$value = "51";
$strcount = strlen($value);

$ex0 =
random_int(10000, 99999).
$value.
random_int(100, 999).
$value.
random_int(1000, 9999).
$value.
random_int(10, 99).
$strcount.
random_int(10, 99);

 $ex1 = 
 random_int(100, 999).
 "3".
 random_int(1000000, 9999999).
 "2".
 random_int(1000, 9999);


// RANDOM INT PROCESSOR

if(isset($ex0, $ex1)):

	$strcount = substr($ex0, -3, 1);

	settype($strcount, 'int');
	
	$count0 = substr($ex0, 5, $strcount);
	$range = 8 + $strcount;
	$count1 = substr($ex0, $range, $strcount);
	$range = $range + $strcount + 4;
	$count2 = substr($ex0, $range, $strcount);

	$m0 = substr($ex1, 3, 1);
	$m1 = substr($ex1, 11, 1);

	if($count0 == $count1 && $count0 == $count2){
		$m = $m0.$m1;

		switch ($m) {
			case '32':
				echo "yes";
				break;
			
			default:
				return false;
				break;
		}

	}

endif;