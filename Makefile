SHELL := /usr/bin/env bash

.PHONY: test
test: phpunit phpcs

.PHONY: phpcs
phpcs:
	./vendor/bin/php-cs-fixer fix --dry-run

.PHONY: phpunit
phpunit:
	./vendor/bin/phpunit

.PHONY: update-test
update-test: | composer
	rm -rf Tests/Fixtures/TestProject/cache/test/
	./composer install

composer:
	$(if $(shell which composer 2> /dev/null),\
        ln --symbolic $$(which composer) composer,\
		curl --silent --show-error https://getcomposer.org/installer | php -- --install-dir=$$(pwd) --filename=composer)
