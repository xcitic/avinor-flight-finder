#!/bin/bash
#
# This is a setup script for this program


echo "## Welcome To Xcitic's World ##"

## Sjekker hvilken package manager du har
declare yarn=$(which yarn)
declare npm=$(which npm)
declare packagemanager

# Foretrekker YARN, ellers bruker NPM
if [[ -n $yarn ]]; then
  packagemanager=yarn
elif [[ -n $npm ]]; then
  packagemanager=npm
else
  echo 'Please install NPM or Yarn'
  exit 1
fi

## Installere dependencies
composer install
$packagemanager install


## Environment variabler
cp .env.example .env

## Setup database sqLite3
touch database/database.sqlite
php artisan migrate

## Salt til hashing
php artisan key:generate

## Autoload classer
composer dump-autoload

## Rebuild app.js med dev flags
$packagemanager run dev

## Serve på localhost:8000
php artisan serve
