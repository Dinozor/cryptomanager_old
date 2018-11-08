#!/usr/bin/env bash

pwd > /tmp/btc_dir.txt
php /../../../../../bin/console node:update btc block $1