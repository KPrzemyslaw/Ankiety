<?php


namespace Bundles\Admin;

use KPrzemyslaw\DB\AKPDB;

/**
 * Class QuestionnaireAnswers
 *
 * @author      Przemysław Kotlarz <todofenn@gmail.com>
 * @package     Ankiety
 */
class QuestionnaireAnswers extends AKPDB
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
     * @param int $questionnaireId
     * @return mixed
     * @throws \KPrzemyslaw\Exceptions\EKPData
     * @throws \KPrzemyslaw\Exceptions\KPDBQueryHandler
     */
    public function getDataByQuestionnaireId(int $questionnaireId)
    {
        /** @var \PDOStatement $stmt */
        $stmt = $this->getDBResource()
            ->prepare('SELECT answers.*, lineTable.id AS tbl_line_id, lineTable.name AS tbl_line_name FROM '. $this->getTableName() .' answers'
                    .' LEFT JOIN '. QuestionnairePageLine::TABLE_NAME .' lineTable ON answers.line_id = lineTable.id'
                .' WHERE answers.questionnaire_id = :questionnaireid')
        ;
        $stmt->execute([
            'questionnaireid' => $questionnaireId
        ]);

        $this->resetData();
        $this->setData($stmt->fetchAll(\PDO::FETCH_ASSOC));

        return $this->getData();
    }
}
