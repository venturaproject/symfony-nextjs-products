ifneq (,$(wildcard .env))
    include .env
    export
endif

# Executables (local)
DOCKER_COMP = docker compose

# Executables
PHP      = $(PHP_CONT) php
COMPOSER = $(PHP_CONT) composer
SYMFONY  = $(PHP) bin/console

# Variables
PROJECT_NAME ?= deploy
PROJECT_REFERENCE ?= symfony-react-docker
APP_CONTAINER = symfony-${PROJECT_NAME}
FRONTEND_CONTAINER = react-${PROJECT_NAME}
DB_CONTAINER = db-${PROJECT_NAME}
SUPERVISOR_CONTAINER = supervisor-${PROJECT_NAME}
RABBITMQ_CONTAINER = rabbitmq-${PROJECT_NAME}

run:
	@docker-compose -f docker-compose.yml build --no-cache
	@docker-compose -f docker-compose.yml -p $(PROJECT_REFERENCE) up -d


down:
	@docker-compose down --remove-orphans

logs: 
	@$(DOCKER_COMP) logs --tail=0 --follow

sta:
	@docker exec -it $(APP_CONTAINER) php vendor/bin/phpstan analyse src --level=7

migrations:
	@docker exec -it $(APP_CONTAINER) php bin/console doctrine:migrations:migrate --no-interaction

fixtures:
	@docker exec -it $(APP_CONTAINER) php bin/console doctrine:fixtures:load --append

messenger-consume:
	@docker exec -it $(APP_CONTAINER) php bin/console doctrine:fixtures:load --append
	 
app-container:
	@docker exec -it $(APP_CONTAINER) bash

frontend-container:
	@docker exec -it $(FRONTEND_CONTAINER) bash

db-container:
	@docker exec -it $(DB_CONTAINER) bash

supervisor-container:
	@docker exec -it $(SUPERVISOR_CONTAINER) bash

rabbitmq-container:
	@docker exec -it $(RABBITMQ_CONTAINER) bash

	




