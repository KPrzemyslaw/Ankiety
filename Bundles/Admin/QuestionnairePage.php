<?php


namespace Bundles\Admin;

use KPrzemyslaw\DB\AKPDB;

/**
 * Class QuestionnairePage
 *
 * @author      Przemysław Kotlarz <todofenn@gmail.com>
 * @package     Ankiety
 */
class QuestionnairePage extends AKPDB
{
    const TABLE_NAME = 'questionnaire_pages';

    /**
     * @return string
     */
    public function getTableName()
    {
        return static::TABLE_NAME;
    }

    /**
     * @param int $id
     * @param int $questionnaireId
     *
     * @return mixed
     * @throws \KPrzemyslaw\Exceptions\EKPData
     */
    public function getDataById(int $id, int $questionnaireId = 0)
    {
        $this->resetData();

        $query = 'SELECT * FROM '. $this->getTableName() .' WHERE id = :id';

        $questionnaireId = (int)$questionnaireId;
        if($questionnaireId > 0) {
            $query .= ' AND questionnaire_id = :questionnaire_id';
        }

        /** @var \PDOStatement $stmt */
        $stmt = $this->getDBResource()
            ->prepare($query)
        ;
        $stmt->execute([
            'id' => $id
        ]);

        $this->setData($stmt->fetch(\PDO::FETCH_ASSOC));

        $questionnairePageLine = new \Bundles\Admin\QuestionnairePageLine($this->getDBResource());
        $questionnairePageLine->setWhere(' page_id = '. $id);
        $this->setData('lines', $questionnairePageLine->getList());

        return $this->getData();
    }


    /**
     * @param string $oderBy
     * @param bool $withAllData
     * @return array
     */
    public function getList($oderBy = AKPDB::DB_LIST_ORDER_ASC, $withAllData = false)
    {
        /** @var \PDOStatement $stmt */
        $stmt = $this->getDBResource()
            ->prepare(
                'SELECT * FROM '. $this->getTableName()
                . ( empty($this->getWhere()) ? '' : ' WHERE '.$this->getWhere() )
                .' ORDER BY id '.(in_array($oderBy, AKPDB::getOrderOptions()) ? $oderBy : AKPDB::DB_LIST_ORDER_ASC)
            )
        ;
        $stmt->execute();

        $allData = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        if(is_array($allData) && !empty($allData)) {
            $pageLines = new QuestionnairePageLine($this->getDBResource());

            foreach($allData as $key => $page) {
                $pageLines->setWhere(' page_id = '. $page['id']);
                $allData[$key]['lines'] = $pageLines->getList(
                    AKPDB::DB_LIST_ORDER_ASC,
                    true
                );
            }
        }

        return $allData;
    }
}
