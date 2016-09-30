#!/bin/bash

echo "Adding packages"
apt-get install php-pgsql

echo "Configuring nginx"
cp -f scripts/nginx_default /etc/nginx/sites-available/default

echo "Configuring PostgreSQL"
cp scripts/db_template.sql /tmp/
chmod +r /tmp/db_template.sql
sudo -u postgres psql -f /tmp/db_template.sql

read -p "Press any key to continue... " -n1 -s

echo "Generating example table"
cd scripts
php ex1_table.php

read -p "Press any key to continue... " -n1 -s

