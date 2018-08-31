#!/usr/bin/env bash

echo "Compile content of client ..."
php ./php/KPContentCompiler.php --bundle=client

echo "Compile content of admin ..."
php ./php/KPContentCompiler.php --bundle=admin

echo "Compile HTML of client ..."
php ./php/KPHtmlCompiler.php --bundle=client

echo "Compile HTML of admin ..."
php ./php/KPHtmlCompiler.php --bundle=admin

echo "Compile done."
