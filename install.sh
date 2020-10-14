#!/bin/bash
apt-get upgrade

apt-get update

apt-get install -y vim cron

composer install

bin/console doctrine:migration:migrate -n
