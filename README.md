# ecommerce

docker-compose up -d --build

docker-compose exec -u 1000 ecommerce composer install

docker-compose exec -u 1000 ecommerce bin/console d:d:c

docker-compose exec -u 1000 ecommerce bin/console d:s:c

docker-compose exec -u 1000 ecommerce bin/console d:s:u --force

docker-compose exec -u 1000 ecommerce symfony serve

site dispo sur : 127.0.0.1:8888
