#!/bin/bash

cd /var/www/html/include

rm tgdb.php

wget https://xlx169.26269.de/ysf/download/tgdb.txt
cp /var/www/html/include/tgdb.txt /var/www/html/include/tgdb.php

sudo chown svxlink:svxlink /var/www/html/include/tgdb.txt /var/www/html/include/tgdb.php
sudo chmod 755 /var/www/html/include/tgdb.txt /var/www/html/include/tgdb.php 

rm tgdb.txt

