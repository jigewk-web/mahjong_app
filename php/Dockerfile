FROM php:8.1-apache

# 必要パッケージ + nano追加
RUN apt update && apt install -y \
    git unzip zip nano \
    && apt clean

# PHP拡張
RUN docker-php-ext-install pdo pdo_mysql

# Composer インストール
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
    && php -r "unlink('composer-setup.php');"

# Apache設定
# DocumentRoot を Laravel の public に変更
ENV APACHE_DOCUMENT_ROOT /var/www/html/public

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/sites-available/000-default.conf

RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/apache2.conf \
    /etc/apache2/conf-available/*.conf

# mod_rewrite 有効化
RUN a2enmod rewrite

# .htaccess を有効にする
RUN echo '<Directory /var/www/html/public>\n\
    AllowOverride All\n\
</Directory>' >> /etc/apache2/apache2.conf