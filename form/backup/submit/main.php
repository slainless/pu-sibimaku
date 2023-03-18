<?php

$dir_html = $dir_html.'form-submit/';
require $dir_html."header.html";

require 'function.php';

$inbox = new inbox($query);
$ls_id = $inbox->checkInbox($s_id);
if(!$ls_id):
    //
    if(isset($_POST['title'], $_POST['main'], $_POST['attach'])):
        require 'submit/process.php';
    else:
        require $dir_html."main.html";
    endif;
    //
else:
    if(isset($_POST['title'], $_POST['main'], $_POST['attach'])):
        require 'submit/process.php';
    else:
        if($ls_id[0] == $s_id || $ls_id[1] != $s_id){
            require $dir_html."main-progress.html";
        }
        else{
            require 'submit/revise.php';
        }
    endif;
endif;

require $dir_html."footer.html";