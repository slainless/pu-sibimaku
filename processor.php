<?php

	$_PRIVATE['root'] = $_SERVER['DOCUMENT_ROOT']."prokal/";
	$_PRIVATE['req'] = $_GET['q'];
	
	if($_GET['q'] === '')
		require 'index.php';
		

	$_PRIVATE['temp'] = explode("/", $_PRIVATE['req']);
	
	$var['count'] = count($_PRIVATE['temp']);
	if($_PRIVATE['temp'] > 1 && $_PRIVATE['temp'][$var['count']-1] === "")
    array_pop($_PRIVATE['temp']);
	
	$var['count'] = count($_PRIVATE['temp']);

	$l = 0;

	if($_PRIVATE['temp'][0] == 'dash'):
	

		if($var['count'] > 3)
			require 'html/error.php';

		$_PRIVATE['path'] = 'dash/';

		if($_PRIVATE['temp'][$var['count']-1] == 'processor'):
			switch ($_PRIVATE['temp'][1]):
				case 'catman': $_PRIVATE['path'] .= 'cat'; break;
				case 'docs': $_PRIVATE['path'] .= 'docs'; break;
				case 'mail': $_PRIVATE['path'] .= 'mail'; break;
				case 'dashboard': $_PRIVATE['path'] .= 'main'; break;
				case 'hub': $_PRIVATE['path'] .= 'hub'; break;
				case 'maps': $_PRIVATE['path'] .= 'maps'; break;
				case 'gallery': $_PRIVATE['path'] .= 'gallery'; break;
				case 'usrman': $_PRIVATE['path'] .= 'user'; break;
				case 'form': $_PRIVATE['path'] .= 'form'; break;
				default: require 'html/error.php';
			endswitch;
			$_PRIVATE['path'] .= '/fetcher.php';
		elseif(!isset($_PRIVATE['temp'][1]) || empty($_PRIVATE['temp'][1])):
			$_PRIVATE['path'] .= 'index.php';
		else:
			if(isset($_PRIVATE['temp'][1]) && !empty($_PRIVATE['temp'][1]))
				$_GET['d'] = $_PRIVATE['temp'][1];

			if(isset($_PRIVATE['temp'][2]) && !empty($_PRIVATE['temp'][2]))
				$_GET['id'] = $_PRIVATE['temp'][2];

			$_PRIVATE['path'] .= 'index.php';
			
		endif;

		$_PRIVATE['temp'][$var['count']-1] = '.php';

	elseif($_PRIVATE['temp'][0] == 'login'):

		if(isset($_PRIVATE['temp'][1]) && $_PRIVATE['temp'][1] == 'process')
      $_PRIVATE['path'] = 'login/process.php';
    else
      $_PRIVATE['path'] = 'login/index.php';
		$_PRIVATE['temp'][$var['count']-1] = '.php';

	elseif($_PRIVATE['temp'][0] == 'logout'):

		if($var['count'] > 1)
			require 'html/error.php';

		$_PRIVATE['path'] = 'login/logout.php';
		$_PRIVATE['temp'][$var['count']-1] = '.php';

	elseif($_PRIVATE['temp'][0] == 'assets' || $_PRIVATE['temp'][0] == 'plugins' || $_PRIVATE['temp'][0] == 'drive'):

		$_PRIVATE['path'] = $_PRIVATE['req'];
		if(!is_file($_PRIVATE['path']))
			require 'html/error.php';

		$info['time_mod'] = filemtime($_PRIVATE['path']);
		$info['etag'] = md5_file($_PRIVATE['path']);

		$info['last_mod'] = (isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) ? $_SERVER['HTTP_IF_MODIFIED_SINCE'] : false);
		$info['last_etag'] = (isset($_SERVER['HTTP_IF_NONE_MATCH']) ? trim($_SERVER['HTTP_IF_NONE_MATCH']) : false);

		header("Last-Modified: ".gmdate("D, d M Y H:i:s", $info['time_mod'])." GMT");
		header("Etag: \"".$info['etag']."\"");
		header('Cache-Control: public');

		if (@strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']) == $info['time_mod'] || $info['etag'] == $info['last_etag']):

		    header("HTTP/1.1 304 Not Modified");
		    exit;

		endif;


	else:
		require 'html/error.php';
	endif;

	if(strpos($_PRIVATE['temp'][$var['count']-1], ".js") !== false): header('Content-Type: application/javascript');
	elseif(strpos($_PRIVATE['temp'][$var['count']-1], ".css") !== false): header('Content-Type: text/css');
	elseif(strpos($_PRIVATE['temp'][$var['count']-1], ".png") !== false): header('Content-Type: image/png');
	elseif(strpos($_PRIVATE['temp'][$var['count']-1], ".jpg") !== false): header('Content-Type: image/jpeg');
	elseif(strpos($_PRIVATE['temp'][$var['count']-1], ".jpeg") !== false): header('Content-Type: image/jpeg');
	elseif(strpos($_PRIVATE['temp'][$var['count']-1], ".woff") !== false): header('Content-Type: font/woff');
	elseif(strpos($_PRIVATE['temp'][$var['count']-1], ".ttf") !== false): header('Content-Type: font/ttf');
	elseif(strpos($_PRIVATE['temp'][$var['count']-1], ".pdf") !== false): header('Content-Type: application/pdf');
	elseif(strpos($_PRIVATE['temp'][$var['count']-1], ".doc") !== false): header('Content-Type: application/msword');
	elseif(strpos($_PRIVATE['temp'][$var['count']-1], ".docx") !== false): header('Content-Type: application/msword');
	elseif(strpos($_PRIVATE['temp'][$var['count']-1], ".xls") !== false): header('Content-Type: application/vnd.openxmlformats-');
	elseif(strpos($_PRIVATE['temp'][$var['count']-1], ".xlsx") !== false): header('Content-Type: application/vnd.openxmlformats-');
	elseif(strpos($_PRIVATE['temp'][$var['count']-1], ".woff2") !== false): header('Content-Type: font/woff2');

	elseif(strpos($_PRIVATE['temp'][$var['count']-1], ".php") !== false):
	 	unset($var);
	 	unset($temp);
		require $_PRIVATE['path']; 
		exit();

	else:
		require 'html/error.php';
	endif;

	echo file_get_contents($_PRIVATE['path']);



 	