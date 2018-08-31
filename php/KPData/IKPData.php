<?php
namespace KPrzemyslaw\KPData;


/**
 * Interface IKPData
 *
 * @author      Przemysław Kotlarz <todofenn@gmail.com>
 * @package     Ankiety
 */

interface IKPData
{
    /**
     * @param mixed         $key
     * @param mixed|null    $value
     * @throws EKPData
     */
    public function setData($key, $value = null);

    /**
     * @param mixed $key
     * @param mixed $default
     * @return mixed
     */
    public function getData($key = null, $default = null);

    public function resetData();
}
