<?php
$require = "master-config.php";

$a = count(explode("/",$_SERVER["DOCUMENT_ROOT"])) + 1;
if(substr($_SERVER['DOCUMENT_ROOT'], 1, 1) != ':') $a++;
$b = count(explode("/",$_SERVER["SCRIPT_FILENAME"])) - $a;

$tlimit = $limit = $b;

for($x=0; $x<$b; $x++):
$require = "../".$require;
endfor;

require_once $require;

require_once $access_main;
require_once $func_login;

require_once '../function-dash.php';