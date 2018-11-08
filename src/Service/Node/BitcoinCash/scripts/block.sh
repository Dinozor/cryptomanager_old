#!/usr/bin/env bash

pwd > /tmp/bch_dir.txt
php /../../../../../bin/console node:update bch block $1