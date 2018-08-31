<?php
namespace KPrzemyslaw\Actions;

/**
 * Class KPControllerAbstract
 * @package Actions
 */
abstract class KPControllerAbstract
{
    /** @var \KPrzemyslaw\KPRequest $request */
    public $request = null;

    /** @var \KPrzemyslaw\KPSession $session */
    public $session = null;

    /** @var \PDO $dbResource */
    public $dbResource = null;

    /** @var \KPrzemyslaw\KPUser $user */
    public $user = null;

    /**
     * KPControllerAbstract constructor.
     * @param \KPrzemyslaw\KPRequest  $requestObject
     * @param \KPrzemyslaw\KPSession  $sessionObject
     * @param \PDO                  $dbResource
     * @param \KPrzemyslaw\KPUser     $user
     */
    public function __construct(
        \KPrzemyslaw\KPRequest    $requestObject,
        \KPrzemyslaw\KPSession    $sessionObject,
        \PDO                    $dbResource,
        \KPrzemyslaw\KPUser       $user
    )
    {
        $this->request = $requestObject;
        $this->session = $sessionObject;
        $this->dbResource = $dbResource;
        $this->user = $user;
    }
}
