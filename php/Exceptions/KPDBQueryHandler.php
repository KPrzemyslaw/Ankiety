<?php
namespace KPrzemyslaw\Exceptions;

/**
 * Class KPDBQueryHandler
 * @package KPrzemyslaw\Exceptions
 */
class KPDBQueryHandler extends \Exception
{
    /** @var string $query */
    protected $query = null;

    /** @var \Exception $base */
    protected $base = null;

    /**
     * @param \Exception    $base
     * @param string        $query
     * @return KPDBQueryHandler
     */
    static public function create(\Exception $base, $query = null)
    {
        $exc = new KPDBQueryHandler();
        $exc->base = $base;
        $exc->query = $query;

        return $exc;
    }

    /**
     * @return string
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * @return \Exception
     */
    public function getBase()
    {
        return $this->base;
    }
}
