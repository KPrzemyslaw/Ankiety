<?php


namespace Bundles\Admin;

use KPrzemyslaw\DB\AKPDB;

/**
 * Class Questionnaire
 *
 * @author      Przemysław Kotlarz <todofenn@gmail.com>
 * @package     Ankiety
 */
class Questionnaire extends AKPDB
{
    const TABLE_NAME = 'questionnaires';

    /**
     * @return string
     */
    public function getTableName()
    {
        return static::TABLE_NAME;
    }

    /**
     * @return array
     * @throws \KPrzemyslaw\Exceptions\EKPData
     */
    public function getPages()
    {
        $questionnairePage = new QuestionnairePage($this->getDBResource());
        $questionnairePage->setWhere(' questionnaire_id = '. $this->getData('id'));
        $this->setData('pages', $questionnairePage->getList());

        return $this->getData('pages');
    }

    /**
     * @return array
     * @throws \KPrzemyslaw\Exceptions\EKPData
     * @throws \KPrzemyslaw\Exceptions\KPDBQueryHandler
     */
    public function getAnswers()
    {
        $questAnsws = new QuestionnaireAnswers($this->getDBResource());
        $answers = $questAnsws->getDataByQuestionnaireId($this->getData('id'));
        $this->setData('answers', $answers);

        return $this->getData('answers');
    }
}
