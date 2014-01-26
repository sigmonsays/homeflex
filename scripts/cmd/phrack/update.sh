#!/bin/bash

source /etc/homeflex || exit 1

function cleanup {
	echo "INFO: Cleaning up tmp data..."
	rm -f $TMP
	echo "INFO: Exiting..."
	exit 1
}

cat << EOF
This script will update the phrack archive section from phrack.org

Press enter to continue, otherwise Control-C out now!!!

EOF

trap cleanup SIGINT

read foo


must cd $HOME_ROOT/phracks

TMP=`tempfile`

echo "downloading a list of available phracks.."
curl http://www.phrack.org/archives/ > $TMP 2>/dev/null

if [ ! -d "tar" ] ; then
	mkdir tar
	touch tar/.skip tar/index.php
fi
echo "processing.."
# download files
while read line
do

	file=`echo "$line" | awk -F '"' '{print $6}'`

	if [ -z "$file" ] ; then
		continue;
	fi

	if ! echo "$file" | grep -q tar.gz$ ; then
		echo "skipping $file.."
		continue;
	fi


	if [ ! -f "tar/$file" ] ; then
		echo
		echo "downloading $file.."
		curl "http://www.phrack.org/archives/$file" > "tar/$file"
	fi
done < $TMP

# process downloaded files
NEW=0
ls -1 tar > $TMP
while read file
do
	if ! echo "$file" | grep -q tar.gz$ ; then
		echo "skipping non tar.gz file ($file)..."
		continue;
	fi

	dirname=`tar tzf "tar/$file" | head -n1 | awk -F / '{print $1}'`

	if [ ! -d "$dirname" ] ; then
		echo "Untar $dirname $file"
		tar zxf "tar/$file"
		echo $((NEW++)) >/dev/null
	fi
done < $TMP

echo "INFO: $NEW phracks added"
cleanup
