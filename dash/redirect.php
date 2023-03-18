<?php

require_once 'function-dash.php';

if($s_level > 2){

	if(isset($_GET["d"])){

		switch ($_GET["d"]) {
			case 'usrman':
				require 'user/main.php';
				break;
				
			case 'catman':
				require 'cat/main.php';
				break;
			
			case 'dashboard':
				require 'main/main.php';
				break;

			case 'mail':
				require 'mail/main.php';
				break;

			case 'docs':
				require 'docs/main.php';
				break;

			case 'gallery':
				require 'gallery/main.php';
				break;

			case 'maps':
				require 'maps/main.php';
				break;


			default:
				errCode("404", "Page not Found", true);
				exit();
				break;
		}
		
	}
	else {
	    //$url = urlc("dash/?d=catman", $tlimit); 
	    header('Location: /dash/catman');
	    exit();
	}
}
elseif($s_level == 2){
	if(isset($_GET["d"])){

		switch ($_GET["d"]) {
			
			case 'dashboard':
				require 'main/main.php';
				break;

			case 'docs':
				require 'docs/main.php';
				break;

			case 'gallery':
				require 'gallery/main.php';
				break;

			case 'maps':
				require 'maps/main.php';
				break;


			default:
				$url = urlc("dash/?d=dashboard", $tlimit); 
			    header('Location: '.$url);
			    exit();
				break;
		}
	}
	else {
	    $url = urlc("dash/?d=dashboard", $tlimit); 
	    header('Location: '.$url);
	    exit();
	}
}
