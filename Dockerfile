FROM phpswoole/swoole:5.1-php8.2

# 安装系统依赖和 Composer
RUN apt-get update && apt-get install -y \
    git zip unzip libzip-dev libpng-dev libjpeg-dev libonig-dev libxml2-dev libssl-dev libicu-dev pkg-config \
    && docker-php-ext-install pdo pdo_mysql intl gd pcntl \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# 复制 Composer（从官方镜像中复制）
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# 设置工作目录
WORKDIR /var/www

# 复制 Laravel 代码
COPY ./source /var/www

# 设置国内 Composer 镜像
RUN composer config -g repo.packagist composer https://mirrors.aliyun.com/composer/

# 安装 Laravel 依赖
RUN composer install --no-dev --optimize-autoloader

# 开放 Octane 默认端口
EXPOSE 8840 8848
