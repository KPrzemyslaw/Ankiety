<?php

namespace KPrzemyslaw\DB;

use KPrzemyslaw\Exceptions\KPDBQueryHandler;
use KPrzemyslaw\KPData\AKPData;

/**
 * Class AKPDB
 *
 * @author      Przemysław Kotlarz <todofenn@gmail.com>
 * @package     Ankiety
 */
abstract class AKPDB extends AKPData implements IKPDB
{
    const DB_LIST_ORDER_ASC = 'ASC';
    const DB_LIST_ORDER_DESC = 'DESC';

    /** @var \PDO $dbResource */
    private $dbResource = null;

    private $dbOrderBy = null;

    private $where = null;

    /**
     * KPDBAbstractor constructor.
     * @param \PDO $dbResource
     */
    public function __construct(\PDO $dbResource)
    {
        $this->dbResource = $dbResource;
    }

    /**
     * @return \PDO
     */
    public function getDBResource()
    {
        return $this->dbResource;
    }

    /**
     * @param string    $whereStr
     */
    public function setWhere($whereStr)
    {
        $this->where = $whereStr;
    }

    /**
     * @return string
     */
    public function getWhere()
    {
        return (string)$this->where;
    }

    /**
     * @param int $id
     * @return mixed
     * @throws KPDBQueryHandler
     * @throws \KPrzemyslaw\Exceptions\EKPData
     */
    public function getDataById(int $id)
    {
        if(empty($this->getTableName())) {
            throw new KPDBQueryHandler('Table name is empty!');
        }

        $this->resetData();

        /** @var \PDOStatement $stmt */
        $stmt = $this->getDBResource()
            ->prepare('SELECT * FROM '. $this->getTableName() .' WHERE id = :id')
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
                'SELECT * FROM '.$this->getTableName()
                .' ORDER BY id '.(in_array($oderBy, AKPDB::getOrderOptions()) ? $oderBy : AKPDB::DB_LIST_ORDER_ASC)
            )
        ;
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Update ordering for all not-ordered records
     * @param string    $tableName
     * @param int       $index
     */
    public function setOrdering($tableName, $index)
    {
        /** @var \PDOStatement $stmt */
        $stmt = $this->getDBResource()->prepare('SELECT MAX(ordering) AS max_ordering FROM '.$tableName);
        $stmt->execute();
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);

        $this->getDBResource()
            ->prepare('UPDATE '.$tableName.' SET ordering=:ordering WHERE id=:id')
            ->execute([
                'ordering' => (int)$data['max_ordering']+1,
                'id' => (int)$index
            ])
        ;
    }

    /**
     * @return bool
     */
    public function isEdit()
    {
        $index = (int)$this->getData('id');
        return ($index > 0);
    }

    /**
     * @return array
     */
    public function getOperationDataKeys()
    {
        $data = $this->getData();
        unset($data['id']);

        return array_keys($data);
    }

    /**
     * @return int
     * @throws KPDBQueryHandler
     * @throws \KPrzemyslaw\Exceptions\EKPData
     */
    public function save()
    {
        $data = $this->getOperationDataKeys();
        if(empty($data)) {
            throw new KPDBQueryHandler('You must add some data for '. ($this->isEdit() ? 'edit' : 'insert'));
        }

        $cols = [];
        $cols2 = [];
        $params = [];
        foreach ($data as $col) {
            if($this->isEdit()) {
                $cols[] = $col.'=:'.$col;
            } else {
                $cols[] = $col;
                $cols2[] = ':'.$col;
            }
            $params[$col] = $this->getData($col);
        }

        if ($this->isEdit()) {
            $query = 'UPDATE '.$this->getTableName().' SET '. implode(',', $cols) .' WHERE id=:id';
            $params['id'] = (int)$this->getData('id');
        } else {
            $query = 'INSERT INTO '.$this->getTableName().'('. implode(',', $cols) .') VALUES ('. implode(',', $cols2) .')';
        }

        try {
            $this->getDBResource()->prepare($query)->execute($params);
        } catch(\Exception $e) {
            throw KPDBQueryHandler::create($e, $query);
        }

        if (!$this->isEdit()) {
            $this->setData('id', $this->getDBResource()->lastInsertId('id'));
        }

        return (int)$this->getData('id');
    }

    /**
     * @return int
     * @throws KPDBQueryHandler
     */
    public function remove()
    {
        $index = (int)$this->getData('id');

        if($index > 0) {
            $this->getDBResource()
                ->prepare('DELETE FROM '.$this->getTableName().' WHERE id=:id')
                ->execute(['id' => $index])
            ;

            return $index;
        }

        throw new KPDBQueryHandler('For delete recort, index must be greather then 0!');
    }

    /**
     * Update ordering for all not-ordered records
     * @param string    $tableName
     * @param int       $indexFrom
     * @param int       $indexTo
     * @throws KPDBQueryHandler
     */
    public function swapOrderingOnTable($tableName, $indexFrom, $indexTo)
    {
        $indexFrom = (int)$indexFrom;
        $indexTo = (int)$indexTo;

        /** @var \PDOStatement $stmt */
        $stmt = $this->getDBResource()
            ->prepare('SELECT id, ordering FROM '.$tableName.' WHERE (id=:idf OR id=:idt)')
        ;

        $stmt->execute([
            'idf' => $indexFrom,
            'idt' => $indexTo
        ]);

        $data = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $records = [];
        foreach($data as $record) {
            $records[(int)$record['id']] = (int)$record['ordering'];
        }

        if(isset($records[$indexFrom]) && isset($records[$indexTo])) {
            $query = 'UPDATE '.$tableName.' SET ordering=:ordering WHERE id=:id';
            $this->getDBResource()
                ->prepare($query)
                ->execute([
                    'ordering' => $records[$indexTo],
                    'id' => $indexFrom
                ])
            ;
            $this->getDBResource()
                ->prepare($query)
                ->execute([
                    'ordering' => $records[$indexFrom],
                    'id' => $indexTo
                ])
            ;
        } else {
            throw new KPDBQueryHandler('No set data');
        }
    }

    /**
     * @return array
     */
    public static function getOrderOptions()
    {
        return [
            static::DB_LIST_ORDER_ASC,
            static::DB_LIST_ORDER_DESC
        ];
    }
}
