FROM phpswoole/swoole:5.1-php8.2

# 安装系统依赖和 CA 证书
RUN apt-get update && apt-get install -y \
    git zip unzip curl ca-certificates \
    libzip-dev libpng-dev libjpeg-dev libonig-dev libxml2-dev libssl-dev libicu-dev pkg-config \
    && docker-php-ext-install pdo pdo_mysql intl gd pcntl \
    && update-ca-certificates \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# 复制 Composer（从官方镜像中复制）
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# 设置工作目录
WORKDIR /var/www

# 复制完整源码
COPY ./source /var/www

# 复制 composer 文件（单独复制是为了利用 Docker 缓存）
COPY ./source/composer.json ./source/composer.lock* ./

# 使用国内 Composer 镜像
RUN composer config -g repo.packagist composer https://mirrors.aliyun.com/composer/

# 安装 Laravel 依赖，带容错逻辑（第一次失败自动清缓存重试）
RUN composer install -vvv --no-dev --optimize-autoloader --no-interaction || \
    (composer clear-cache && composer install --no-dev --optimize-autoloader --no-interaction)

# 开放 Octane 默认端口
EXPOSE 8848