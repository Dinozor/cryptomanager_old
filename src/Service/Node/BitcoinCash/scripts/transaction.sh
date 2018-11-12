#!/bin/bash

SOURCE="${BASH_SOURCE[0]}"
CON="$( dirname $SOURCE )/../../../../../"
echo $CON $1 >> /tmp/bch_transactions.txt
cd $CON
php bin/console node:update bch wallet $1 &>> /tmp/bch_transactions.txt