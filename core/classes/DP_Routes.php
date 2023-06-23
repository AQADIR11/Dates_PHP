<?php
class DP_Routes
{

    public static $controller = 'Welcome';
    public static $method     = 'index';
    public static $params     = [];
    public static function App()
    {
        $url = self::url();
        if (!empty($url)) {
            $controllersPath = BASE_PATH . 'app/controllers/';
            $j = 0;
            $count = count($url);
            for ($i = 0; $i < $count; $i++) {
                if (is_dir($controllersPath . $url[$i])) {
                    $controllersPath .= $url[$i] . '/';
                    unset($url[$i]);
                    $j++;
                }
            }
            if (file_exists($controllersPath . $url[$j] . '.php')) {
                self::$controller = $url[$j];
                unset($url[$j]);
                require_once $controllersPath . self::$controller . '.php';
                self::$controller = new self::$controller;
            } else {
                echo '<div class="alert alert-danger" style="
                            color: #a94442; 
                            background-color: #f2dede; 
                            border-color: #ebccd1;  
                            padding: 15px;
                            margin-bottom: 20px;
                            border: 1px solid transparent;
                            border-top-color: transparent;
                            border-right-color: transparent;
                            border-bottom-color: transparent;
                            border-left-color: transparent;
                            border-radius: 4px;">
                            <strong>Error!</strong> Sorry ' . $url[$j] . ' Controller Not Found.
                </div>';
                exit;
            }
        }


        if (isset($url[$j + 1]) && !empty($url[$j + 1])) {
            if (method_exists(self::$controller, $url[$j + 1])) {
                self::$method = $url[$j + 1];
                unset($url[$j + 1]);
            } else {
                echo '<div class="alert alert-danger" style="
                            color: #a94442; 
                            background-color: #f2dede; 
                            border-color: #ebccd1;  
                            padding: 15px;
                            margin-bottom: 20px;
                            border: 1px solid transparent;
                            border-top-color: transparent;
                            border-right-color: transparent;
                            border-bottom-color: transparent;
                            border-left-color: transparent;
                            border-radius: 4px;">
                            <strong>Error!</strong> Sorry ' . $url[$j + 1] . ' Method Not Found.
                </div>';
                exit;
            }
        }

        if (isset($url)) {
            self::$params = $url;
        } else {
            self::$params = [];
        }
        call_user_func_array([self::$controller, self::$method], self::$params);
    }

    public static function url()
    {

        require_once BASE_PATH . "config/routes.php";

        if (isset($_GET['url'])) {
            $uri = '';
            foreach ($routes as $key => $val) {
                $key = str_replace(array('%str%', '%int%'), array('[^/]+', '[0-9]+'), $key);
                if (preg_match('#^' . $key . '$#', $_GET['url'], $matches)) {
                    $uri = preg_replace('#^' . $key . '$#', $val, $_GET['url']);
                }
            }
            if (array_key_exists($_GET['url'], $routes)) {
                $url = $routes[$_GET['url']];
                $url = rtrim($url);
                $url = filter_var($url, FILTER_SANITIZE_URL);
                $url = explode('/', $url);
                $Request = $url;
            } else if ($uri != '') {
                $url = $uri;
                $url = rtrim($url);
                $url = filter_var($url, FILTER_SANITIZE_URL);
                $url = explode('/', $url);
                $Request = $url;
            } else {
                $url = $_GET['url'];
                $url = rtrim($url);
                $url = filter_var($url, FILTER_SANITIZE_URL);
                $url = explode('/', $url);
                $Request = $url;
            }
        } else {
            $url = $routes['default_controller'];
            $url = rtrim($url);
            $url = filter_var($url, FILTER_SANITIZE_URL);
            $url = explode('/', $url);
            $Request = $url;
        }
        return $Request;
    }
}