FROM registry.freeb.vip/freeb/php:8.3.8-apache

ENV COMPOSER_ALLOW_SUPERUSER=1;

# 初始化脚本
COPY --chown=www-data:www-data . .
RUN composer update




