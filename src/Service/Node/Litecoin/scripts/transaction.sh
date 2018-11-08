#!/usr/bin/env bash

pwd > /tmp/block_dir.txt
php /../Users/1/Desktop/Work/cryptomanager/bin/console node:update ltc wallet $1
read -p "Press any key..."