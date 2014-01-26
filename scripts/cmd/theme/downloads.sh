#!/bin/bash

source /etc/homeflex || exit 1

must cd "$HOME_ROOT/themes"

ls -1 > themes.txt

while read theme
do
	if [ ! -f "$theme/index.html" ] ; then
		continue;
	fi

	echo $theme
	tar zcf "download/${theme}.tar.gz" "$theme"

done < themes.txt
