#!/usr/bin/env bash

pwd > /tmp/block_dir.txt
php /../../../../../bin/console node:update ltc block $1