<?php


namespace Bundles\Admin;

use KPrzemyslaw\DB\AKPDB;

/**
 * Class QuestionnairePageLine
 *
 * @author      Przemysław Kotlarz <todofenn@gmail.com>
 * @package     Ankiety
 */
class QuestionnairePageLine extends AKPDB
{
    const TABLE_NAME = 'questionnaire_page_lines';

    /**
     * @return string
     */
    public function getTableName()
    {
        return static::TABLE_NAME;
    }

    /**
     * @param int $id
     * @param int $pageId
     *
     * @return mixed
     * @throws \KPrzemyslaw\Exceptions\EKPData
     */
    public function getDataById(int $id, int $pageId = 0)
    {
        $this->resetData();

        $query = 'SELECT * FROM questionnaire_page_lines WHERE id = :id';

        $pageId = (int)$pageId;
        if($pageId > 0) {
            $query .= ' AND page_id = :page_id';
        }

        /** @var \PDOStatement $stmt */
        $stmt = $this->getDBResource()
            ->prepare($query)
        ;
        $stmt->execute([
            'id' => $id
        ]);

        $this->setData($stmt->fetch(\PDO::FETCH_ASSOC));

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
                'SELECT * FROM questionnaire_page_lines'
                . ( empty($this->getWhere()) ? '' : ' WHERE '.$this->getWhere() )
                .' ORDER BY id '.(in_array($oderBy, AKPDB::getOrderOptions()) ? $oderBy : AKPDB::DB_LIST_ORDER_ASC)
            )
        ;
        $stmt->execute();

        $allData = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        if(is_array($allData) && !empty($allData)) {
            $pageLineFields = new QuestionnairePageLineField($this->getDBResource());

            foreach($allData as $key => $line) {
                $pageLineFields->setWhere(' line_id = '. $line['id']);
                $allData[$key]['fields'] = $pageLineFields->getList(
                    AKPDB::DB_LIST_ORDER_ASC,
                    true
                );
            }
        }

        return $allData;
    }

    /**
     * @return int|void
     * @throws \KPrzemyslaw\Exceptions\KPDBQueryHandler
     */
    public function remove()
    {
        $dbFields = new QuestionnairePageLineField($this->getDBResource());
        $dbFields->removeMany($this->getData('id'));

        return parent::remove();
    }
}
