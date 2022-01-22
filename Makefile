SHELL := /bin/bash

tests: export APP_ENV=test
tests:
	symfony console doctrine:database:drop --force || true
	symfony console doctrine:database:create
	symfony console doctrine:migrations:migrate -n
	symfony console doctrine:fixtures:load -n
	symfony php bin/phpunit $@
.PHONY: tests

workers-run: workers-stop
	symfony run -d --watch=config,src,templates,vendor symfony console messenger:consume async

workers-stop:
	symfony console messenger:stop-workers