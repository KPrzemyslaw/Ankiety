<?php
/**
 * Class GetData
 *
 * @author      Przemysław Kotlarz <todofenn@gmail.com>
 * @package     Ankiety
 */


namespace Bundles\Admin\QuestionnairePageLineField;

use KPrzemyslaw\Actions\KPController;

/**
 * Class GetData
 * @package Bundles\Admin\QuestionnairePageLineField
 */
class GetData extends KPController
{
    /** @var \Bundles\Admin\QuestionnairePage $questionnairePageLineField */
    private $questionnairePageLineField = null;

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

        $this->questionnairePageLineField = new \Bundles\Admin\QuestionnairePageLineField($dbResource);
    }

    public function run()
    {
        return $this->questionnairePageLineField->getDataById(
            (int)$this->request->getData('id')
        );
    }
}
