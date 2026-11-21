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

# ۳) اگر دیتابیس SQLite است، فایل آن باید از قبل وجود داشته باشد (Laravel خودش نمی‌سازد)
DB_CONN="${DB_CONNECTION:-$(grep -E '^DB_CONNECTION=' "$PANEL_DIR/.env" | cut -d= -f2- || true)}"
if [ "$DB_CONN" = "sqlite" ]; then
    DB_PATH="${DB_DATABASE:-$(grep -E '^DB_DATABASE=' "$PANEL_DIR/.env" | cut -d= -f2- || true)}"
    if [ -n "$DB_PATH" ] && [ "$DB_PATH" != ":memory:" ] && [ ! -f "$DB_PATH" ]; then
        mkdir -p "$(dirname "$DB_PATH")"
        touch "$DB_PATH"
        echo "entrypoint: فایل دیتابیس SQLite ساخته شد ($DB_PATH)."
    fi
fi

# ۴) صبر تا آماده شدن دیتابیس
until php "$PANEL_DIR/artisan" migrate:status >/dev/null 2>&1; do
    echo "entrypoint: در انتظار دیتابیس..."
    sleep 2
done

# ۵) مایگریشن‌ها (بار اول می‌سازد؛ اجراهای بعدی بی‌اثر)
php "$PANEL_DIR/artisan" migrate --force

# ۶) Seeder فقط بار اول (تا با هر ری‌استارت ادمین تکراری ساخته نشود)
if [ ! -f "$PANEL_DIR/storage/app/.seeded" ]; then
    php "$PANEL_DIR/artisan" db:seed --force
    touch "$PANEL_DIR/storage/app/.seeded"
fi

# ۷) دسترسی‌های نوشتن برای www-data
chown -R www-data:www-data "$PANEL_DIR/storage" /var/www/public_html/uploads

exec "$@"
