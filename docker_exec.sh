#!/bin/bash

SERVICES="ssh php7.2-fpm nginx"

for service in $SERVICES; do
	service $service start
done

while [ 1 ]; do
	sleep 60
	for service in $SERVICES; do
		if ! service $service status | grep running; then
			echo "Service $service is dead, stopping container"
			exit
		fi
	done
done
