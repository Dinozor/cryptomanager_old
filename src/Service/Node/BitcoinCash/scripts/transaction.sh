#!/usr/bin/env bash

pwd > /tmp/bch_dir.txt
php /../../../../../bin/console node:update bch wallet $1
