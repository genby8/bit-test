<?php

namespace Application\Session;


class SessionWrapper
{
    public static function start()
    {
        if (self::sessionExists()) {
            return;
        }
        session_start();
    }

    public static function sessionExists(): bool
    {
        if (session_status() == PHP_SESSION_ACTIVE) {
            return true;
        }
        return false;
    }

    public static function destroy()
    {
        if (!self::sessionExists()) {
            return;
        }
        session_destroy();
    }

    public static function writeClose()
    {
        if (!self::sessionExists()) {
            return;
        }
        session_write_close();
    }

    public static function getData($key)
    {
        if (!self::sessionExists()) {
            return null;
        }
        if (isset($_SESSION[$key])) {
            return $_SESSION[$key];
        }
    }

    public static function setData($key, $value)
    {
        if (!self::sessionExists()) {
            return;
        }
        $_SESSION[$key] = $value;
    }

    public static function unsetData($key)
    {
        if (!self::sessionExists()) {
            return;
        }
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
    }

    public static function regenerateId()
    {
        if (!self::sessionExists()) {
            return;
        }
        session_regenerate_id();
    }

}