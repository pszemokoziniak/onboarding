#!/bin/bash

cp ./docker/app/config/php.ini.dist /usr/local/etc/php/php.ini
[ -f ./docker/app/config/php.ini.dist.override ] && cp ./docker/app/config/php.ini.dist.override /usr/local/etc/php/php.ini

[ -z $CI_JOB_ID ] && wait-for-it ${DB_HOST}:3306 --timeout=240 -- echo "OK"

apache2-foreground
