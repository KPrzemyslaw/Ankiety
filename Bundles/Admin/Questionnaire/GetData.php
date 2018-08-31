<?php
/**
 * Class GetData
 *
 * @author      Przemysław Kotlarz <todofenn@gmail.com>
 * @package     Ankiety
 */


namespace Bundles\Admin\Questionnaire;

use KPrzemyslaw\Actions\KPController;

/**
 * Class GetData
 * @package Bundles\Admin\Questionnaire
 */
class GetData extends KPController
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
        $this->questionnaire->getDataById((int)$this->request->getData('id'));
        $this->questionnaire->getPages();
        return $this->questionnaire->getData();
    }
}
