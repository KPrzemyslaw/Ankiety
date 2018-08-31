<?php
/**
 * Class GetData
 *
 * @author      Przemysław Kotlarz <todofenn@gmail.com>
 * @package     Ankiety
 */


namespace Bundles\Admin\QuestionnairePage;

use KPrzemyslaw\Actions\KPController;

/**
 * Class GetData
 * @package Bundles\Admin\QuestionnairePage
 */
class GetData extends KPController
{
    /** @var \Bundles\Admin\QuestionnairePage $questionnairePage */
    private $questionnairePage = null;

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

        $this->questionnairePage = new \Bundles\Admin\QuestionnairePage($dbResource);
    }

    public function run()
    {
        return $this->questionnairePage->getDataById(
            (int)$this->request->getData('id')
        );
    }
}
