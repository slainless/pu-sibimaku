<?php
/**
 * Session Helper Class
 *
 * A simple session wrapper class.
 *
 * Recommended for use with PHP 5.4.0 or higher. (Not required.)
 * 
 * Usage Example:
 * <?php
 * try {
 *     Session::w('foo', 'bar');
 * 
 *     echo Session::r('foo');
 * }
 * catch (Exception $e) {
 *     // do something
 * }
 * ?>
 *
 * Copyright (c) 2013 Robert Dunham
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @remarks A simple session wrapper class.
 * @author Robert Dunham <contact@robdunham.info>
 * @website http://www.robdunham.info
 * @version 1.0.3
 * @date 20130514
 * @copyright Copyright (c) 2013, Robert Dunham
 */
// include_once 'CustomException.php';
if ( !class_exists('CustomException') ) {
    class CustomException extends Exception {}
}
class SessionHandlerException extends CustomException {}
class SessionDisabledException extends SessionHandlerException {}
class InvalidArgumentTypeException extends SessionHandlerException {}
class ExpiredSessionException extends SessionHandlerException {}
class SessionUseOnlyCookiesException extends SessionHandlerException {}
class SessionHttpOnlyCookieException extends SessionHandlerException {}
class SessionCookieSecureException extends SessionHandlerException {}
//defined('CHECK_ACCESS') or die('Direct access is not allowed.');
class Session
{
    /**
     * Session Age.
     * 
     * The number of seconds of inactivity before a session expires.
     * 
     * @var integer
     */
    protected static $SESSION_AGE = 1800;
    
    /**
     * Writes a value to the current session data.
     * 
     * @param string $key String identifier.
     * @param mixed $value Single value or array of values to be written.
     * @return mixed Value or array of values written.
     * @throws InvalidArgumentTypeException Session key is not a string value.
     */
    public static function write($key, $value)
    {
        if ( !is_string($key) )
            throw new InvalidArgumentTypeException('Session key must be string value');
        self::_init();
        $_SESSION[$key] = $value;
        self::_age();
        return $value;
    }
    
    /**
     * Alias for {@link Session::write()}.
     * 
     * @see Session::write()
     * @param string $key String identifier.
     * @param mixed $value Single value or array of values to be written.
     * @return mixed Value or array of values written.
     * @throws InvalidArgumentTypeException Session key is not a string value.
     */
    public static function w($key, $value)
    {
        return self::write($key, $value);
    }
    
    /**
     * Reads a specific value from the current session data.
     * 
     * @param string $key String identifier.
     * @param boolean $child Optional child identifier for accessing array elements.
     * @return mixed Returns a string value upon success.  Returns false upon failure.
     * @throws InvalidArgumentTypeException Session key is not a string value.
     */
    public static function read($key, $child = false)
    {
        if ( !is_string($key) )
            throw new InvalidArgumentTypeException('Session key must be string value');
        self::_init();
        if (isset($_SESSION[$key]))
        {
            self::_age();
            
            if (false == $child)
            {
                return $_SESSION[$key];
            }
            else
            {
                if (isset($_SESSION[$key][$child]))
                {
                    return $_SESSION[$key][$child];
                }
            }
        }
        return false;
    }
    
    /**
     * Alias for {@link Session::read()}.
     * 
     * @see Session::read()
     * @param string $key String identifier.
     * @param boolean $child Optional child identifier for accessing array elements.
     * @return mixed Returns a string value upon success.  Returns false upon failure.
     * @throws InvalidArgumentTypeException Session key is not a string value.
     */
    public static function r($key, $child = false)
    {
        return self::read($key, $child);
    }
    
    /**
     * Deletes a value from the current session data.
     * 
     * @param string $key String identifying the array key to delete.
     * @return void
     * @throws InvalidArgumentTypeException Session key is not a string value.
     */
    public static function delete($key)
    {
        if ( !is_string($key) )
            throw new InvalidArgumentTypeException('Session key must be string value');
        self::_init();
        unset($_SESSION[$key]);
        self::_age();
    }
    
    /**
     * Alias for {@link Session::delete()}.
     * 
     * @see Session::delete()
     * @param string $key String identifying the key to delete from session data.
     * @return void
     * @throws InvalidArgumentTypeException Session key is not a string value.
     */
    public static function d($key)
    {
        self::delete($key);
    }
    
    /**
     * Echos current session data.
     * 
     * @return void
     */
    public static function dump()
    {
        self::_init();
        echo nl2br(print_r($_SESSION));
    }
    /**
     * Starts or resumes a session by calling {@link Session::_init()}.
     * 
     * @see Session::_init()
     * @return boolean Returns true upon success and false upon failure.
     * @throws SessionDisabledException Sessions are disabled.
     */
    public static function start()
    {
        // this function is extraneous
        return self::_init();
    }
    
    /**
     * Expires a session if it has been inactive for a specified amount of time.
     * 
     * @return void
     * @throws ExpiredSessionException() Throws exception when read or write is attempted on an expired session.
     */
    private static function _age()
    {
        $last = isset($_SESSION['LAST_ACTIVE']) ? $_SESSION['LAST_ACTIVE'] : false ;
        
        if (false !== $last && (time() - $last > self::$SESSION_AGE))
        {
            self::destroy();
            throw new ExpiredSessionException();
        }
        $_SESSION['LAST_ACTIVE'] = time();
    }
    
    /**
     * Returns current session cookie parameters or an empty array.
     * 
     * @return array Associative array of session cookie parameters.
     */
    public static function params()
    {
        $r = array();
        if ( '' !== session_id() )
        {
            $r = session_get_cookie_params();
        }
        return $r;
    }
    
    /**
     * Closes the current session and releases session file lock.
     * 
     * @return boolean Returns true upon success and false upon failure.
     */
    public static function close()
    {
        if ( '' !== session_id() )
        {
            return session_write_close();
        }
        return true;
    }
    
    /**
     * Alias for {@link Session::close()}.
     * 
     * @see Session::close()
     * @return boolean Returns true upon success and false upon failure.
     */
    public static function commit()
    {
        return self::close();
    }
    
    /**
     * Removes session data and destroys the current session.
     * 
     * @return void
     */
    public static function destroy()
    {
        if ( '' !== session_id() )
        {
            $_SESSION = array();
            // If it's desired to kill the session, also delete the session cookie.
            // Note: This will destroy the session, and not just the session data!
            if (ini_get("session.use_cookies")) {
                $params = session_get_cookie_params();
                setcookie(session_name(), '', time() - 42000,
                    $params["path"], $params["domain"],
                    $params["secure"], $params["httponly"]
                );
            }
            session_destroy();
        }
    }
    
    /**
     * Initializes a new secure session or resumes an existing session.
     * 
     * @return boolean Returns true upon success and false upon failure.
     * @throws SessionDisabledException Sessions are disabled.
     */
    private static function _init()
    {
        if (function_exists('session_status'))
        {
            // PHP 5.4.0+
            if (session_status() == PHP_SESSION_DISABLED)
                throw new SessionDisabledException();
        }
        
        if ( '' === session_id() )
        {
            $secure = true;
            $httponly = true;
            // Disallow session passing as a GET parameter.
            // Requires PHP 4.3.0
            if (ini_set('session.use_only_cookies', 1) === false) {
                throw new SessionUseOnlyCookiesException();
            }
            // Mark the cookie as accessible only through the HTTP protocol.
            // Requires PHP 5.2.0
            if (ini_set('session.cookie_httponly', 1) === false) {
                throw new SessionHttpOnlyCookieException();
            }
            // Ensure that session cookies are only sent using SSL.
            // Requires a properly installed SSL certificate.
            // Requires PHP 4.0.4 and HTTPS
            //if (ini_set('session.cookie_secure', 1) === false) {
            //    throw new SessionCookieSecureException();
            //}
            $params = session_get_cookie_params();
            session_set_cookie_params($params['lifetime'],
                $params['path'], $params['domain'],
                $secure, $httponly
            );
            return session_start();
        }
        // Helps prevent hijacking by resetting the session ID at every request.
        // Might cause unnecessary file I/O overhead?
        // TODO: create config variable to control regenerate ID behavior
        return session_regenerate_id(true);
    }
}
