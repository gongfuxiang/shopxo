FROM registry.freeb.vip/freeb/php:apache

ENV COMPOSER_ALLOW_SUPERUSER=1;

# 初始化脚本
COPY --chown=www-data:www-data . .
RUN composer update




