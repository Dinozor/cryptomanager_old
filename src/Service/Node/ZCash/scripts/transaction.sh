#!/bin/bash

SOURCE="${BASH_SOURCE[0]}"
CON="$( dirname $SOURCE )/../../../../../"
echo $CON $1 >> /tmp/zec_transactions.txt
cd $CON
php bin/console node:update zec wallet $1 &>> /tmp/zec_transactions.txt