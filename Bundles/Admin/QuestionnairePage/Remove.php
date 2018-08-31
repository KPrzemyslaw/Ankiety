<?php
/**
 * Class Remove
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
class Remove extends KPController
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
        $removedId = null;
        $questionnaireId = (int)$this->request->getData('questionnaire_id');
        if($questionnaireId > 0) {
            $this->questionnairePage->setData('id',  (int)$this->request->getData('id'));
            $this->questionnairePage->setData('questionnaire_id', $questionnaireId);
            $removedId = $this->questionnairePage->remove();
        }

        return [
            'id' => $removedId
        ];
    }
}
