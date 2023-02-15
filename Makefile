args = $(filter-out $@,$(MAKECMDGOALS))

.PHONY: start

start:
	symfony serve -d
	docker-compose up -d
	yarn encore dev-server

stop:
	symfony server:stop
	docker-compose stop

test:
	symfony php ./vendor/bin/phpunit $(call args)

testmethod:
	symfony php ./vendor/bin/phpunit --filter=$(call args)

coverage:
	symfony php vendor/bin/phpunit --coverage-html public/coverage

phpstan:
	symfony php vendor/bin/phpstan analyse src --level=$(call args)

insights:
	symfony php vendor/bin/phpinsights analyse src/CenaConDelitto -v

fix:
	PHP_CS_FIXER_IGNORE_ENV=1 php ./vendor/bin/php-cs-fixer fix src/CenaConDelitto

.PHONY: start
