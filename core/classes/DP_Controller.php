<?php
class DP_Controller
{
    public $db;
    public function __construct()
    {
        $this->db = new DP_Database();
    }

    /**
     * Call project views files
     * @param $viewName [string] view file name
     * @param $data [Array] Pass data to views file
     */
    public function view($viewName, array $data = [])
    {
        extract($data);
        if (file_exists(BASE_PATH . "app/views/$viewName.php")) {
            require_once(BASE_PATH . "app/views/$viewName.php");
        } else {
            echo "$viewName.php file not found";
        }
    }


    /**
     * Call project third party libraries
     * @param $libraryName [string] library file name
     * @return [Object] return library object or show error message
     */
    public function libraries($libraryName)
    {
        $library = @explode("/", $libraryName);
        if (file_exists(BASE_PATH . "app/libraries/$libraryName.php")) {
            require_once(BASE_PATH . "app/libraries/$libraryName.php");
            $classIndex = count($library) - 1;
            return new $library[$classIndex];
        } else {
            echo $library[count($library) - 1] . ".php file not found!";
        }
    }


    /**
     * Call project models
     * @param $modelName [string] model file name
     * @return [Object] return model object or show error message
     */
    public function model($modelName)
    {
        $model = @explode("/", $modelName);
        if (file_exists(BASE_PATH . "app/models/$modelName.php")) {
            require_once(BASE_PATH . "app/models/$modelName.php");
            $classIndex = count($model) - 1;
            return new $model[$classIndex];
        } else {
            echo $model[count($model) - 1] . ".php file not found!";
        }
    }


    /**
     * Get post and get params values
     * @param $inputName [string] get and post input key
     * @return [mixed] return get and post input value or array, show the error message
     */
    public function input($inputName = "")
    {
        if ($inputName != "") {
            if (strtolower($_SERVER['REQUEST_METHOD']) == 'post') {
                $data = $_POST[$inputName];
                if (is_array($_POST[$inputName])) {
                    $data = @implode('()', $_POST[$inputName]);
                }
                $Rdata = trim(strip_tags($data));
            } else if (strtolower($_SERVER['REQUEST_METHOD']) == 'get') {
                $data = $_GET[$inputName];
                if (is_array($_GET[$inputName])) {
                    $data = @implode('()', $_GET[$inputName]);
                }
                $Rdata = trim(strip_tags($data));
            }
            if (strpos($Rdata, '()') !== false) {
                return @explode("()", $Rdata);
            }
        } else {
            if (strtolower($_SERVER['REQUEST_METHOD']) == 'post') {
                $Rdata = $_POST;
            } else if (strtolower($_SERVER['REQUEST_METHOD']) == 'get') {
                unset($_GET['url']);
                $Rdata = $_GET;
            }
        }

        return $Rdata;
    }

    /**
     * Call project helper
     * @param $helperName [string] helper file name
     */
    public function helper($helperName)
    {
        if (file_exists(BASE_PATH . "app/helpers/$helperName.php")) {
            require_once BASE_PATH . "app/helpers/$helperName.php";
        } else {
            echo "$helperName.php file not found!";
        }
    }

    /**
     * Get project constent values
     * @param $itemKey [string] constent key
     * @return [mixed] return constent value or bool false
     */
    public function constent($itemKey)
    {
        require_once(BASE_PATH . "config/constents.php");
        if (array_key_exists($itemKey, $constent)) {
            return $constent[$itemKey];
        } else {
            return false;
        }
    }

    /**
     * Set session value
     * @param $sessionName [string] session key
     * @param $sessionValue [any] session value
     * @return [bool] return bool false in case session name or session value empty
     */
    public function setSession($sessionName, $sessionValue)
    {
        if (!empty($sessionName) && !empty($sessionValue)) {
            $_SESSION[$sessionName] = $sessionValue;
        } else {
            return false;
        }
    }


    /**
     * get the session value
     * @param $sessionName [string] session key
     * @return  [mixed] return session value or array
     */
    public function getSession($sessionName = "")
    {
        if (!empty($sessionName)) {
            return $_SESSION[$sessionName];
        } else {
            return $_SESSION;
        }
    }

    /**
     * unset the specific session
     * @param $sessionName [string] session key
     * @return [bool] return bool true or false
     */
    public function unsetSession($sessionName)
    {
        if (!empty($sessionName)) {
            unset($_SESSION[$sessionName]);
            return true;
        } else {
            return false;
        }
    }


    /**
     * End all Session
     */
    public function endSessions()
    {
        session_unset();
        session_destroy();
    }

    /**
     * Redirect the specific url
     * @param  [string] redirect page 
     */
    public function redirect($path)
    {
        header("Location:" . BASE_URL . '/' . $path);
    }

    /**
     * Get params value from the url
     * @param  [integer] index number
     * @return [mixed] return params value or bool false
     */
    public function params(int $index)
    {
        $uri = explode('/', $_GET['url']);
        if (isset($uri[$index])) {
            return trim(strip_tags($uri[$index]));
        } else {
            return false;
        }
    }


    /**
     * Returns a page's token.
     * - Page name is required so that users can browse to multiple pages and allows for each
     *   page to have its own unique token
     *
     * @param  [string]   page name
     * @param  [int]      expiry time
     * @return [mixed]    markup to be used in the ajax, false on data missing
     */
    public function get_csrf($page, $expiry = 1800, $length = 100)
    {
        if (empty($page)) {
            trigger_error('Page is missing.', E_USER_ERROR); // rm psr
            return false;
        }

        $token = (Csrf::getSessionToken($page) ? Csrf::getSessionToken($page) : Csrf::setNewToken($page, $expiry, $length));

        if (time() > (int) $token->expiry) {
            $token = Csrf::setNewToken($page, $expiry, $length);
        }

        return $token->sessiontoken;
    }

    /**
     * Returns a input's token.
     * @return [input]
     */

    public function csrfInput()
    {
        $csrf_input = Csrf::csrf_input();
        return $csrf_input;
    }

    /**
     * Verify's a request token against a session token
     * @param  [string]    page name
     * @param  [string]    token from the request
     * @return [bool]      whether the request submission is valid or not
     */
    public function verify_csrf($page, $removeToken = false, $requestToken = null)
    {

        // if the request token has not been passed, check POST
        $requestToken = ($requestToken ? $requestToken : ($this->input('csrftoken') != "" ? $this->input('csrftoken') : null));
        if (empty($page)) {
            trigger_error('Page alias is missing', E_USER_WARNING); // rm psr
            return false;
        } else if (empty($requestToken)) {
            trigger_error('Token is missing', E_USER_WARNING); // rm psr
            return false;
        }

        $token = Csrf::getSessionToken($page);

        // if the time is greater than the expiry form submission window
        if (empty($token) || time() > (int) $token->expiry) {
            Csrf::removeToken($page);
            return false;
        }

        // check the hash matches the Session / Cookie
        $sessionConfirm = Csrf::hashEquals($token->sessiontoken, $requestToken);
        $cookieConfirm =  Csrf::hashEquals($token->cookietoken, Csrf::getCookieToken($page));

        // remove the token
        if ($removeToken) {
            Csrf::removeToken($page);
        }
        if ($sessionConfirm) {
            return true;
        }

        return false;
    }


    /**
     * Get error message
     *
     * @param $fieldName
     * @return mixed|string
     */
    public function getError($fieldName)
    {
        return Validator::get_field_error($fieldName);
    }


    /**
     * @param array $rules list of rules
     * @param array $payload list of form parameters
     * @param array $message list of form custom error message
     * @return bool Return validation result, same as isValid
     */
    public function form_validate(array $rules, array $payload, array $message = [])
    {
        return Validator::validate($rules, $payload, $message);
    }

    /**
     * Generate Random String
     * @param int $length length of Random Generated string
     */
    public function generateRandomString($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ-';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    /**
     * Get encoded string 
     * @param string $string you want to encode
     */
    public function encode($string)
    {
        if (is_numeric($string)) {
            $str = (string)$string;
        } else {
            $str = $string;
        }
        $encoded = '';
        for ($i = 0; $i < strlen($str); $i++) {
            $encoded .= $this->generateRandomString(10) . $str[$i];
        }
        $encoded = json_encode($encoded);
        $encoded = str_rot13($encoded);
        $encoded = base64_encode($encoded);
        return $encoded;
    }

    /**
     * Get decoded string 
     * @param string $string pass encoded string
     */

    public function decode($string)
    {
        $decode = base64_decode($string);
        $decode = str_rot13($decode);
        $decode = json_decode($decode);
        $j = 10;
        $decoded = '';
        for ($i = 0; $i <= strlen($decode); $i++) {
            if ($i == $j) {
                $decoded .= $decode[$j];
                $j += 11;
            }
        }
        return $decoded;
    }
}
