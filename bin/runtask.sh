#!/bin/bash

cd $1
shift

if [ "$1" = "" ]; then
    echo usage runtask.sh taskid
else
    nohup php artisan task:run "$@" > /dev/null 2>&1 &
fi
