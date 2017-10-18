SHELL := /bin/bash

.PHONY: test
test: phpunit phpmd phpcs phpda

.PHONY: phpcs
phpcs:
	./vendor/bin/php-cs-fixer fix --dry-run

.PHONY: phpmd
phpmd:
	./vendor/bin/phpmd $$((\
		find * -maxdepth 0 -not -name 'vendor' -not -name 'Tests' -type d && \
		find Tests/ -mindepth 1 -maxdepth 1 -not -name 'Fixtures' && \
		find Tests/Fixtures/ -mindepth 2 -maxdepth 2 -not -name 'cache' -not -name 'logs'\
		) | paste --delimiter , --serial) text phpmd.xml

.PHONY: phpunit
phpunit:
	php vendor/bin/simple-phpunit

.PHONY: update-test
update-test:
	composer install

/usr/local/bin/composer:
	curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

.PHONY: configure-pipelines
configure-pipelines: /usr/local/bin/composer
	apt-get update
	apt-get install --yes git postgresql-server-dev-9.4 graphviz
	docker-php-ext-install pdo_pgsql zip

.PHONY: phpda
phpda:
	./vendor/bin/phpda analyze phpda.yml

