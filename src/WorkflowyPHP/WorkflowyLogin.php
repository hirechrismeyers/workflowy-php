<?php namespace WorkflowyPHP;

use WorkflowyPHP\Workflowy;

class WorkflowyLogin
{

    /**
     * Tries to log in using the given credentials
     * Returns the session ID on success, FALSE otherwise
     * @param string $username
     * @param string $password
     * @return bool|string
     */
    public function login($username, $password)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, Workflowy::LOGIN_URL);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, Workflowy::LOGIN_TIMEOUT);
        curl_setopt($ch, CURLOPT_POSTFIELDS, array(
            'username' => $username,
            'password' => $password,
            'next'     => ''
        ));
        $raw_data = curl_exec($ch);
        curl_close($ch);
        preg_match('#^Set-Cookie:\s*sessionid=([^;]*)#mi', $raw_data, $session_id_match);
        if (preg_match('#^Location:#mi', $raw_data) && !empty($session_id_match[1]))
        {
            return $session_id_match[1];
        }
        return false;
    }

}
