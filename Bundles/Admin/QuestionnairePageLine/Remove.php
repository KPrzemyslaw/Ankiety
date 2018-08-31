<?php
/**
 * Class Remove
 *
 * @author      Przemysław Kotlarz <todofenn@gmail.com>
 * @package     Ankiety
 */


namespace Bundles\Admin\QuestionnairePageLine;

use KPrzemyslaw\Actions\KPController;

/**
 * Class AddPage
 * @package Bundles\Admin\QuestionnairePageLine
 */
class Remove extends KPController
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

    /**
     * @return int|null|void
     * @throws \KPrzemyslaw\Exceptions\EKPData
     * @throws \KPrzemyslaw\Exceptions\KPDBQueryHandler
     */
    public function run()
    {
        $removedId = null;
        $questionnaireId = (int)$this->request->getData('page_id');
        if($questionnaireId > 0) {
            $this->questionnairePageLine->setData('id',  (int)$this->request->getData('id'));
            $this->questionnairePageLine->setData('page_id', $questionnaireId);
            $removedId = $this->questionnairePageLine->remove();
        }

        return [
            'id' => $removedId
        ];
    }
}
