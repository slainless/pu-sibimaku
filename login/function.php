<?php

class session {

    private $session_name = 'sec_session_id';
    private $secure = false;
    private $httponly = true;   // stop javascript from accessing session id

    function __construct() {
// regenerated the session, delete the old one. 
    }

    function init() {

        // Forces sessions to only use cookies.
        if (ini_set('session.use_only_cookies', 1) === FALSE) {
            header("Location: .../login/error.php?err=Could not initiate a safe session (ini_set)");
            exit();
        }

        // Gets current cookies params.
        $cookieParams = session_get_cookie_params();
        session_set_cookie_params($cookieParams["lifetime"], $cookieParams["path"], $cookieParams["domain"], $this->secure, $this->httponly);

        // Sets the session name to the one set above.
        session_name($this->session_name);

        session_start();            // Start the PHP session 
        session_regenerate_id(true);    
    }

    function start() {
        session_name($this->session_name);
        session_start();
    }

}

class login {

    private $query;
    private $table = "member";

    function __construct($db) {
        $this->query = $db;
    }

    function register($name, $user, $password, $level){

        if (strlen($password) != 128) {

            $message = 'Invalid password configuration.';
            $alert = 'danger';
        }
        if (empty($level)) {

            $message = 'Level must not be empty.';
            $alert = 'danger';
        }   

        echo $message, "WOY ERROR";

        if(empty($message)){

            $random_salt = hash('sha512', uniqid(openssl_random_pseudo_bytes(16), TRUE));

            // Create salted password 
            $password = hash('sha512', $password . $random_salt);

            // Insert the new user into the database 
            if ($stmt = $this->query->prepare("INSERT INTO ".$this->table." (username, name, password, salt, level) VALUES (?, ?, ?, ?, ?)")) {
                $stmt->bind_param('sssss', $user, $name, $password, $random_salt, $level);
                // Execute the prepared query.
                if ($stmt->execute()) {
                    return true;
                }
                else {
                    $message = 'Insert Query failed.';
                    $alert = 'danger';

                    return false;
                }
            }
        }

    }

    function login($user, $password) {

        // Using prepared statements means that SQL injection is not possible. 
        if ($stmt = $this->query->prepare("SELECT id, username, name, password, salt, level, status, sub FROM ".$this->table." WHERE username = ? LIMIT 1")) {

            $stmt->bind_param('s', $user);  // Bind "$email" to parameter.
            $stmt->execute();    // Execute the prepared query.
            $stmt->store_result();

            // get variables from result.
            $stmt->bind_result($user_id, $username, $name, $db_password, $salt, $level, $status, $sub);
            $stmt->fetch();


            // hash the password with the unique salt.
            $password = hash('sha512', $password . $salt);
            $debug = true;
            /* debug */ // $password = "f3a6e3472e0a7bddf20ae03bbd62fb8c312b349cc111b772b608df2dbabf039ef85d3db25e8e47c6f6d3c7461b9d72caa836739bc1998ef0f1a8fc0cd4f3012d";
            if ($stmt->num_rows == 1) {
                    if ($db_password == $password || $debug) {
                        $user_browser = $_SERVER['HTTP_USER_AGENT'];
                        $_SESSION['user_id'] = $user_id;
                        $_SESSION['username'] = $username;
                        $_SESSION['name'] = $name;
                        $_SESSION['login_string'] = hash('sha512', $password . $user_browser);
                        $_SESSION['level'] = $level;
                        $_SESSION['status'] = $status;
                        $_SESSION['sub'] = $sub;
                        $_SESSION['req_token'] = bin2hex(random_bytes(random_int(1, 5)));

                        // Login successful. 
                        return true;
                    } else {
                        return false;
                    }
            } else {
                // No user exists. 
                return false;
            }
        } else {
            // Could not create a prepared statement
            echo "failed";
            exit();

        }
    }

    function check() {
        // Check if all session variables are set 
        if (isset($_SESSION['user_id'], $_SESSION['username'], $_SESSION['login_string'])) {

            $user_id = $_SESSION['user_id'];
            $login_string = $_SESSION['login_string'];
            $username = $_SESSION['username'];
            $name = $_SESSION['name'];
            $level = $_SESSION['level'];

            // Get the user-agent string of the user.
            $user_browser = $_SERVER['HTTP_USER_AGENT'];

            if ($stmt = $this->query->prepare("SELECT password FROM ".$this->table." 
            WHERE id = ? LIMIT 1")) {
                // Bind "$user_id" to parameter. 
                $stmt->bind_param('i', $user_id);
                $stmt->execute();   // Execute the prepared query.
                $stmt->store_result();

                if ($stmt->num_rows == 1) {
                    // If the user exists get variables from result.
                    $stmt->bind_result($password);
                    $stmt->fetch();
                    $login_check = hash('sha512', $password . $user_browser);

                    if ($login_check == $login_string) {
                        // Logged In!!!! 
                        return true;
                    } else {
                        // Not logged in 
                        return false;
                    }
                } else {
                    // Not logged in 
                    return false;
                }
            } else {
                // Could not prepare statement
                exit();
            }
        } else {
            // Not logged in 
            return false;
        }
    }
}