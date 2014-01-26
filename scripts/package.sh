#!/bin/bash

source /etc/homeflex || exit 1
source $HOME_ROOT/scripts/bash-helpers.sh || exit 1

echo This pulls the latest version of homeflex from CVS and packages it
echo Press enter to continue...
echo
read foo

rm -rf /tmp/homeflex
mkdir /tmp/homeflex

must cd /tmp/homeflex

ME=`whoami`

CVSROOT=`question "export CVSROOT=" "$CVSROOT"`

export CVSROOT

must cvs login

must cvs co homeflex

find -type d -name CVS | xargs rm -r

REL=`date +%Y-%m-%d`
must mv homeflex  homeflex-$REL
tar zcf homeflex-$REL.tar.gz homeflex-$REL
rm -rf homeflex-$REL
echo "release is at /tmp/homeflex/homeflex-$REL.tar.gz"
