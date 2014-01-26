#!/bin/bash

source /etc/homeflex || exit 1

cat << EOF
phrack commands:

	update		update your local phrack with the current data off phrack.org
	clean			remove unused tar files
EOF
