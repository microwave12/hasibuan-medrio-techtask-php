# Symfony API Today's Lunch Decision Maker

API for checking your lunch for today.

## Specifications

- Symfony Framework v4.4.3
- PHP 7.2

## Installation

Install [Git](https://git-scm.com/downloads) and [Composer](https://getcomposer.org/download/) into your local system. Then simply clone this project.
```
git clone https://github.com/microwave12/hasibuan-medrio-techtask-php.git
```

Jump inside the project directory `cd hasibuan-medrio-techtask-php` and run `composer update` to load the missing dependencies.

## How to Run & Unit Testing
This API is using hard coded date instead of `date("Y-m-d")`. Today's date for this API is `2019-03-07`

__CMD/Terminal :__  
Run & Unit Testing the application via CMD/Terminal.

###### Run :
```
php -S 127.0.0.1:8080 -t public
```
You can directly access the API on `http://127.0.0.1:8080/lunch`

###### Testing :
Will installing PHPUnit dependencies in first-run.
```
php ./bin/phpunit
```

__Docker Container :__  
Run & Unit Testing the application via Docker Container.

###### Run :
Run in detached mode.
```
docker-compose up --build -d
```
You can directly access the API on `http://<your_docker_ip_address>/lunch`

###### Testing :
Will installing PHPUnit dependencies in first-run.
```
docker-compose exec symfony php ./bin/phpunit
```

Have been tested and works well in Docker Toolbox.
