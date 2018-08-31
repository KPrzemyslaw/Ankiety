<?php

namespace KPrzemyslaw;


/**
 * Class KPFile
 *
 * @author      Przemysław Kotlarz <todofenn@gmail.com>
 * @package     Ankiety
 */
class KPFile
{
    const ALL_FILES = PHP_INT_MAX;
    const TYPE_DIR = 1;
    const TYPE_FILE = 2;

    /**
     * Getter of directories tree as flat array of dir paths
     *
     * @param string    $dirPath
     * @param string    $regExp
     * @param bool      $recursive
     * @return array
     */
    static public function getFilesList($dirPath, $regExp = null, $type = self::ALL_FILES, $recursive = false)
    {
        $list = file_exists($dirPath) ? scandir($dirPath) : [];
        $listNew = [];
        foreach($list as $dir) {
            if ($dir != '.' && $dir != '..') {
                $dirPathN = $dirPath . '/' . $dir;

                if(
                    (($type & static::TYPE_FILE) && is_file($dirPathN))
                    || (($type & static::TYPE_DIR) && is_dir($dirPathN))
                ) {
                    ($regExp == '*') ? $regExp = null : null;
                    if(!$regExp || ($regExp && preg_match('/^('.$regExp.')$/i', $dirPathN))) {
                        $listNew[] = $dirPathN;
                    }
                }
                if($recursive && is_dir($dirPathN)) {
                    $listNew = array_merge($listNew, self::getFilesList($dirPathN, $regExp, $type, $recursive));
                }
            }
        }
        $list = $listNew;

        return $list;
    }
}
