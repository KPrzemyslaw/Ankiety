<?php
include_once 'KPAutoloader.php';

try {
    $reportInfos = new \KPrzemyslaw\KPController();
    $reportInfos->importSettings();
    $reportInfos->prepareAction();
    $reportInfos->run();
    $reportInfos->show();
}
catch(Exception $e) {
    echo $e->getMessage();
}

