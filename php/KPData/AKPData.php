<?php

namespace KPrzemyslaw\KPData;

use KPrzemyslaw\Exceptions\EKPData;

/**
 * Class AKPData
 *
 * @author      Przemysław Kotlarz <todofenn@gmail.com>
 * @package     Ankiety
 */
abstract class AKPData implements IKPData
{
    /** @var array $data */
    private $data = [];

    /**
     * @param mixed $key
     * @param mixed|null $value
     * @throws EKPData
     */
    public function setData($key, $value = null)
    {
        if(empty($key)) {
            return;
        }

        if(is_array($key)) {
            $this->data = array_merge($this->data, $key);
            return;
        }

        if(is_object($key)) {
            throw new EKPData('Key must be not an object!');
        }

        $this->data[$key] = $value;
    }

    /**
     * @param mixed $key
     * @param mixed $default
     * @return mixed
     */
    public function getData($key = null, $default = null)
    {
        if($key === null) {
            return $this->data;
        }
        return isset($this->data[$key]) ? $this->data[$key] : $default;
    }

    public function resetData()
    {
        unset($this->data);
        $this->data = [];
    }
}
