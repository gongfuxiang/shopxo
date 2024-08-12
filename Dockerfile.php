FROM registry.freeb.vip/docker/library/php:8.3.8-apache

###打包适用的php环境
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
&& sed -i s@/deb.debian.org/@/mirrors.aliyun.com/@g /etc/apt/sources.list.d/debian.sources \
&& apt-get clean \
&& apt-get update \
&& apt-get install -y \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
        zlib1g-dev \
        libzip-dev  \
        libpq-dev \
&& docker-php-ext-configure gd --with-freetype --with-jpeg \
&& docker-php-ext-install -j$(nproc) gd mysqli zip pdo pdo_mysql\
&& rm -rf /var/lib/apt/lists/*

