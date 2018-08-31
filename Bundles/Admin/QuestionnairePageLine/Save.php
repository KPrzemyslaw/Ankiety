<?php
/**
 * Class Save
 *
 * @author      Przemysław Kotlarz <todofenn@gmail.com>
 * @package     Ankietyv
 */


namespace Bundles\Admin\QuestionnairePageLine;

use KPrzemyslaw\Actions\KPController;

/**
 * Class AddPage
 * @package Bundles\Admin\QuestionnairePageLine
 */
class Save extends KPController
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
        $page = null;
        $pageId = (int)$this->request->getData('page_id');
        if($pageId > 0) {
            $this->questionnairePageLine->setData('id', (int)$this->request->getData('id'));
            $this->questionnairePageLine->setData('name', trim($this->request->getData('name')));
            $this->questionnairePageLine->setData('page_id', $pageId);
            $page = $this->questionnairePageLine->save();
        }

        return [
            'id' => $page
        ];
    }
}
