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

# ۴) مایگریشن با انتظار برای دیتابیس:
#    نکته: migrate:status روی دیتابیس تازه (بدون جدول migrations) با exit 1 تمام می‌شود،
#    پس مستقیماً migrate --force را retry می‌کنیم؛ روی DB دست‌نیافتنی fail می‌ماند و
#    روی دیتابیس تازه یا موجود همان کار لازم را انجام می‌دهد.
ATTEMPTS=0
until php "$PANEL_DIR/artisan" migrate --force >/tmp/migrate.log 2>&1; do
    ATTEMPTS=$((ATTEMPTS + 1))
    if [ "$ATTEMPTS" -eq 1 ] || [ $((ATTEMPTS % 15)) -eq 0 ]; then
        tail -4 /tmp/migrate.log
    fi
    if [ "$ATTEMPTS" -ge 150 ]; then
        echo "entrypoint: دیتابیس پس از ۵ دقیقه در دسترس نشد."
        tail -20 /tmp/migrate.log
        exit 1
    fi
    echo "entrypoint: در انتظار دیتابیس... (تلاش $ATTEMPTS)"
    sleep 2
done
tail -3 /tmp/migrate.log

# ۵) Seeder فقط بار اول (تا با هر ری‌استارت ادمین تکراری ساخته نشود)
if [ ! -f "$PANEL_DIR/storage/app/.seeded" ]; then
    php "$PANEL_DIR/artisan" db:seed --force
    touch "$PANEL_DIR/storage/app/.seeded"
fi

# ۶) دسترسی‌های نوشتن برای www-data
chown -R www-data:www-data "$PANEL_DIR/storage" /var/www/public_html/uploads

exec "$@"
