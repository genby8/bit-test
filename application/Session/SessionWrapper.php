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
        self::start();
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
        self::start();
        if (isset($_SESSION[$key])) {
            return $_SESSION[$key];
        }
        self::writeClose();
    }

    public static function setData($key, $value)
    {
        self::start();
        $_SESSION[$key] = $value;
        self::writeClose();
    }

    public static function unsetData($key)
    {
        self::start();
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
        self::writeClose();
    }

    public static function regenerateId()
    {
        self::start();
        session_regenerate_id();
        self::writeClose();
    }

}