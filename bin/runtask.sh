#!/bin/bash

cd /project/task-demo

if [ "$1" = "" ]; then
    echo usage runtask.sh taskid
else
    php artisan task:run "$@"
fi
