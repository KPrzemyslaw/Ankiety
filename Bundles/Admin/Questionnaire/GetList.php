<?php
/**
 * Class GetList
 *
 * @author      Przemysław Kotlarz <todofenn@gmail.com>
 * @package     Ankiety
 */


namespace Bundles\Admin\Questionnaire;

use KPrzemyslaw\Actions\KPController;

/**
 * Class GetList
 * @package Bundles\Admin\Questionnaire
 */
class GetList extends KPController
{
    /** @var \Bundles\Admin\Questionnaire $questionnaire */
    private $questionnaire = null;

    /**
     * Save constructor.
     * @param \KPrzemyslaw\KPRequest  $requestObject
     * @param \KPrzemyslaw\KPSession  $sessionObject
     * @param \PDO                  $dbResource
     * @param \KPrzemyslaw\KPUser     $user
     */
    public function __construct(\KPrzemyslaw\KPRequest $requestObject, \KPrzemyslaw\KPSession $sessionObject, \PDO $dbResource, \KPrzemyslaw\KPUser $user)
    {
        parent::__construct($requestObject, $sessionObject, $dbResource, $user);

        $this->questionnaire = new \Bundles\Admin\Questionnaire($dbResource);
    }

    public function run()
    {
        return $this->questionnaire->getList(\KPrzemyslaw\DB\AKPDB::DB_LIST_ORDER_DESC);
    }
}
