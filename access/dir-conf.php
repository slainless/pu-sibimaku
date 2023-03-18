<?php
$require = "master-config.php";

$array = explode("/",$_SERVER["REQUEST_URI"]);
$tlimit = $limit = count($array)-3;

for($x=0; $x<$limit; $x++):
$require = "../".$require;
endfor;

require_once $require;