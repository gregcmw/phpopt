#!/bin/bash

echo "Adding packages"
apt-get install php-pgsql
apt-get install php7.0-json

echo "Configuring nginx"
cp -f scripts/nginx-default /etc/nginx/sites-available/default
service nginx restart

echo "Configuring PostgreSQL"
cp scripts/db_template.sql /tmp/
chmod +r /tmp/db_template.sql
sudo -u postgres psql -f /tmp/db_template.sql

echo "Generating example table"
php scripts/ex1_table.php

read -p "Press any key to continue... " -n1 -s

