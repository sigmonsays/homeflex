#!/bin/bash

source /etc/homeflex || exit 1

cat << EOF
theme command help

	list
			list themes

	convert1-2
			convert themes from 1.x format to 2.x format

	downloads
			create tar.gz files for each theme
EOF
