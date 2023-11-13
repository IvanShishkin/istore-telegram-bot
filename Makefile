container="intshop-app"

up:
	vendor/bin/sail up -d

stop:
	vendor/bin/sail stop

bash:
	docker-compose exec -it ${container} bash

cs-fix:
	docker-compose exec ${container} ./vendor/bin/phpcbf --standard=PSR12 --extensions=php --ignore=app/Support/helpers.php --exclude=Generic.Files.LineLength app

cs-check:
	docker-compose exec ${container} ./vendor/bin/phpcs --standard=PSR12 --extensions=php --ignore=app/Support/helpers.php --exclude=Generic.Files.LineLength app

install:
	docker-compose exec ${container} composer install

migrate:
	vendor/bin/sail artisan migrate

rollback:
	vendor/bin/sail artisan migrate:rollback

test:
	vendor/bin/sail artisan test --coverage-text
