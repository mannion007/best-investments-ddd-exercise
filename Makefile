## Initialize the application
init: composer.phar
	php composer.phar install --no-interaction --optimize-autoloader --prefer-dist

## Installs composer locally
composer.phar:
	curl -sS https://getcomposer.org/installer | php

## Test the application
test: phpcs test-spec test-behat

## Run PHP specification tests
test-spec:
	vendor/bin/phpspec run

## Run Behat tests
test-behat:
	vendor/bin/behat

## Run php code sniffer
phpcs:
	vendor/bin/phpcs --standard=PSR2 ./src

## Fix php syntax with code sniffer
phpcs-fix:
	vendor/bin/phpcbf --standard=PSR2 ./src

## Run the PHP inbuilt server
start-inbuilt-server:
	kill `ps -A | grep '[1]27.0.0.1:8888' | awk '{print $1}'` >/dev/null 2>&1
	nohup php -S 127.0.0.1:8888 -t public/ >/dev/null 2>&1 &
