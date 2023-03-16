#!/usr/bin/env bash

mysqlcheck -a -u${MYSQL_USER} -p${MYSQL_PASSWORD} ${MYSQL_DATABASE}
