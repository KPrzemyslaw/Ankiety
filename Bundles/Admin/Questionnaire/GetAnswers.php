<?php
/**
 * Class GetAnswers
 *
 * @author      Przemysław Kotlarz <todofenn@gmail.com>
 * @package     Ankiety
 */


namespace Bundles\Admin\Questionnaire;

use KPrzemyslaw\Actions\KPController;

/**
 * Class GetData
 * @package Bundles\Admin\Questionnaire
 */
class GetAnswers extends KPController
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

    /**
     * @return array
     * @throws \KPrzemyslaw\Exceptions\EKPData
     * @throws \KPrzemyslaw\Exceptions\KPDBQueryHandler
     */
    public function run()
    {
        $this->questionnaire->getDataById((int)$this->request->getData('id'));
        $this->questionnaire->getAnswers();

        return $this->getStatistics($this->questionnaire->getData('answers'));
    }

    /**
     * @param array $dataList
     * @return array
     */
    public function getStatistics(array $dataList)
    {
        $dataResult = [];
        $sessions = [];
        foreach($dataList as $data) {
            $lineId = (int)$data['tbl_line_id'];
            if(!isset($dataResult[$lineId])) {
                $dataResult[$lineId] = [
                    'values' => [],
                    'line' => [
                        'id' => $lineId,
                        'name' => $data['tbl_line_name']
                    ]
                ];
            }

            if(!isset($dataResult[$lineId]['values'][$data['value']])) {
                $dataResult[$lineId]['values'][$data['value']] = [
                    'counter' => 0
                ];
            }
            $dataResult[$lineId]['values'][$data['value']]['counter']++;

            if(!in_array($data['session_id'], $sessions)) {
                $sessions[] = $data['session_id'];
            }
        }

        return [
            'data' => $dataResult,
            'sessions_counter' => count($sessions),
            'questionnaire' => [
                'id' => $this->questionnaire->getData('id'),
                'name' => $this->questionnaire->getData('name'),
                'current' => $this->questionnaire->getData('current')
            ]
        ];
    }
}
