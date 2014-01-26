#!/bin/bash

source /etc/homeflex || exit 1

must cd "$HOME_ROOT/random"

$HOME_ROOT/scripts/cmd/random/get-random-data.php.sh
