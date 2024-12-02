# Utiliser une image officielle de PHP avec Apache
FROM php:8.1-apache

# Installer les extensions PHP nécessaires
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Copier les fichiers de votre application
COPY . /var/www/html/

# Configurer les permissions
RUN chown -R www-data:www-data /var/www/html

# Exposer le port 80 pour Fly.io
EXPOSE 80
