#!/bin/bash

SOURCE="${BASH_SOURCE[0]}"
CON=$SOURCE/../../../../../bin/console
echo $CON $1 >> /tmp/ltc_transactions.txt
php $CON node:update ltc wallet $1 &>> /tmp/ltc_transactions.txt