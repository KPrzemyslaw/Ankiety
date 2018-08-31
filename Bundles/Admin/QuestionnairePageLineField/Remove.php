<?php
/**
 * Class Remove
 *
 * @author      Przemysław Kotlarz <todofenn@gmail.com>
 * @package     Ankiety
 */


namespace Bundles\Admin\QuestionnairePageLineField;

use KPrzemyslaw\Actions\KPController;

/**
 * Class AddPage
 * @package Bundles\Admin\QuestionnairePageLineField
 */
class Remove extends KPController
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

    /**
     * @return int|null|void
     * @throws \KPrzemyslaw\Exceptions\EKPData
     * @throws \KPrzemyslaw\Exceptions\KPDBQueryHandler
     */
    public function run()
    {
        $removedId = null;
        $lineId = (int)$this->request->getData('line_id');
        if($lineId > 0) {
            $this->questionnairePageLineField->setData('id',  (int)$this->request->getData('id'));
            $this->questionnairePageLineField->setData('line_id', $lineId);
            $removedId = $this->questionnairePageLineField->remove();
        }

        return [
            'id' => $removedId
        ];
    }
}
