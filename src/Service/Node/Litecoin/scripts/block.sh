#!/bin/bash

SOURCE="${BASH_SOURCE[0]}"
CON=$SOURCE/../../../../../bin/console
echo $CON $1 >> /tmp/ltc_block.txt
php $CON node:update ltc block $1 &>> /tmp/ltc_block.txt