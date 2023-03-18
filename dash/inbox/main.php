<?php
$dir_html2 = $dir_html.'form-inbox/';
require $dir_html2."header.html";

require_once 'function.php';

$inbox = new inbox($query);

if(isset($_POST["status"], $_POST["komentar"])):

    require 'check/process.php';
    require 'inbox/display-inbox.php';

else:

    if(isset($_GET["id"], $_GET["in"])):
        require 'check/main.php';
    else:
        require 'inbox/display-inbox.php';
    endif;

endif;
require $dir_html2."footer.html";