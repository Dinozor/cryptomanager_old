#!/bin/bash

SOURCE="${BASH_SOURCE[0]}"
CON="$( dirname $SOURCE )/../../../../../"
echo $CON $1 >> /tmp/btc_block.txt
cd $CON
php bin/console node:update btc block $1 &>> /tmp/btc_block.txt