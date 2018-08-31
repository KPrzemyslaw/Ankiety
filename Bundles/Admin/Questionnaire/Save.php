<?php
/**
 * Class Save
 *
 * @author      Przemysław Kotlarz <todofenn@gmail.com>
 * @package     Ankiety
 */


namespace Bundles\Admin\Questionnaire;

use KPrzemyslaw\Actions\KPController;

/**
 * Class Save
 * @package Bundles\Admin\Questionnaire
 */
class Save extends KPController
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
        $this->questionnaire->setData('id', (int)$this->request->getData('id'));
        $this->questionnaire->setData('name', $this->request->getData('name'));
        $this->questionnaire->setData('current', $this->request->getData('current'));
        $quest = $this->questionnaire->save();

        return [
            'id' => $quest
        ];
    }
}
