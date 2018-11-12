#!/bin/bash

SOURCE="${BASH_SOURCE[0]}"
CON="$( dirname $SOURCE )/../../../../../"
echo $CON $1 >> /tmp/btc_transactions.txt
cd $CON
php bin/console node:update btc wallet $1 &>> /tmp/btc_transactions.txt