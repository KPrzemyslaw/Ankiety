<?php
namespace KPrzemyslaw;

/**
 * Class KPRequest
 * @package KPrzemyslaw
 */
class KPRequest
{
    const ENCODED_TYPE_JSON = 'json';

    const REQUEST_SOURCE_POST = 'post';
    const REQUEST_SOURCE_GET = 'get';
    //const REQUEST_SOURCE_REQUEST = 'request';

    private $data = [];

    public $type = null;

    public $bundleName = null;

    public $controllerName = null;

    public $actionName = null;

    public $isAjax;
    public $isJson;

    public function __construct($source = null)
    {
        $this->bundleName = KPConfigure::MAIN_PAGE_BUNDLE;
        $this->controllerName = KPConfigure::MAIN_PAGE_BUNDLE_CONTROLLER;
        $this->actionName = KPConfigure::MAIN_PAGE_BUNDLE_CONTROLLER_ACTION;

        $this->isAjax = (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && (strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'));
        $this->isJson = isset($_SERVER['HTTP_ACCEPT']) ? (strpos(strtolower($_SERVER['HTTP_ACCEPT']), 'json') !== false) : false;

        $this->prepareRequestDataFromSource($source);
    }

    public function parseUrl()
    {
        $diff = array_diff(
            explode('/', $_SERVER['REQUEST_URI']),
            explode('/', $_SERVER['SCRIPT_NAME'])
        );

        $index = 0;
        $lastParam = null;
        foreach ($diff as $elem) {
            if($index == 0) {
                $elem = strtolower(trim($elem));
                if($this->isExistsBundleByName($elem)) {
                    $this->bundleName = ucfirst($elem);
                } else {
                    $index++;
                }
            }
            if($index == 1) {
                $this->controllerName = ucfirst($elem);
            }
            if($index == 2) {
                $this->actionName = ucfirst($elem);
            }
            if($index > 2) {
                if($index % 2) {
                    $lastParam = $elem;
                    $this->data[$lastParam] = null;
                } else {
                    $this->data[$lastParam] = $elem;
                }
            }
            $index++;
        }
    }

    /**
     * @param $name
     * @return bool
     */
    private function isExistsBundleByName($name)
    {
        $name = ucfirst($name);
        $cdir = scandir(KPConfigure::BUNDLES_DIRECOTRY);
        foreach ($cdir as $value) {
            $value = ucfirst($value);
            if(is_dir(rtrim(KPConfigure::BUNDLES_DIRECOTRY, '/').'/'.$value) && ($value == $name)) {
                return true;
            }
        }
        return false;
    }

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
     * @return array|string|null
     */
    public function getData($key = null)
    {
        if($key !== null) {
            return (isset($this->data[$key]) ? $this->data[$key] : null);
        }
        return $this->data;
    }

    /**
     * @param string    $source
     */
    private function prepareRequestDataFromSource($source)
    {
        if($source == static::REQUEST_SOURCE_POST) {
            $source = $_POST;
        }
        elseif($source == static::REQUEST_SOURCE_GET) {
            $source = $_GET;
        }
        else {
            $source = $_REQUEST;
        }

        $this->data += self::getRequestData(null, true, $source);
    }

    /**
     * @return bool
     */
    public static function isPost() {
        /* Przy wchodzeniu na strone ($_GET):
         * $_POST ==> array()
         * $_REQUEST ==> $_GET
         * $_GET ==> $_GET
         *
         * Przy wchodzeniu na strone ($_POST):
         * $_POST ==> $_POST
         * $_REQUEST ==> $_GET + $_POST
         * $_GET ==> $_GET
         */

        $requestMethod = ((isset($_SERVER['REQUEST_METHOD'])) ? strtolower($_SERVER['REQUEST_METHOD']) : null);
        if($requestMethod === 'post'){
            return true;
        }

        return ($_POST && is_array($_POST) && !empty($_POST));
    }

    /**
     * @param string|null   $key
     * @param bool          $htmlentity
     * @param array|bool    $src
     * @return bool|mixed
     */
    static public function getRequestData($key=null, $htmlentity=true, $src=false) {
        if(!is_array($src)) {
            $src = $_REQUEST;
        }

        $resultData = null;
        if($key === null) {
            $resultData = $src;
        }
        else if(array_key_exists($key, $src)) {
            $resultData = $src[$key];
        }
        else {
            $src = json_decode(file_get_contents('php://input'));
            if(is_array($src) && array_key_exists($key, $src)) {
                $resultData = $src[$key];
            }
        }

        return KPUtils::checkValue($resultData, $htmlentity);
    }
}
