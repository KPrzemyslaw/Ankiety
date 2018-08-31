<?php

namespace KPrzemyslaw;

/**
 * Class KPConfigure
 *
 * @author      Przemysław Kotlarz <todofenn@gmail.com>
 * @package     Ankiety
 */
class KPConfigure extends \KPrzemyslaw\KPData\AKPData
{
    const MAIN_PAGE_BUNDLE = 'Client';
    const MAIN_PAGE_BUNDLE_CONTROLLER = 'Main';
    const MAIN_PAGE_BUNDLE_CONTROLLER_ACTION = 'Index';

    const APP_CONTENT = __dir__.'/..';
    const BUNDLES_DIRECOTRY = __dir__.'/../Bundles';
    const ETC_DIRECOTRY = __dir__.'/../etc';

    const CONFIGURE_DEFAULT = 'configure.json';

    private $filePath = null;

    /**
     * KPConfigure constructor.
     * @param null|string $path
     */
    public function __construct(?string $path = null)
    {
        if(!empty($path)) {
            $this->setFilePath($path);
        }
    }

    /**
     * @throws Exceptions\EKPData
     */
    public function loadData()
    {
        if(file_exists($this->getFilePath())) {
            $rawData = file_get_contents($this->getFilePath());
            $this->setData(json_decode($rawData, true));
        }
    }

    /**
     * @param string    $path
     */
    public function setFilePath($path)
    {
        $this->filePath = $path;
    }

    /**
     * @return null|string
     */
    public function getFilePath()
    {
        if(!$this->filePath) {
            $this->filePath = static::ETC_DIRECOTRY.'/'.static::CONFIGURE_DEFAULT;
        }

        return $this->filePath;
    }

    /**
     * @param $paramName
     * @param string|null $path
     * @return mixed
     * @throws Exceptions\EKPData
     */
    static public function getParam($paramName, string $path = null)
    {
        $confJsonFile = new self($path);
        $confJsonFile->loadData();
        return $confJsonFile->getData($paramName);
    }

    /**
     * @param bool $developerMode
     * @return mixed|string
     * @throws Exceptions\EKPData
     */
    static public function getVersion($developerMode = false)
    {
        $version = self::getParam('version');
        if($developerMode) {
            $version .= '_'. time();
        }
        return $version;
    }
}
