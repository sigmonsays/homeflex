#!/bin/bash

source /etc/homeflex || exit 1
source $HOME_ROOT/scripts/bash-helpers.sh || exit 1

must cd $HOME_ROOT/scripts

mysql -h ${MYSQL_HOST} -u ${MYSQL_USER} --password=${MYSQL_PASS} -e "create database ${MYSQL_DATABASE}"

mysql -D ${MYSQL_DATABASE} -h ${MYSQL_HOST} -u ${MYSQL_USER} --password=${MYSQL_PASS} < homeflex.sql

echo
IFS="
"
for sql_file in `find sql/ -type f -regex '.*.sql$' -printf '%f\n'`
do
	echo "File: $sql_file"

	sql_desc=`cat sql/sql.description | egrep "^$sql_file" | awk -F : '{print $2}'`

	q=`question "Install $sql_desc (y/n)" y`
	if [ "$q" == "y" ] ; then
		echo "INFO: Installing $sql_file"
		mysql -D ${MYSQL_DATABASE} -h ${MYSQL_HOST} -u ${MYSQL_USER} --password=${MYSQL_PASS} < "sql/$sql_file"
	else
		echo "skipping $sql_file"
	fi
	echo
done

echo 
echo install-sql complete
