#!/usr/bin/env bash

for i in {1..200}
do
   echo "Start for $i"
  ./test.php >> test."$i".log 2>&1 &
done
