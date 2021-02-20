FROM php:7.4-fpm
RUN apt-get update -y && apt-get install -y openssl zip unzip git
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN docker-php-ext-install pdo pdo_mysql

RUN echo $PORT

ARG JWT_SECRET
ARG MAIL_PASSWORD
ARG DB_DATABASE
ARG DB_HOST
ARG DB_PASSWORD
ARG DB_USERNAME

ENV JWT_SECRET=$JWT_SECRET
ENV MAIL_PASSWORD=$MAIL_PASSWORD
ENV DB_DATABASE=$DB_DATABASE
ENV DB_HOST=$DB_HOST
ENV DB_PASSWORD=$DB_PASSWORD
ENV DB_USERNAME=$DB_USERNAME

WORKDIR /app
COPY . /app
RUN composer install
CMD php artisan serve --host=0.0.0.0 --port=$PORT
EXPOSE $PORT