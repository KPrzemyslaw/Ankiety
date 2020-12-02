<?php

function generator(int $strlen, array $excluded = []): string
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);

    do {
        $randomString = '';
        for ($i = 0; $i < $strlen; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
    } while(in_array($randomString, $excluded));

    return $randomString;
}

function findPassword(string $password): int
{
    $index = 0;
    $passwordLen = strlen($password);
    $generatedList = [];
    do {
        $generated = generator(
            $passwordLen,
            $generatedList
        );

        $generatedList[] = $generated;
        echo "$index. $generated\n";
        $index++;
    } while($generated != $password);

    return $index;
}

$pass = 'admin123';
$step = findPassword($pass);
echo sprintf('Password [%s] finded in [%d] step', $pass, $step);

