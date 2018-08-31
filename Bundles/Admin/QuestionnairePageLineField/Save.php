<?php
/**
 * Class Save
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
class Save extends KPController
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
     * @return array|null|void
     * @throws \KPrzemyslaw\Exceptions\EKPData
     */
    public function run()
    {
        $line = null;
        $lineId = (int)$this->request->getData('line_id');
        if($lineId > 0) {
            $params = $this->request->getData('params');
            $this->questionnairePageLineField->setData('id', (int)$this->request->getData('id'));
            $this->questionnairePageLineField->setData('type', trim($this->request->getData('type')));
            $this->questionnairePageLineField->setData('name', trim($this->request->getData('name')));
            $this->questionnairePageLineField->setData('params', json_encode($params));
            $this->questionnairePageLineField->setData('line_id', $lineId);
            $line = $this->questionnairePageLineField->save();
        }

        return [
            'id' => $line
        ];
    }
}
