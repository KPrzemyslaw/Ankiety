#!/usr/bin/env php
<?php

function sendData(string $url, array $data)
{
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $serverOutput = curl_exec($ch);

    curl_close($ch);

    return $serverOutput;
}

$loadAverageStart = exec('cat /proc/loadavg');
$index = 0;
$live = true;
$tmStart = microtime(true);
$maxSeconds = 60.0;
while ($live) {
    sendData('http://ankiety.localhost:9080/html/client.html', [
        't' => 123
    ]);
    sendData('http://ankiety.localhost:9080/php/questionnaire', [
        't' => 123
    ]);

    $index++;
    $tmDiff = (microtime(true) - $tmStart);
    $live = ($tmDiff < $maxSeconds);
}

$loadAverageStop = exec('cat /proc/loadavg');

echo "Load Average: [$loadAverageStart] => [$loadAverageStop]\n";
echo "Counter: $index\n";
echo "$maxSeconds: $maxSeconds\n";
echo "Time diff: $tmDiff\n";
