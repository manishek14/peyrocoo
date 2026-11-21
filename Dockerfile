# ---------- مرحله ۱: نصب وابستگی‌های Composer از روی composer.lock ----------
FROM composer:2 AS vendor

WORKDIR /app
COPY panel/composer.json panel/composer.lock ./
# ignore-platform-reqs: ایمیج composer بر پایه PHP خیلی جدید است و platform check
# پکیج‌های locked قدیمی را رد می‌کند؛ runtime نهایی PHP 8.1 است (در CI boot-check شده)
# no-scripts: artisan در این stage وجود ندارد؛ manifest ها در runtime ساخته می‌شوند
RUN composer install --no-dev --no-scripts --ignore-platform-reqs --no-interaction --no-progress --prefer-dist

# ---------- مرحله ۲: ایمیج نهایی (PHP 8.1 + Apache — مطابق سرور اصلی) ----------
FROM php:8.1-apache

# mod_rewrite برای .htaccess سایت + درایور MySQL برای Laravel
RUN a2enmod rewrite \
    && docker-php-ext-install pdo_mysql

# تنظیمات Apache: docroot روی public_html + فعال‌سازی .htaccess
COPY docker/apache.conf /etc/apache2/sites-available/000-default.conf

# کد اپلیکیشن: وب‌روت و پنل کنار هم (مطابق چیدمان سرور اصلی؛
# AppServiceProvider مسیر public را به ../public_html بایند می‌کند)
COPY index.php .htaccess robots.txt favicon.ico admin-assets /var/www/public_html/
COPY 1.png 2.png 3.png 4.png lajvd2.png /var/www/public_html/
COPY ["راهنما copy.jpg", "/var/www/public_html/"]
COPY panel/ /var/www/panel/
COPY --from=vendor /app/vendor /var/www/panel/vendor
COPY docker/env.docker /var/www/panel/env.docker
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh

# پوشه آپلودها باید در وب‌روت وجود داشته و قابل نوشتن باشد
RUN mkdir -p /var/www/public_html/uploads \
    && chown -R www-data:www-data /var/www/public_html /var/www/panel/storage /var/www/panel/bootstrap/cache \
    && chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 80

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["apache2-foreground"]
