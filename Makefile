#include .env

help: 	 		## Show this help.
	@fgrep -h "##" $(MAKEFILE_LIST) | fgrep -v fgrep | sed -e 's/\\$$//' | sed -e 's/##//'

build: 		 	## Build all docker containers.
	@bash ./.docker/scripts/build.sh

up: 	 		
	@docker-compose -f ./docker-compose.yml up -d

front: 	 	
	@bash ./.docker/scripts/front.sh

key:
	@docker-compose -f ./docker-compose.yml exec -T php-fpm php artisan key:generate

ngrok: 	 	
	@bash ./.docker/scripts/ngrok.sh

url: 	 	
	@bash ./.docker/scripts/url.sh

down: 	 		## Down all docker containers.
	@docker-compose -f ./docker-compose.yml down

refresh:  		## Put down, rebuild and up all docker containers.
	@bash ./.docker/scripts/refresh.sh

in: 	        	## Show user a list of avaliable docker containers to go inside like root.
	@bash ./.docker/scripts/in.sh

in-root: 	        	## Show user a list of avaliable docker containers to go inside like root.
	@bash ./.docker/scripts/in-root.sh

logs:			## Show logs of all containers.
	@bash ./.docker/scripts/docker-log.sh

docker-clean:		## Remove all Containers, Images, Networks and Volumes.
	@bash ./.docker/scripts/docker-clean.sh

composer-install:	## Install composer in php container.
	@bash ./.docker/scripts/composer-install.sh

npm-install:	
	@bash ./.docker/scripts/npm-install.sh

composer-dump:		## Run composer dump in php container.
	@bash ./.docker/scripts/composer-dump.sh

migrate:  		## Run all migrations in php container.
	@bash ./.docker/scripts/migrate.sh

seed: 	 		## Run all seeders in php container.
	@bash ./.docker/scripts/seed.sh

refresh-db:   		## Drop all tables, re-run migrations and re-seed in php container.
	@bash ./.docker/scripts/refresh-db.sh

test:            	## Run tests suits from Codeception.
	@bash ./.docker/scripts/test.sh

rollback:   		## Execute rollback of code changes using second from the last commit
	@bash ./.k8s/scripts/rollback.sh

first-install:    	## Execute the first start of the project.
	echo "First Install"
	make build
	make up
	make composer-install
	make key
	make npm-install
	make refresh-db
	make composer-dump