## Initialize the application
init: composer.phar
	php composer.phar install --no-interaction --optimize-autoloader --prefer-dist

## Installs composer locally
composer.phar:
	curl -sS https://getcomposer.org/installer | php

## Test the application
test: phpcs test-spec

## Run PHP specification tests
test-spec:
	vendor/bin/phpspec run

## Run php code sniffer
phpcs:
	vendor/bin/phpcs --standard=PSR2 ./src

## Fix php syntax with code sniffer
phpcs-fix:
	vendor/bin/phpcbf --standard=PSR2 ./src