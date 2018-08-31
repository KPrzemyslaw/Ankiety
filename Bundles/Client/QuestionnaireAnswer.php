<?php


namespace Bundles\Client;

use KPrzemyslaw\DB\AKPDB;

/**
 * Class QuestionnaireAnswer
 *
 * @author      Przemysław Kotlarz <todofenn@gmail.com>
 * @package     Ankiety
 */
class QuestionnaireAnswer extends AKPDB
{
    const TABLE_NAME = 'questionnaire_anwers';

    /**
     * @return string
     */
    public function getTableName()
    {
        return static::TABLE_NAME;
    }

    /**
     * @param int $index
     * @return array
     */
    public function save()
    {
        $answerExists = $this->isAnswerExists(
            (int)$this->getData('questionnaire_id'),
            $this->getData('session_id'),
            (int)$this->getData('line_id')
        );

        $index = 0;
        if(!$answerExists) {
            $query = 'INSERT INTO questionnaire_anwers(questionnaire_id, session_id, line_id, value) VALUES (:questionnaire_id, :session_id, :line_id, :value)';
            $params = [
                'questionnaire_id' => $this->getData('questionnaire_id'),
                'session_id' => $this->getData('session_id'),
                'line_id' => (int)$this->getData('line_id'),
                'value' => $this->getData('value'),
            ];

            $this->getDBResource()->prepare($query)->execute($params);
            $index = $this->getDBResource()->lastInsertId('id');
        }

        return [
            'id' => $index,
            'error' => $answerExists
        ];
    }

    /**
     * @param int       $questionnaireId
     * @param string    $sessionId
     * @param int       $lineId
     * @return bool
     */
    public function isAnswerExists($questionnaireId, $sessionId, $lineId)
    {
        /** @var \PDOStatement $stmt */
        $stmt = $this->getDBResource()
            ->prepare(
                'SELECT * FROM questionnaire_anwers WHERE questionnaire_id=:questionnaire_id AND session_id=:session_id AND line_id=:line_id'
            )
        ;
        $stmt->execute([
            'questionnaire_id' => (int)$questionnaireId,
            'session_id' => $sessionId,
            'line_id' => (int)$lineId,
        ]);

        return is_array($stmt->fetch(\PDO::FETCH_ASSOC));
    }
}
