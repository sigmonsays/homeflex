#!/bin/bash

source /etc/homeflex || exit 1


b=`basename $0 .sh`
c="$1"
if [ -z "$c" ] ; then
	c="help"
fi

if [ -x "$HOME_ROOT/scripts/cmd/$b/${c}.sh" ] ; then
	$HOME_ROOT/scripts/cmd/$b/${c}.sh
fi
