#!/usr/bin/env bash
# راه‌اندازی کانتینر peyrocoo: آماده‌سازی .env و دیتابیس، سپس Apache
set -euo pipefail

PANEL_DIR=/var/www/panel

# ۱) ساخت .env از قالب داکر (فقط بار اول)
if [ ! -f "$PANEL_DIR/.env" ]; then
    cp "$PANEL_DIR/env.docker" "$PANEL_DIR/.env"
    echo "entrypoint: فایل .env ساخته شد."
fi

# ۲) تولید کلید اپلیکیشن اگر خالی باشد
if ! grep -q '^APP_KEY=base64' "$PANEL_DIR/.env"; then
    php "$PANEL_DIR/artisan" key:generate --force
    echo "entrypoint: APP_KEY ساخته شد."
fi

# ۳) صبر تا آماده شدن دیتابیس
until php "$PANEL_DIR/artisan" migrate:status >/dev/null 2>&1; do
    echo "entrypoint: در انتظار دیتابیس..."
    sleep 2
done

# ۴) مایگریشن‌ها (بار اول می‌سازد؛ اجراهای بعدی بی‌اثر)
php "$PANEL_DIR/artisan" migrate --force

# ۵) Seeder فقط بار اول (تا با هر ری‌استارت ادمین تکراری ساخته نشود)
if [ ! -f "$PANEL_DIR/storage/app/.seeded" ]; then
    php "$PANEL_DIR/artisan" db:seed --force
    touch "$PANEL_DIR/storage/app/.seeded"
fi

# ۶) دسترسی‌های نوشتن برای www-data
chown -R www-data:www-data "$PANEL_DIR/storage" /var/www/public_html/uploads

exec "$@"
