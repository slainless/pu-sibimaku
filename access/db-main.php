<?php

$query = new mysqli($db_main_host, $db_main_user, $db_main_pass, $db_main_name);
    if ($query -> connect_error) {
        echo "DB CONNECTION ERROR";
        exit();
    }

