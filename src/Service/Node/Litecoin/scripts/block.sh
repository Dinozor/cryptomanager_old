#!/bin/bash

SOURCE="${BASH_SOURCE[0]}"
CON="$( dirname $SOURCE )/../../../../../"
echo $(date -Iseconds): $CON $1 >> /tmp/ltc_block.txt
cd $CON
php bin/console node:update ltc block $1 &>> /tmp/ltc_block.txt
