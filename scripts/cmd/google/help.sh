#!/bin/bash

source /etc/homeflex || exit 1

cat << EOF
google commands:
	update		pulls google news information and write it to XML files
	index-count	prints out googles index count and nothing more =)
EOF
