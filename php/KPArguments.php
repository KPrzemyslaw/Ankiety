<?php

namespace KPrzemyslaw;

/**
 * Class KPArguments
 * Tool for giving an arguments from command line and HTTP Request
 *
 * @author      Przemysław Kotlarz <todofenn@gmail.com>
 * @package     Ankiety
 */
class KPArguments
{
    private $data = [];

    public function __construct()
    {
        if($this->isCommandCall()) {
            $this->data = $_SERVER['argv'];

            foreach($this->data as $argv) {
                if(($pos = strpos($argv, '=')) !== false) {
                    $key = substr($argv, 2, $pos-2);
                    $value = substr($argv, $pos+1);
                    $this->data[$key] = $value;
                }
            }
        }
        else {
            $request = new KPRequest();
            $this->data = $request->getData();
        }
    }

    public function isCommandCall()
    {
        return isset($_SERVER['argv']);
    }

    /**
     * @param string|null   $key
     * @return array|mixed|null
     */
    public function getData($key=null)
    {
        if($key !== null) {
            return isset($this->data[$key]) ? $this->data[$key] : null;
        }
        return $this->data;
    }
}
