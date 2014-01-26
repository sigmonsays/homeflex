#!/bin/bash

source /etc/homeflex || exit 1

cat << EOF
This script converts the config.inc to config.php file

EOF
read foo

echo "# comment me if you know what you're doing!"
echo "exit 1"

# comment me if you know what you're doing!
exit 1

must cd $HOME_ROOT/themes

IFS="
"
for i in `find -type d -maxdepth 1`
do
	name=`echo $i | awk -F / '{print $2}'`
	if [ -z $name ] ; then
		continue;
	fi

	if [ ! -f "$name/index.html" ] ; then
		continue;
	fi

	echo $name

	if [ ! -f "$name/config.inc" ] ; then
		continue;
	fi

	mv "$name/config.inc" "$name/config.php"

done
