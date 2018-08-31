<?php

namespace KPrzemyslaw;

/**
 * Class KPSession
 *
 * @author      Przemysław Kotlarz <todofenn@gmail.com>
 * @package     Ankiety
 */
class KPSession
{
    const KP_SESSION_DEFAULT = 'default';
    const KP_SESSION_USER = 'user';

    public function __construct()
    {
        if(!array_key_exists(static::KP_SESSION_DEFAULT, $_SESSION)) {
            $_SESSION[static::KP_SESSION_DEFAULT] = [];
        }
        if(!array_key_exists(static::KP_SESSION_USER, $_SESSION)) {
            $_SESSION[static::KP_SESSION_USER] = null;
        }
    }

    /**
     * @param string|null   $key
     * @return mixed
     */
    public function getData($key = null, $type = self::KP_SESSION_DEFAULT)
    {
        $type = static::getNormalizedType($type);
        if($key !== null) {
            return isset($_SESSION[$type][$key]) ? $_SESSION[$type][$key] : null;
        }
        return $_SESSION[$type];
    }

    /**
     * @param string|null   $key
     */
    public function setData($key, $value = null, $type = self::KP_SESSION_DEFAULT)
    {
        $_SESSION[static::getNormalizedType($type)][$key] = $value;
    }

    /**
     * @param string|null   $key
     * @return mixed
     */
    public function getDataUser($key = null) {
        return $this->getData($key, static::KP_SESSION_USER);
    }

    /**
     * @param string|null   $key
     * @param string|null   $value
     */
    public function setDataUser($key, $value) {
        if($key === null && $value === null) {
            $_SESSION[static::KP_SESSION_USER] = null;
        }
        $this->setData($key, $value, static::KP_SESSION_USER);
    }

    /**
     * @param mixed $type
     * @return int
     */
    public static function getNormalizedType($type) {
        $type = trim($type);
        if($type !== static::KP_SESSION_DEFAULT && $type !== static::KP_SESSION_USER) {
            $type = static::KP_SESSION_DEFAULT;
        }

        return $type;
    }
}
