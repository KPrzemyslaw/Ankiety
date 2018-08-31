<?php


namespace Bundles\Client;

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
     * @param bool  $withPages
     * @return mixed
     * @throws \KPrzemyslaw\Exceptions\EKPData
     */
    public function getCurrent($withPages = true)
    {
        $this->resetData();

        /** @var \PDOStatement $stmt */
        $stmt = $this->getDBResource()
            ->prepare('SELECT * FROM '. $this->getTableName() .' WHERE current = :current')
        ;
        $stmt->execute([
            'current' => 1
        ]);

        $this->setData($stmt->fetch(\PDO::FETCH_ASSOC));

        $questionnairePage = new \Bundles\Admin\QuestionnairePage($this->getDBResource());
        $questionnairePage->setWhere(' questionnaire_id = '. $this->getData('id'));
        $this->setData('pages', $questionnairePage->getList(
            AKPDB::DB_LIST_ORDER_ASC,
            true
        ));

        return $this->getData();
    }
}
