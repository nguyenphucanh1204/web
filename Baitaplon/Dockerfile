FROM php:8.1-apache

# ============================
# 1. Cài đặt extensions PHP cần thiết
# ============================
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    zip \
    unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        mysqli \
        gd \
        zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# ============================
# 2. Bật mod_rewrite cho .htaccess
# ============================
RUN a2enmod rewrite

# ============================
# 3. Cho phép .htaccess override (AllowOverride All)
# ============================
RUN sed -i 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf

# ============================
# 4. Copy source code vào /var/www/html/Baitaplon/
#    (Giữ nguyên path /Baitaplon/ khớp URL hardcode trong code)
# ============================
COPY . /var/www/html/Baitaplon/

# ============================
# 5. Phân quyền cho thư mục upload ảnh
# ============================
RUN chown -R www-data:www-data /var/www/html/Baitaplon/Public/Images \
    && chmod -R 755 /var/www/html/Baitaplon/Public/Images

# ============================
# 6. Cấu hình PHP phù hợp
# ============================
RUN echo "upload_max_filesize = 20M" > /usr/local/etc/php/conf.d/custom.ini \
    && echo "post_max_size = 25M" >> /usr/local/etc/php/conf.d/custom.ini \
    && echo "max_execution_time = 120" >> /usr/local/etc/php/conf.d/custom.ini \
    && echo "memory_limit = 256M" >> /usr/local/etc/php/conf.d/custom.ini

EXPOSE 80
