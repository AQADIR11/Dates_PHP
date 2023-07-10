<?php

class Csrf
{
    /**
     * Generates a new token
     * @return [object]   token
     * @param string $page where you want token
     * @param integer $expiry in seconds
     * @param string $expiry in seconds
     */
    public static function setNewToken($page, $expiry, int $length = 100)
    {
        $token = new \stdClass();
        $token->page = $page;
        $token->expiry = time() + $expiry;
        $token->sessiontoken = base64_encode(self::GenerateSalts($length));
        $token->cookietoken = md5(base64_encode(mt_rand(10, 100)));
        setcookie(self::makeCookieName($page), $token->cookietoken, $token->expiry, '/');
        return $_SESSION['csrftokens'][$page] = $token;
    }


    /**
     * generate Random Token.
     *
     * @param integer $length length of salts
     *
     * @return string;
     */

     public static function GenerateSalts(int $length)
    {

        $chars = array_merge(range(0, 9), range('a', 'z'), range('A', 'Z'));
        $stringlength = count($chars);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $chars[rand(0, $stringlength - 1)];
        }
        return $randomString;
    }

    /**
     * Returns a session token for a page
     * @param  [string]   page name
     * @return [object]   token
     */
    public static function getSessionToken($page)
    {
        return !empty($_SESSION["csrftokens"][$page]) ? $_SESSION["csrftokens"][$page] : null;
    }


    /**
     * [getCookieToken description]
     * @param  [string]   page name
     * @return [string]   token string / empty string
     */

     public static function getCookieToken($page)
    {

        $value = self::makeCookieName($page);
        return !empty($_COOKIE[$value]) ? $_COOKIE[$value] : '';
    }

    /**
     * Centralised method to make the cookie name
     * @param  [string]   page name
     * @return [string]   cookie token name / empty string
     */
    public static function makeCookieName($page)
    {
        if (empty($page)) {
            return '';
        }
        return 'csrftoken-' . substr(md5($page), 0, 10);
    }


    public static function hashEquals($str1, $str2)
    {
        if (strlen($str1) != strlen($str2)) {
            return false;
        } else {
            $res = $str1 ^ $str2;
            $ret = 0;
            for ($i = strlen($res) - 1; $i >= 0; $i--) {
                $ret |= ord($res[$i]);
            }
            return !$ret;
        }
    }



    /**
     * Removes a token from the session
     * @param  [string] $page page name
     * @return [bool] successfully removed or not
     */
    public static function removeToken($page)
    {

        if (empty($page)) {
            return false;
        }

        unset($_COOKIE[self::makeCookieName($page)], $_SESSION['csrftokens'][$page]);

        return true;
    }

    /**
     * Genrate csrf input
     * @return [input]
     */

     public static function csrf_input() {
        $page = $_SERVER['REQUEST_URI'];
        $expiry = 1800;
        if (empty($page)) {
            trigger_error('Page is missing.', E_USER_ERROR); // rm psr
            return false;
        }
        $token = (self::getSessionToken($page) ? self::getSessionToken($page) : self::setNewToken($page, $expiry));

        return '<input type="hidden" id="_csrf_token_" name="_csrf_token_" value="' . $token->sessiontoken . '">';
    }
}
