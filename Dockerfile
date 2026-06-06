FROM php:8.2-apache

RUN a2enmod rewrite

WORKDIR /var/www/html

COPY . .

# sobrescreve o VirtualHost corretamente
RUN echo '<VirtualHost *:80>\n\
    DocumentRoot /var/www/html/backend/public\n\
    <Directory /var/www/html/backend/public>\n\
        AllowOverride All\n\
        Require all granted\n\
    </Directory>\n\
</VirtualHost>' > /etc/apache2/sites-available/000-default.conf

RUN chown -R www-data:www-data /var/www/html