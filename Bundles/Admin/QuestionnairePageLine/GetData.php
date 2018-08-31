<?php
/**
 * Class GetData
 *
 * @author      Przemysław Kotlarz <todofenn@gmail.com>
 * @package     Ankiety
 */


namespace Bundles\Admin\QuestionnairePageLine;

use KPrzemyslaw\Actions\KPController;

/**
 * Class GetData
 * @package Bundles\Admin\QuestionnairePageLine
 */
class GetData extends KPController
{
    /** @var \Bundles\Admin\QuestionnairePage $questionnairePageLine */
    private $questionnairePageLine = null;

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

        $this->questionnairePageLine = new \Bundles\Admin\QuestionnairePageLine($dbResource);
    }

    public function run()
    {
        return $this->questionnairePageLine->getDataById(
            (int)$this->request->getData('id')
        );
    }
}
