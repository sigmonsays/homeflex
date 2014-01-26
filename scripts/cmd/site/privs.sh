#!/bin/bash

source /etc/homeflex || exit 1

chown -R ${WEB_USER}.${WEB_GROUP} $WEB_ROOT
chown ${WEB_USER}.${WEB_GROUP} /etc/homeflex.php
