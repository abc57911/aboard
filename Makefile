#!/usr/bin/make -f

check: update
	find src -name '*.php' -exec php -l {} \;
	find test -name '*.php' -exec php -l {} \;
	find src -name '*.php' -exec vendor/instaclick/php-code-sniffer/scripts/phpcs --standard=PSR2 {} \;
	find test -name '*.php' -exec vendor/instaclick/php-code-sniffer/scripts/phpcs --standard=PSR2 {} \;

composer.phar:
	curl -sS https://getcomposer.org/installer | php

update: composer.phar
	./composer.phar install

update-dep: composer.phar
	./composer.phar selfupdate
	./composer.phar update

pux:
	vendor/corneltek/pux/pux compile -o route/compiled.php route/mux.php

test: check
	rm -fr test/test_files
	mkdir test/test_files
	cp test/files/test.jpg test/test_files/
	vendor/phpunit/phpunit/phpunit.php -c phpunit.xml

doc:
	rm -fr docs/html/*.html docs/html/*.png docs/html/*.css docs/html/*.js docs/html/search
	doxygen doxygen.conf


.PHONY: test update-dep check update docs
