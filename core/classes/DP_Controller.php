<?php
class DP_Controller
{
    public $db;
    public function __construct()
    {
        $this->db = new DP_Database();
    }

    // Call view 
    public function view($viewName, array $data = [])
    {
        extract($data);
        if (file_exists(BASE_PATH . "app/views/$viewName.php")) {
            require_once(BASE_PATH . "app/views/$viewName.php");
        } else {
            echo "$viewName.php file not found";
        }
    }


    // Call libraries 
    public function libraries($libraryName)
    {
        $library = @explode("/", $libraryName);
        if (file_exists(BASE_PATH . "app/libraries/$libraryName.php")) {
            require_once(BASE_PATH . "app/libraries/$libraryName.php");
            return new $library[count($library) - 1];
        } else {
            echo $library[count($library) - 1] . ".php file not found!";
        }
    }


    // Call Model
    public function model($modelName)
    {
        $model = @explode("/", $modelName);
        if (file_exists(BASE_PATH . "app/models/$modelName.php")) {
            require_once(BASE_PATH . "app/models/$modelName.php");
            return new $model[count($model) - 1];
        } else {
            echo $model[count($model) - 1] . ".php file not found!";
        }
    }


    // Call GET POST method
    public function input($inputName = "")
    {
        if ($inputName != "") {
            if ($_SERVER['REQUEST_METHOD'] == 'POST' || $_SERVER['REQUEST_METHOD'] == 'post') {
                $data = $_POST[$inputName];
                if (is_array($_POST[$inputName])) {
                    $data = @implode('()', $_POST[$inputName]);
                }
                $Rdata = trim(strip_tags($data));
            } else if ($_SERVER['REQUEST_METHOD'] == 'GET' || $_SERVER['REQUEST_METHOD'] == 'get') {
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
            if ($_SERVER['REQUEST_METHOD'] == 'POST' || $_SERVER['REQUEST_METHOD'] == 'post') {
                $Rdata = $_POST;
            } else if ($_SERVER['REQUEST_METHOD'] == 'GET' || $_SERVER['REQUEST_METHOD'] == 'get') {
                unset($_GET['url']);
                $Rdata = $_GET;
            }
        }

        return $Rdata;
    }

    // Call Halper
    public function helper($helperName)
    {
        if (file_exists(BASE_PATH . "app/helpers/$helperName.php")) {
            require_once BASE_PATH . "app/helpers/$helperName.php";
        } else {
            echo "$helperName.php file not found!";
        }
    }

    // Get Constent items
    public function constent($itemKey)
    {
        require_once(BASE_PATH . "config/constents.php");
        if (array_key_exists($itemKey, $constent)) {
            return $constent[$itemKey];
        } else {
            return false;
        }
    }

    // Set Session
    public function setSession($sessionName, $sessionValue)
    {
        if (!empty($sessionName) && !empty($sessionValue)) {
            $_SESSION[$sessionName] = $sessionValue;
        } else {
            return false;
        }
    }


    // Get Session
    public function getSession($sessionName = "")
    {
        if (!empty($sessionName)) {
            return $_SESSION[$sessionName];
        } else {
            return $_SESSION;
        }
    }

    // Unset single session
    public function unsetSession($sessionName)
    {
        if (!empty($sessionName)) {
            unset($_SESSION[$sessionName]);
            return true;
        } else {
            return false;
        }
    }


    // Destroy all session
    public function endSessions()
    {
        session_unset();
        session_destroy();
    }

    // Redirect Method
    public function redirect($path)
    {
        header("Location:" . BASE_URL . '/' . $path);
    }

    // Get Params Value
    public function params($index)
    {
        $uri = explode('/', $_GET['url']);
        if (isset($uri[$index])) {
            return trim(strip_tags($uri[$index]));
        } else {
            return false;
        }
    }

    protected static function setNewToken($page, $expiry, int $length)
    {
        $token = new \stdClass();
        $token->page = $page;
        $token->expiry = date("Y-m-d H:i:s", strtotime("+$expiry minutes"));
        $token->sessiontoken = base64_encode(self::GenerateSalts($length));
        $token->cookietoken = md5(base64_encode(mt_rand(10, 100)));
        return $_SESSION['csrftokens'][$page] = $token;
    }


    protected static function GenerateSalts(int $length)
    {

        $chars = array_merge(range(0, 9), range('a', 'z'), range('A', 'Z'));
        $stringlength = count($chars);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $chars[rand(0, $stringlength - 1)];
        }
        return $randomString;
    }
}
