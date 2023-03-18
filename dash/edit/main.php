<?php

$dir_html = $dir_html.'dash-edit/';
require $dir_html."header.html";

$get_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
$get_in = filter_input(INPUT_GET, 'm', FILTER_SANITIZE_NUMBER_INT);

require_once 'function.php';

if(isset($_POST["submit"])){
    require 'edit/process.php';
}

switch ($get_in) {
    case 0:
        require 'main/display-info.php';
        break;
    case 1:
        require 'edit/edit-dp.php';
        break;
    case 2:
        require 'edit/edit-pf.php';
        break;  
    case 3:
        require 'edit/edit-mp.php';
        break;   
    case 4:
        require 'edit/edit-rp.php';
        break; 
    case 5:
        require 'edit/edit-ke.php';
        break;   
    case 6:
        require 'edit/edit-ga.php';
        break;   
    case 7:
        require 'edit/edit-mk.php';
        break;    
    case 8:
        require 'edit/edit-na.php';
        break;   
    case 9:
        require 'edit/edit-id.php';
        break;   
    default:
        # code...
        break;
}

require $dir_html."footer.html";