.PHONY: up install ci stan cs cs-fix test

up:
	docker compose up -d

install:
	docker exec -it calsum8 composer install

ci:
	docker exec -it calsum8 composer ci

stan:
	docker exec -it calsum8 composer phpstan

cs:
	docker exec -it calsum8 composer cs

cs-fix:
	docker exec -it calsum8 composer cs-fix

test:
	docker exec -it calsum8 composer test