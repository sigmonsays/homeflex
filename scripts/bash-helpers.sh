#!/bin/bash

function heading {
   L=`echo -n "$@" | wc -c`
   echo "$@"
   for((i=0; i<L; i++))
   do
      echo -n "-"
   done
   echo
}

function question {
   # $1 question
   # $2 default

   echo -ne "$1" >/dev/stderr
   if [ ! -z "$2" ] ; then
      echo -ne "[$2] " >/dev/stderr
   fi
   echo -ne "? " >/dev/stderr
   read V
   if [ -z "$V" ] ; then
      V="$2"
   fi
   echo "$V"
}
