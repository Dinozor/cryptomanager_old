#!/bin/bash

SOURCE="${BASH_SOURCE[0]}"
CON="$( dirname $SOURCE )/../../../../../"
echo $CON $1 >> /tmp/zec_block.txt
cd $CON
php bin/console node:update zec block $1 &>> /tmp/zec_block.txt