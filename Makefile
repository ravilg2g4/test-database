DOCKER_COMPOSE = docker compose
EXEC_PHP = $(DOCKER_COMPOSE) exec -T php php
COMPOSER = $(DOCKER_COMPOSE) exec -T php composer

.PHONY: setup
setup: build up php-bash composer-install create-table down
# подготовка к работе (только при первом запуске)

.PHONY: start
start: up index
# запуск программы

.PHONY: stop
stop: exit down
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
php-bash: # прыгаем в контейнер с php
	$(DOCKER_COMPOSE) exec php bash


.PHONY: composer-install
composer-install: # ставим composer
	$(COMPOSER) install


.PHONY: create-table
create-table: # создаем таблицу пользователей
	$(EXEC-PHP) src/createTable.php


.PHONY: public
public: # попадаем в папку public
	cd public


.PHONY: cd
cd: # переход на папку выше
	cd ..

.PHONY: index
index: # запуск файла index.php (основная программа)
	$(EXEC_PHP) public/index.php

.PHONY: exit
exit: # выход
	exit

