# Image PHP avec Apache
FROM php:8.2-apache

# Installer dépendances système + extensions PHP nécessaires
RUN apt-get update && apt-get install -y \
    git unzip libicu-dev libzip-dev zip \
    && docker-php-ext-install intl pdo pdo_mysql zip

# Activer mod_rewrite Apache
RUN a2enmod rewrite

# Installer Composer (gestionnaire PHP)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copier tout le projet dans le container
COPY . /var/www/html/

WORKDIR /var/www/html

# Donner les droits à www-data (Apache)
RUN chown -R www-data:www-data /var/www/html

# Configurer Apache pour utiliser le dossier public comme racine
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

RUN sed -ri -e 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf

# Exposer le port 80
EXPOSE 80
