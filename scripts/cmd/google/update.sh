#!/bin/bash

source /etc/homeflex || exit 1

URLS="
http://news.google.com/news/gnmainleftnav.html
http://news.google.com/news/gnworldleftnav.html
http://news.google.com/news/gnusaleftnav.html
http://news.google.com/news/gnbusinessleftnav.html
http://news.google.com/news/gntechnologyleftnav.html
http://news.google.com/news/gnsportsleftnav.html
http://news.google.com/news/gnenterleftnav.html
http://news.google.com/news/gnhealthleftnav.html
"

for i in $URLS
do
	outfile=`basename "$i" | sed "s/\.html$//; s/leftnav$//; s/^gn//;"`
	$HOME_ROOT/scripts/cmd/google/google-news.php.sh "$i" > \
		"$HOME_ROOT/backends/arch/google/$outfile.xml"
done
