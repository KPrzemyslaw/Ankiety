<?php
/**
 * Class DataSave.php
 *
 * @author      Przemysław Kotlarz <todofenn@gmail.com>
 * @package     Ankiety
 */


namespace Bundles\Client\Questionnaire;

use Bundles\Client\Questionnaire;
use Bundles\Client\QuestionnaireAnswer;
use KPrzemyslaw\Actions\KPController;

/**
 * Class DataSave
 * @package Bundles\Client\Questionnaire
 */
class DataSave extends KPController
{
    private $questionnaireData = null;

    /**
     * @return array|void
     * @throws \KPrzemyslaw\Exceptions\EKPData
     */
    public function run()
    {
        $quest = new Questionnaire($this->dbResource);
        $this->questionnaireData = $quest->getCurrent(false);

        $answers = $this->request->getData();

        $metaData = [];
        $fields = [];
        foreach($answers as $key => $data) {
            if(!strncmp($key, '__', 2)) {
                $metaData[substr($key, 2)] = $data;
            } else {
                $fields[$key] = $data;
            }
        }

        $errors = [];
        foreach($fields as $lineId => $value) {
            $saveRequest = $this->saveAnswer($metaData, $lineId, $value);
            if($saveRequest['error']) {
                $errors[] = $lineId;
            }
        }

        return [
            'meta' => $metaData,
            'fields' => $fields,
            'errors' => $errors
        ];
    }

    /**
     * @param array         $metaData
     * @param int           $lineId
     * @param string|array  $value
     * @return array
     * @throws \KPrzemyslaw\Exceptions\EKPData
     */
    private function saveAnswer($metaData, $lineId, $value)
    {
        if(is_array($value)) {
        	$value = array_filter(array_map('trim', $value), 'strlen');
            sort($value);
            $value = implode(',', $value);
        }

        $answer = new QuestionnaireAnswer($this->dbResource);
        $answer->setData('questionnaire_id', $this->questionnaireData['id']);
        $answer->setData('session_id', session_id());
        $answer->setData('line_id', (int)$lineId);
        $answer->setData('value', $value);
        return $answer->save();
    }
}
