<?php


namespace Bundles\Admin;

use KPrzemyslaw\DB\AKPDB;

/**
 * Class QuestionnairePageLineField
 *
 * @author      Przemysław Kotlarz <todofenn@gmail.com>
 * @package     Ankiety
 */
class QuestionnairePageLineField extends AKPDB
{
    const TABLE_NAME = 'questionnaire_page_line_fields';

    /**
     * @return string
     */
    public function getTableName()
    {
        return static::TABLE_NAME;
    }

    /**
     * @param int $id
     * @param int $lineId
     *
     * @return mixed
     * @throws \KPrzemyslaw\Exceptions\EKPData
     */
    public function getDataById(int $id, int $lineId = 0)
    {
        $this->resetData();

        $query = 'SELECT * FROM '. $this->getTableName() .' WHERE id = :id';

        $lineId = (int)$lineId;
        if($lineId > 0) {
            $query .= ' AND line_id = :line_id';
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
     * @return array
     */
    public function getList($oderBy = AKPDB::DB_LIST_ORDER_ASC)
    {
        /** @var \PDOStatement $stmt */
        $stmt = $this->getDBResource()
            ->prepare(
                'SELECT * FROM questionnaire_page_line_fields'
                . ( empty($this->getWhere()) ? '' : ' WHERE '.$this->getWhere() )
                .' ORDER BY ordering '.(in_array($oderBy, AKPDB::getOrderOptions()) ? $oderBy : AKPDB::DB_LIST_ORDER_ASC)
            )
        ;
        $stmt->execute();
        $dataList = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $dataListCount = count($dataList);
        for($i = 0; $i < $dataListCount; $i++) {
            $dataList[$i]['prev'] = (isset($dataList[$i-1]) ? (int)$dataList[$i-1]['id'] : 0);
            $dataList[$i]['next'] = (isset($dataList[$i+1]) ? (int)$dataList[$i+1]['id'] : 0);
        }

        return $dataList;
    }

    /**
     * @param int           $parentId
     * @param string|null   $where
     * @param array         $bind
     * @return array
     */
    public function removeMany(int $parentId, $where = null, array $bind = [])
    {
        $bind['line_id'] = $parentId;

        if(empty($where)) {
            $where = null;
        } else {
            $where = " AND ($where)";
        }

        $this->getDBResource()
            ->prepare('DELETE FROM questionnaire_page_line_fields WHERE line_id = :line_id'. $where)
            ->execute($bind)
        ;

        return [];
    }

    /**
     * Update ordering for all not-ordered records
     * @param int       $indexFrom
     * @param int       $indexTo
     * @throws \KPrzemyslaw\Exceptions\KPDBQueryHandler
     */
    public function swapOrdering($indexFrom, $indexTo)
    {
        $this->swapOrderingOnTable('questionnaire_page_line_fields', $indexFrom, $indexTo);
    }
}
