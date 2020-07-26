FROM php:7.4-apache

RUN apt-get update -y && \
    apt-get install -y \
    curl \
    python3-pip
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN a2enmod rewrite proxy proxy_http proxy_wstunnel headers
RUN docker-php-ext-install \
    pdo \
    pdo_mysql

RUN pip3 install \
    discord \
    python-dotenv

WORKDIR /var/www/
COPY ./src .
COPY ./compose_for_production/apache.conf /etc/apache2/sites-available/000-default.conf
RUN rmdir /var/www/html \
    && composer install \
    && chown -R www-data: /var/www

RUN apt-get autoremove -y \
    && update-ca-certificates \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

COPY ./korriban-messenger /app

CMD ["python3", "/app/korriban-messenger.py"]
