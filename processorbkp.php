<?php
	var_dump(microtime(true));
	$_PRIVATE['root'] = $_SERVER['DOCUMENT_ROOT']."prokal/";

	$_PRIVATE['path'] = $_GET['q'];

	if(preg_match('/.php$/', $_PRIVATE['path']))
		require 'html/error.php';

	require 'function-global.php';

	$var['pexp'] = explode("/", $_GET['q']);
	$var['count'] = count($var['pexp']);

	$var['pexp'] = explode("/", $_PRIVATE['path']);

	$temp = '';
	switch ($var['pexp'][0]):
		case 'dash':

			switch ($var['pexp'][1]):
				case 'catman':
					if($var['count'] > 3)
						require 'html/error.php';

					$_GET['d'] = 'catman';
					$_PRIVATE['path'] = 'dash/index.php';

					if(isset($var['pexp'][2]) && $var['pexp'][2] !== ''):
						$_GET['id'] = $var['pexp'][2];
					endif;
				break;

				case 'mail':
					if($var['count'] > 3)
						require 'html/error.php';

					if($var['pexp'][$var['count']-1] !== 'processor'):
						$_GET['d'] = 'mail';
						$_PRIVATE['path'] = 'dash/index.php';
					else:
						$_PRIVATE['path'] = 'dash/mail/fetcher.php';
					endif;
				break;

				case 'dashboard':
					if($var['count'] > 3)
						require 'html/error.php';


					if($var['pexp'][$var['count']-1] !== 'processor'):
						$_GET['d'] = 'dashboard';
						$_PRIVATE['path'] = 'dash/index.php';

						if(isset($var['pexp'][2]) && $var['pexp'][2] !== ''):
							$_GET['id'] = $var['pexp'][2];
						endif;
					else:
						$_PRIVATE['path'] = 'dash/main/fetcher.php';
					endif;
				break;

				case 'docs':
					if($var['count'] > 3)
						require 'html/error.php';

					if($var['pexp'][$var['count']-1] !== 'processor'):
						$_GET['d'] = 'docs';
						$_PRIVATE['path'] = 'dash/index.php';

						if(isset($var['pexp'][2]) && $var['pexp'][2] !== ''):
							$_GET['id'] = $var['pexp'][2];
						endif;
					else:
						$_PRIVATE['path'] = 'dash/docs/fetcher.php';
					endif;
				break;
			endswitch;
		break;

		case 'login':
			$_PRIVATE['path'] = str_replace('login', 'login/index.php', $_PRIVATE['path']);
		break;

		case 'logout':
			$_PRIVATE['path'] = str_replace('logout', 'login/logout.php', $_PRIVATE['path']);
		break;

		case '':
			$_PRIVATE['path'] = 'index.php';
		break;
	endswitch;

	if($var['pexp'][$var['count']-1] == 'processor')
		$_PRIVATE['path'] = str_replace('processor', 'fetcher.php', $_PRIVATE['path']);

	$var['pexp'] = explode("/", $_PRIVATE['path']);
	$var['count'] = count($var['pexp']);

	if(!is_file($_PRIVATE['path']))
		require 'html/error.php';

	if(strpos($var['pexp'][$var['count']-1], 'php') === false):

		$info['time_mod'] = filemtime($_PRIVATE['path']);
		$info['etag'] = md5_file($_PRIVATE['path']);

		$info['last_mod'] = (isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) ? $_SERVER['HTTP_IF_MODIFIED_SINCE'] : false);
		$info['last_etag'] = (isset($_SERVER['HTTP_IF_NONE_MATCH']) ? trim($_SERVER['HTTP_IF_NONE_MATCH']) : false);

		header("Last-Modified: ".gmdate("D, d M Y H:i:s", $info['time_mod'])." GMT");
		header("Etag: ".$info['etag']);
		header('Cache-Control: public');

		if (@strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']) == $info['time_mod'] || $info['etag'] == $info['last_etag']):

		    header("HTTP/1.1 304 Not Modified");
		    exit;

		endif;

	endif;
var_dump(microtime(true));
	if(strpos($var['pexp'][$var['count']-1], ".js")): header('Content-Type: application/javascript');
	elseif(strpos($var['pexp'][$var['count']-1], ".css")): header('Content-Type: text/css');
	elseif(strpos($var['pexp'][$var['count']-1], ".png")): header('Content-Type: image/png');
	elseif(strpos($var['pexp'][$var['count']-1], ".jpg")): header('Content-Type: image/jpeg');
	elseif(strpos($var['pexp'][$var['count']-1], ".jpeg")): header('Content-Type: image/jpeg');
	elseif(strpos($var['pexp'][$var['count']-1], ".woff")): header('Content-Type: font/woff');
	elseif(strpos($var['pexp'][$var['count']-1], ".ttf")): header('Content-Type: font/ttf');
	elseif(strpos($var['pexp'][$var['count']-1], ".woff2")): header('Content-Type: font/woff2');


	elseif(strpos($var['pexp'][$var['count']-1], ".php")):
	 	unset($var);
	 	unset($temp);
		require $_PRIVATE['path']; 
		exit();
	endif;

	echo file_get_contents($_PRIVATE['path']);

 	