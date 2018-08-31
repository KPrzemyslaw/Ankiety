<?php
/**
 * Class Save
 *
 * @author      Przemysław Kotlarz <todofenn@gmail.com>
 * @package     Ankiety
 */


namespace Bundles\Admin\QuestionnairePage;

use KPrzemyslaw\Actions\KPController;

/**
 * Class AddPage
 * @package Bundles\Admin\QuestionnairePage
 */
class Save extends KPController
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
        $page = null;
        $lines = [];

        $questionnaireId = (int)$this->request->getData('questionnaire_id');
        if($questionnaireId > 0) {
            $lineDescList = $this->request->getData('line_desc_list');
            $lineRequiredList = $this->request->getData('required_list');

            $dataList = [];
            if(!empty($lineDescList) || !empty($lineRequiredList)) {
                foreach ($lineDescList as $lineNo => $lineDesc) {
                    $dataList[$lineNo]['name'] = $lineDesc;
                }
                foreach ($lineRequiredList as $lineNo => $lineRequired) {
                    $dataList[$lineNo]['required'] = (int)$lineRequired;
                }
            }

            if(!empty($dataList)) {
                foreach ($dataList as $lineNo => $data) {
                    $questionnairePageLine = new \Bundles\Admin\QuestionnairePageLine($this->dbResource);
                    $questionnairePageLine->setData('id', (int)$lineNo);
                    $questionnairePageLine->setData('page_id', (int)$this->request->getData('id'));
                    $questionnairePageLine->setData($data);
                    $lines[] = $questionnairePageLine->save();
                }
            }

            $this->questionnairePage->setData('id', (int)$this->request->getData('id'));
            $this->questionnairePage->setData('name', trim($this->request->getData('name')));
            $this->questionnairePage->setData('desc_begin', trim($this->request->getData('desc_begin')));
            $this->questionnairePage->setData('desc_end', trim($this->request->getData('desc_end')));
            $this->questionnairePage->setData('questionnaire_id', $questionnaireId);
            $page = $this->questionnairePage->save();
        }

        return [
            'id' => $page,
            'lines' => $lines
        ];
    }
}
