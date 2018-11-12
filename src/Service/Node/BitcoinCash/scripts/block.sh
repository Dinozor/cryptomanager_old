#!/bin/bash

SOURCE="${BASH_SOURCE[0]}"
CON="$( dirname $SOURCE )/../../../../../"
echo $CON $1 >> /tmp/bch_block.txt
cd $CON
php bin/console node:update bch block $1 &>> /tmp/bch_block.txt