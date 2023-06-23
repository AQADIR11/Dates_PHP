<?php

class Controller extends Validator
{
    /**
     * Generates a new token
     * @return [object]   token
     * @param integer $expiry in secondes
     */
    protected static function setNewToken($page, $expiry, int $length)
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

    /**
     * Returns a session token for a page
     * @param  [string]   page name
     * @return [object]   token
     */
    protected static function getSessionToken($page)
    {
        return !empty($_SESSION["csrftokens"][$page]) ? $_SESSION["csrftokens"][$page] : null;
    }


    /**
     * [getCookieToken description]
     * @param  [string]   page name
     * @return [string]   token string / empty string
     */

    protected static function getCookieToken($page)
    {

        $value = self::makeCookieName($page);
        return !empty($_COOKIE[$value]) ? $_COOKIE[$value] : '';
    }

    /**
     * Centralised method to make the cookie name
     * @param  [string]   page name
     * @return [string]   cookie token name / empty string
     */
    protected static function makeCookieName($page)
    {
        if (empty($page)) {
            return '';
        }
        return 'csrftoken-' . substr(md5($page), 0, 10);
    }


    protected static function hashEquals($str1, $str2)
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
     * @param  [string] $page    page name
     * @return [bool]            successfully removed or not
     */
    protected static function removeToken($page)
    {

        if (empty($page)) {
            return false;
        }

        unset($_COOKIE[self::makeCookieName($page)], $_SESSION['csrftokens'][$page]);

        return true;
    }
}
