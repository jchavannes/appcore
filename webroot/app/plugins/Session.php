<?php

class Session {

    const MAX_SESSION_LENGTH = 604800;  // One week
    const COOKIE_ID_LENGTH = 604800; // Used to be 15 sec, but don't really see the point anymore

    const DATABASE_ID = "database_id";
    const LOGGED_IN = 'logged_in';
    const USER_ID = 'user_id';
    const EMAIL = 'email';
    const USERNAME = 'username';
    const PERMISSIONS = 'permissions';
    const CSRF_TOKEN = 'csrf_token';

    static public function load() {

        ini_set("session.cookie_httponly", 1);
        if (FORCE_SSL_ONLY) {
            ini_set("session.cookie_secure", 1);
        }
        session_start();

        $SessionTbl = new SessionTbl();
        $session = $SessionTbl->getSession(session_id());
        $time = time();
        $ip = Session::getRemoteAddr();

        if (!empty($session[SessionTbl::ID])
            && $session[SessionTbl::IP_ADDRESS] == $ip
            && $session[SessionTbl::FIRST_VISIT] > $time - SESSION::MAX_SESSION_LENGTH
            && isset($_SESSION[self::DATABASE_ID])) {

            $updates = array(
                SessionTbl::VISITS => $session[SessionTbl::VISITS] + 1,
                SessionTbl::LAST_VISIT => $time
            );
            if ($session[SessionTbl::ID_RESET] < $time - self::COOKIE_ID_LENGTH) {
                session_regenerate_id();
                $updates[SessionTbl::PHPSESHID] = session_id();
                $updates[SessionTbl::ID_RESET] = $time;
            }
            $SessionTbl->updateSession($updates, $session[SessionTbl::ID]);

        }
        else {

            $_SESSION[Session::LOGGED_IN] = false;
            $fields = array(
                SessionTbl::IP_ADDRESS => $ip,
                SessionTbl::PHPSESHID => session_id(),
                SessionTbl::FIRST_VISIT => $time,
                SessionTbl::LAST_VISIT => $time,
                SessionTbl::ID_RESET => $time,
                SessionTbl::VISITS => 1
            );
            $stmt = $SessionTbl->createSession($fields);
            $_SESSION[self::DATABASE_ID] = $stmt->insert_id;

        }

    }

    static public function login($fields) {
        $user = new UserTbl();
        $user_data = $user->login($fields);
        if ($user_data[UserTbl::ID]) {
            Session::setUser($user_data);
            return true;
        }
        return false;
    }

    static public function getRemoteAddr() {
        return !empty($_SERVER["HTTP_CF_CONNECTING_IP"]) ? $_SERVER["HTTP_CF_CONNECTING_IP"] : $_SERVER["REMOTE_ADDR"];
    }

    static public function setUser($user_data) {
        $SessionTbl = new SessionTbl();
        $SessionTbl->setUser($user_data[UserTbl::ID]);
        $_SESSION[Session::LOGGED_IN] = true;
        $_SESSION[Session::USER_ID] = $user_data[UserTbl::ID];
        $_SESSION[Session::USERNAME] = $user_data[UserTbl::USERNAME];
        $_SESSION[Session::PERMISSIONS] = $user_data[UserTbl::PERMISSIONS];
    }

    static public function reset() {
        session_unset();
        session_regenerate_id();
    }

    static public function isLoggedIn() {
        return (isset($_SESSION[Session::LOGGED_IN]) && $_SESSION[Session::LOGGED_IN]);
    }

    static public function username() {
        if (isset($_SESSION[Session::USERNAME]) && !empty($_SESSION[Session::USERNAME])) {
            return $_SESSION[Session::USERNAME];
        } else {
            return false;
        }
    }

    static public function checkPermission($permission) {
        if (!isset($_SESSION['permissions']) || $_SESSION['permissions'] <= 0 || !Session::isLoggedIn()) {
            return false;
        }

        $permissions = Session::getPermissions($_SESSION['permissions']);
        return in_array($permission, $permissions);
    }

    static public function getPermissions($num) {
        $permissions = array();
        while($num > 0) {
            $max = 1;
            while($max <= $num) {$max *= 2;}
            $max /= 2;
            $num -= $max;
            array_push($permissions, $max);
        }
        return $permissions;
    }

}
