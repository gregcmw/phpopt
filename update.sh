#!/bin/bash

echo "Configuring PostgreSQL"
cp scripts/db_template.sql /tmp/
chmod +r /tmp/db_template.sql
sudo -u postgres psql -f /tmp/db_template.sql

read -p "Press any key to continue... " -n1 -s

echo "Generating example table"
cd scripts
php ex1_table.php

read -p "Press any key to continue... " -n1 -s

