FROM php:8.1-cli-alpine

WORKDIR /src

RUN apk add --no-cache bash
RUN wget -O /bin/wait-for-it.sh https://raw.githubusercontent.com/vishnubob/wait-for-it/master/wait-for-it.sh
RUN chmod +x /bin/wait-for-it.sh
RUN docker-php-ext-install sockets pdo pdo_mysql
RUN docker-php-ext-enable pdo sockets

CMD ["php", "consumer.php"]