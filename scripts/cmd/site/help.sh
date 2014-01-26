#!/bin/bash

source /etc/homeflex || exit 1
cat << EOF
	Various site related commands

	mvHandler
		creates symlinks in $WEB_ROOT pointing to each file in homeflex/cat/*
		This allows pages such as /page to be used instead of /show/page

	dump-sql
		dumps create sql code for all the tables in the database

	privs
		setup privellages

EOF
