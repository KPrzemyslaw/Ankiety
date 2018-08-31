<?php

namespace KPrzemyslaw;
use KPrzemyslaw\DB\AKPDB;


/**
 * Class KPUser
 *
 * @author      Przemysław Kotlarz <todofenn@gmail.com>
 * @package     Ankiety
 */
class KPUser extends AKPDB
{
    /** @var KPSession $session */
    private $session;

    public function __construct(\PDO $dbResource, KPSession $session)
    {
        parent::__construct($dbResource);
        $this->session = $session;
    }

    public function getTableName()
    {
        return 'users';
    }

    /**
     * @param string    $login
     * @param string    $pass
     */
    public function signIn($login, $pass)
    {
        $sql = sprintf(
            "SELECT id, login FROM %s WHERE login='%s' AND password='%s' LIMIT 1",
            $this->getTableName(), $login, $pass
        );
        $stmt = $this->getDBResource()->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);

        if(is_array($data)) {
            foreach($data as $name => $value) {
                $this->session->setDataUser($name, $value);
            }
        } else {
            $this->signOut();
        }
    }

    public function signOut()
    {
        $this->session->setDataUser(null, null);
    }

    /**
     * @param mixed|null $key
     * @param mixed|null $default
     * @return mixed
     */
    public function getData($key = null, $default = null)
    {
        return $this->session->getDataUser($key);
    }
}
