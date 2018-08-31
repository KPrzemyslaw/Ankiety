<?php
/**
 * Class GetList
 *
 * @author      Przemysław Kotlarz <todofenn@gmail.com>
 * @package     Ankiety
 */


namespace Bundles\Admin\QuestionnairePageLineField;

use KPrzemyslaw\Actions\KPController;

/**
 * Class GetList
 * @package Bundles\Admin\Questionnaire
 */
class GetList extends KPController
{
    /** @var \Bundles\Admin\QuestionnairePageLineField $questionnairePageLineField */
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
        $lineId = (int)$this->request->getData('line_id');
        if($lineId > 0) {
            $this->questionnairePageLineField->setWhere('line_id = '.$lineId);
            return $this->questionnairePageLineField->getList();
        }

        return [];
    }
}
