<?php
/**
 * Class SwapOrdering
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
class SwapOrdering extends KPController
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

    /**
     * @throws \KPrzemyslaw\Exceptions\KPDBQueryHandler
     */
    public function run()
    {
        $this->questionnairePageLineField->swapOrdering(
            (int)$this->request->getData('field_no'),
            (int)$this->request->getData('field_no_oponent')
        );
    }
}
