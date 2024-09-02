#!/bin/bash

set -u  # Remove -e to handle the error manually

echo "Preparing DNS to host..."
HOST_DOMAIN="host.docker.internal"
if ! ping -q -c1 $HOST_DOMAIN > /dev/null 2>&1; then
  HOST_IP=$(ip route | awk 'NR==1 {print $3}')
  echo $HOST_IP
  echo -e "$HOST_IP\t$HOST_DOMAIN" >> /etc/hosts
  echo "Added host /etc/hosts"
else
  HOST_IP=$(grep "$HOST_DOMAIN" /etc/hosts | awk '{print $1}')
fi
echo "$HOST_IP dns setup ok"
