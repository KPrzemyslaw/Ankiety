<?php
/**
 * @author      Przemysław Kotlarz <todofenn@gmail.com>
 * @package     Ankiety
 */
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

try {
    spl_autoload_register(function ($className) {
        $classNameCleaned = ltrim($className, "\\");

        $searchDirs = [
            "KPrzemyslaw\\" => __DIR__,
            "Bundles\\" => __DIR__.'/../Bundles'
        ];

        $filesNotExists = null;
        $classNotExists = null;
        foreach($searchDirs as $baseNamespace => $pathDir) {
            $baseNamespaceLen = strlen($baseNamespace);
            if (!strncmp($classNameCleaned, $baseNamespace, $baseNamespaceLen)) {
                $classNameCleaned = substr($classNameCleaned, $baseNamespaceLen);
            }
            $filename = $pathDir . '/' . str_replace("\\", '/', $classNameCleaned) . '.php';
            if (file_exists($filename)) {
                include_once($filename);

                $classNameParts = explode('\\', $className);
                $classNameParts = array_map('ucfirst', $classNameParts);
                $className = implode('\\', $classNameParts);

                if (class_exists($className) || interface_exists($className)) {
                    return true;
                }
                else {
                    $classNotExists = $className;
                }
            } else {
                $filesNotExists = $filename;
            }
        }

        throw new \KPrzemyslaw\Exceptions\KPClassHandler(
            ($classNotExists ? "Class [$classNotExists] doesn't exists!" : ($filesNotExists ? "File [$filesNotExists] doesn't exists!" : '???'))

        );
    });
}
catch(Exception $e) {
    echo $e->getMessage();
}

