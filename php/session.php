<?php
header("Content-type: application/javascript");
include_once 'KPAutoloader.php';

try {
    $session = new \KPrzemyslaw\KPSession();

    $defaultData = $session->getData(null, \KPrzemyslaw\KPSession::KP_SESSION_DEFAULT);
    $userData = $session->getData(null, \KPrzemyslaw\KPSession::KP_SESSION_USER);

    echo "KP.sessionData.default = ". (empty($defaultData) ? 'null' : "JSON.parse('".json_encode($defaultData)."')") .";\n";
    echo "KP.sessionData.user = ". (empty($userData) ? 'null' : "JSON.parse('".json_encode($userData)."')") .";\n";
}
catch(Exception $e) {
    echo $e->getMessage();
}

