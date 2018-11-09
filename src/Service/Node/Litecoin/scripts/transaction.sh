#!/bin/bash

echo $1 >> /tmp/ltc_transactions.txt
SOURCE="${BASH_SOURCE[0]}"
php $SOURCE/../../../../../bin/console node:update ltc wallet $1