<?php
    require_once "dir-conf.php";

    require $access_main;
    require $func_login;

    $session = new session(); // Our custom secure way of starting a PHP session.
    $session->init();
    
    $login = new login($query);
    
if (isset($_POST['username'], $_POST['p']) && !isset($_POST['name'],$_POST['level'])) {
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_EMAIL);
    $password = $_POST['p']; // The hashed password.
    
    if ($login->login($username, $password) == true) {
        header("Location: /dash");
        exit();
    } else {
        header("Location: /?error=1");
        exit();
    }
}
elseif (isset($_POST['name'], $_POST['username'], $_POST['p'], $_POST['level'])) {

        // Sanitize and validate the data passed in
        $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
        $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
        
        $password = filter_input(INPUT_POST, 'p', FILTER_SANITIZE_STRING);
        $level = filter_input(INPUT_POST, 'level', FILTER_SANITIZE_NUMBER_INT);


        $array = array(
            array(0, 2),
            array(1, 3)
            );
        
        for($x=0;$x<$limit;$x++){
            if($level == $array[$x][0]){
                $level = $array[$x][1];
            }
        }

        if ($login->register($name, $username, $password, $level)) {
            echo "WOY SUCCESS";
        } else {
            echo "WOY ERROR";
        }
} else {
    // The correct POST variables were not sent to this page. 
    header('Location: index.php?err=Could not process login');
    exit();
}