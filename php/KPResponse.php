<?php
namespace KPrzemyslaw;

/**
 * Class KPResponse
 * @package KPrzemyslaw
 */
class KPResponse
{
    const ENCODED_TYPE_JSON = 'json';

    private $data = [];

    public $type = 'json';

    /**
     * @param mixed $data
     */
    public function setData($data)
    {
        if (is_array($data)) {
            $this->data = array_merge($this->data, $data);
            return ;
        }

        $this->data[] = $data;
    }

    /**
     * @return array|string
     */
    public function getData()
    {
        if ($this->type == static::ENCODED_TYPE_JSON) {
            return json_encode($this->data);
        }

        return $this->data;
    }
}
