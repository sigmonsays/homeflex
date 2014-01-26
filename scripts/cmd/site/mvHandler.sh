#!/bin/bash

source /etc/homeflex || exit 1


if [ ! -d "homeflex/cat" ] ; then
	echo "ERROR: I can't find the homeflex folder, run me next to homeflex.."
	exit 1
fi

function cleanup() {
	rm -f $TMP
	exit 0
}

trap cleanup SIGINT

TMP=`tempfile`

find homeflex/cat -maxdepth 1 -type f > $TMP

while read line
do
	base=`basename "$line"`
	name=`echo "$base" | sed "s/.cat.php//g"`

	ln -sf homeflex/mvHandler.php "${name}.php"
done < $TMP

cleanup
