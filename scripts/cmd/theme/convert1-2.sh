#!/bin/bash

cat << EOF
This script converts themes from the 1.0 format to the 2.0 format.

All scripts included are now in the 2.0 format, which utilizes the template class included
with this project. You shouldn't need this unless you're using an older theme... 

EOF
read foo

echo "# comment me if you know what you're doing!"
echo "exit 1"

# comment me if you know what you're doing!
exit 1

IFS="
"
for i in `find old -type d -maxdepth 1`
do
	name=`echo $i | awk -F / '{print $2}'`
	if [ -z $name ] ; then
		continue;
	fi


	echo $name
	cp -r "old/$name" $name
	cat "old/$name/index.html" | sed 's/\%\([a-z0-9_]*\)\%/<%templ tag\(\1\) %>/gi' > "$name/index.html"


done
