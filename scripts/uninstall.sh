#!/bin/bash

source /etc/homeflex || exit 1


cat << EOF
!!!!!!!!!!!!!!!!!!!!!! WARNING !!!!!!!!!!!!!!!!!!!!!!

This is going to delete all the files in $WEB_ROOT,
drop the homeflex database and remove the config files.

Control-C out now if you don't want this!!



!!!!!!!!!!!!!!!!!!!!!! WARNING !!!!!!!!!!!!!!!!!!!!!!
EOF

read foo

echo deleteing $HOME_ROOT
rm -vrf $HOME_ROOT

echo dropping database
mysql -h ${MYSQL_HOST} -u ${MYSQL_USER} --password=${MYSQL_PASS} -e "drop database ${MYSQL_DATABASE}"

echo removing config files
rm -f /etc/homeflex /etc/homeflex.php
