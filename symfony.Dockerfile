# Using my own custom php image based on php:7.2-fpm
FROM microwave/symfony-php

COPY composer.lock composer.json /usr/src/app/

WORKDIR /usr/src/app

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    # && php -r "if (hash_file('SHA384', 'composer-setup.php') === '55d6ead61b29c7bdee5cccfb50076874187bd9f21f65d8991d46ec5cc90518f447387fb9f76ebae1fbbacf329e583e30') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" \
    && php composer-setup.php \
    && php -r "unlink('composer-setup.php');" \
    && php composer.phar install --no-dev --no-scripts \
    && mv composer.phar /usr/local/bin/composer

COPY . .

RUN mkdir var

RUN chown -R www-data:www-data var
