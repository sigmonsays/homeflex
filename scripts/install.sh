#!/bin/bash

EDIT=$VISUAL
if [ -z "$EDIT" ] ; then
	EDIT=$EDITOR
fi

if [ -z "$EDIT" ] ; then
	EDIT=`which pico 2>/dev/null`
fi

if [ -z "$EDIT" ] ; then
	EDIT=`which nano 2>/dev/null`
fi

if [ -z "$EDIT" ] ; then
	EDIT=`which vi 2>/dev/null`
fi

if [ ! -f "config.sh.sample" ] ; then
	echo "ERROR: I can't find config.sh, Try running ./install.sh inside the scripts/ directory!"
	exit 1
fi

cat << EOF
Ok, it's time to start the install.

I am going to open up an editor on a few config files

Just read the comments and you'll survive...

Press enter to continue
EOF
read foo

if [ ! -f /etc/homeflex ] ; then
	cp config.sh.sample /etc/homeflex
fi
$EDIT /etc/homeflex

if [ ! -f /etc/homeflex.php ] ; then
	cp config.php.sample /etc/homeflex.php
fi
$EDIT /etc/homeflex.php

source /etc/homeflex
mkdir -p $WEB_ROOT $HOME_ROOT

chmod +x $HOME_ROOT/scripts/install-sql.sh
$HOME_ROOT/scripts/install-sql.sh

echo
echo "Setting up multiView Handlers and permissions..."

cp ${HOME_ROOT}/scripts/show.php ${WEB_ROOT}/show.php

$HOME_ROOT/scripts/run site mvHandler
$HOME_ROOT/scripts/run site privs

echo Installation complete

