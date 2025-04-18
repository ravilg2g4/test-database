DOCKER_COMPOSE = docker compose
EXEC_PHP = $(DOCKER_COMPOSE) exec -T php php
COMPOSER = $(DOCKER_COMPOSE) exec -T php composer

.PHONY: setup
setup: build up composer-install
# подготовка к работе (только при первом запуске)

.PHONY: start
start: up index
# запуск программы

.PHONY: stop
stop: down
# остановка программы

.PHONY: build
build: # билдим программу
	$(DOCKER_COMPOSE) build


.PHONY: up
up: # поднимаем контейнеры
	$(DOCKER_COMPOSE) up -d


.PHONY: down
down: # роняем контейнеры
	$(DOCKER_COMPOSE) down


.PHONY: php-bash
php-bash: # прыгаем в контейнер с PHP
	$(DOCKER_COMPOSE) exec php bash


.PHONY: php-mysql
mysql-bash: # прыгаем в контейнер с MySQL
	$(DOCKER_COMPOSE) exec mysql bash


.PHONY: composer-install
composer-install: # ставим composer
	$(COMPOSER) install


.PHONY: create-database
create-database: # создаем базу данных и таблицу для пользователей
	$(EXEC_PHP) src/Migrations/createDatabase.php


.PHONY: delete-database
delete-database: # удаляем базу данных для пользователей
	$(EXEC_PHP) src/Migrations/deleteDatabase.php


.PHONY: create-show-database
create-show-database: # создаем базу данных и таблицу с одним пользователем
	$(EXEC_PHP) src/Migrations/createShowDatabase.php


.PHONY: index
index: # запуск файла index.php (основная программа)
	$(EXEC_PHP) public/index.php


.PHONY: exit
exit: # выход
	exit

