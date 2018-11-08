#!/usr/bin/env bash

pwd > /tmp/zec_dir.txt
php /../../../../../bin/console node:update zec wallet $1
