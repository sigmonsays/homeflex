#!/bin/bash

source /etc/homeflex || exit 1

function cleanup {
	rm -f $TMP
	exit 1
}

trap cleanup SIGINT

must cd $HOME_ROOT

TMP=`tempfile`
find -type f > $TMP

{
	while read l
	do
		if ! file -i $l | grep -q text ; then
			continue;
		fi

		c=`cat "$l" | wc -l | sed "s/ //g"`
		echo "$l,$c"
	done < $TMP
} > $HOME_ROOT/source-totals.txt
cleanup
