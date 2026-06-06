FROM php:8.4-fpm

# সিস্টেম ডিপেন্ডেন্সি (libpq-dev PostgreSQL এর জন্য)
RUN apt-get update && apt-get install -y \
    nginx \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    supervisor \
    libpq-dev \
    && docker-php-ext-install pdo_pgsql mbstring exif pcntl bcmath gd

# কম্পোজার ইন্সটল
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# ওয়ার্কিং ডিরেক্টরি
WORKDIR /var/www

# পুরো প্রোজেক্ট কপি
COPY . .

# PHP ডিপেন্ডেন্সি ইন্সটল
RUN composer install --no-interaction --optimize-autoloader --no-dev

# Node.js & npm ইন্সটল (Vue অ্যাসেট বিল্ডের জন্য)
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && npm install \
    && npm run build

# পারমিশন ঠিক করা
RUN chown -R www-data:www-data storage bootstrap/cache
RUN chmod -R 775 storage bootstrap/cache

# এনজিনক্স কনফিগ
RUN mkdir -p /etc/nginx/conf.d
COPY .docker/nginx.conf /etc/nginx/nginx.conf

# সুপারভাইজর কনফিগ
COPY .docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# কপি স্টার্ট স্ক্রিপ্ট
COPY start.sh /usr/local/bin/start.sh
RUN chmod +x /usr/local/bin/start.sh

EXPOSE 80
CMD ["/usr/local/bin/start.sh"]