#!/bin/bash

if [ ! -d "vendor" ]; then
  echo "Run for first time, install php modules..."
  composer install
fi

php schedule.php
