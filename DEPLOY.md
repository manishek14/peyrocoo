# راهنمای استقرار (Deployment)

> **وضعیت:** ایمیج Docker این پروژه در CI واقعاً build و boot شده است (`.github/workflows/ci.yml`) و کل مجموعه تست (PHPUnit + Smoke + E2E) سبز است. دستورالعمل‌های پلتفرم‌های زیر با حساب واقعی اجرا نشده‌اند (سندباکس به حساب/داشبورد این سرویس‌ها دسترسی ندارد) اما دقیقاً بر پایه همان فایل‌های Docker آماده و تأییدشده نوشته شده‌اند.

## گزینه ۱ — Railway (پیشنهادی؛ ساده‌ترین مسیر)

1. در [railway.app](https://railway.app) پروژه جدید بسازید: **New Project → Deploy from GitHub repo** → ریپو `manishek14/peyrocoo` را انتخاب کنید. Railway خودش `Dockerfile` ریشه را تشخیص می‌دهد و build می‌کند.
2. در همان پروژه **Add a Service → Database → MySQL** اضافه کنید. از تب Variables سرویس MySQL مقادیر `MYSQLHOST / MYSQLPORT / MYSQLDATABASE / MYSQLUSER / MYSQLPASSWORD` را بردارید.
3. در سرویس وب این متغیرها را ست کنید:
   - `DB_CONNECTION=mysql` ، `DB_HOST` ، `DB_PORT` ، `DB_DATABASE` ، `DB_USERNAME` ، `DB_PASSWORD` (از گام ۲)
   - `APP_URL` = دامنه Railway (مثل `https://peyrocoo.up.railway.app`)
   - `APP_ENV=production` و `APP_DEBUG=false`
   - بقیه مقادیر از قالب `docker/env.docker` استفاده می‌شود (entrypoint خودش `.env` می‌سازد)
4. برای ماندگاری آپلودها: **Service → Volumes → New Volume** با مسیر mount `/var/www/public_html/uploads`.
5. Deploy بگیرید؛ entrypoint داخل کانتینر خودش `key:generate`، `migrate` و seeder بار اول را اجرا می‌کند.
6. بعد از بالا آمدن: `https://<دامنه>/auth/admin/login` با `admin` / `password` — **بلافاصله رمز را عوض کنید.**

## گزینه ۲ — Render

فایل `render.yaml` در ریشه ریپو آماده است (Web Service داکری + دیسک برای آپلودها):

1. در [render.com](https://render.com): **New → Blueprint** و ریپو را وصل کنید؛ تعریف `render.yaml` خودکار خوانده می‌شود.
2. دیسک فقط در پلن‌های پرداختی Render پشتیبانی می‌شود؛ برای تست رایگان دیسک را حذف کنید (آپلودها موقتی می‌شوند).
3. دیتابیس: MySQL مدیریت‌شده یا یک MySQL خارجی (مثل Aiven/PlanetScale) بسازید و در متغیرهای محیطی سرویس، مقادیر `DB_*` و `APP_URL` را وارد کنید (مطابق گام‌های Railway).

## گزینه ۳ — Fly.io

```bash
fly launch                 # Dockerfile را خودکار تشخیص می‌دهد
fly volumes create uploads --size 1   # و سپس در fly.toml به /var/www/public_html/uploads وصل کنید
fly mysql create           # یا هر MySQL خارجی
fly secrets set DB_HOST=... DB_DATABASE=... DB_USERNAME=... DB_PASSWORD=... APP_URL=https://<app>.fly.dev
fly deploy
```

## گزینه ۴ — VPS (کاملاً محلی و بدون وابستگی به پلتفرم)

```bash
git clone https://github.com/manishek14/peyrocoo.git && cd peyrocoo
# پسورد دیتابیس را در docker-compose.yml و docker/env.docker عوض کنید
docker compose up -d --build
# سپس دامنه را با Nginx/Caddy روی پورت 8080 پروکسی کنید و HTTPS (certbot) اضافه کنید
```

## گزینه ۵ — استقرار بدون داکر (PHP + وب‌سرور عادی)

> این مسیر دقیقاً همان جریانی است که برای دیپلوی محلیِ تأییدشده این ریپو اجرا شد: PHP 8.1 + SQLite + وب‌سرور → کل مجموعه Smoke و E2E سبز شد. تنظیمات Apache/Nginx زیر استاندارد هستند ولی در سندباکس وب‌سرور واقعی اجرا نشده‌اند.

### پیش‌نیازها

- PHP ≥ 8.1 با اکستنشن‌ها: `mbstring`، `pdo` + `pdo_mysql` (یا `pdo_sqlite`)، `fileinfo`، `openssl`، `xml`، `curl`، `tokenizer`، `ctype`، `dom`
- Composer 2 (چون `vendor/` در ریپو track نمی‌شود و باید ساخته شود)
- MySQL 5.7+ یا فقط یک فایل SQLite؛ کاربر وب مثل `www-data`

### گام ۱ — سورس و وابستگی‌ها

```bash
git clone https://github.com/manishek14/peyrocoo.git && cd peyrocoo
cd panel
composer install --no-dev --optimize-autoloader --no-interaction
# توجه: از --classmap-authoritative استفاده نکنید؛ namespace های بخش auth این سورس
# ناسازگاری case با روت‌ها دارند و فقط PSR-4 fallback آن را حل می‌کند
```

### گام ۲ — کلید و دیتابیس

```bash
cp .env.example .env
php artisan key:generate --force
```

سپس در `.env` یکی از دو حالت:

**MySQL (مثل سرور اصلی):**
```bash
mysql -e "CREATE DATABASE peyrocoo CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
# در .env:  DB_CONNECTION=mysql  DB_HOST=127.0.0.1  DB_PORT=3306
#           DB_DATABASE=peyrocoo  DB_USERNAME=...  DB_PASSWORD=...
```

**SQLite (ساده‌ترین؛ بدون هیچ سرور دیتابیس):**
```bash
touch database/database.sqlite
# در .env:  DB_CONNECTION=sqlite  DB_DATABASE=/مسیر/مطلق/به/panel/database/database.sqlite
```

### گام ۳ — مایگریشن و داده اولیه

```bash
php artisan migrate --force
php artisan db:seed --force
```

### گام ۴ — وب‌سرور (ریشه سایت = محتوای ریشه ریپو، مثل public_html سرور اصلی)

ساختار مهم است: **ریشه ریپو = وب‌روت** (شامل `index.php`، asset ها و پوشه `uploads`) و `panel/` کنارِ آن (کد backend).

⚠️ **امنیت:** اگر وب‌روت را روی ریشه ریپو می‌گذارید، کد و `.env` پنل در معرض وب قرار می‌گیرد. فایل `.htaccess` ریشه ریپو اکنون دسترسی به `panel/`، `docker/`، `scripts/`، `tests/`، `.git*` را با پاسخ 403 می‌بندد — پس در Apache حتماً `mod_rewrite` و `AllowOverride All` فعال باشد تا این محافظ کار کند. جایگزین امن‌تر (مطابق چیدمان cPanel اصلی): `panel/` را **خارج از وب‌روت** نگه دارید و فقط محتوای وب‌روت را منتشر کنید — `index.php` با مسیر نسبی `../panel` کار می‌کند.

**Apache** (دقیقاً مثل cPanel): `DocumentRoot` روی ریشه ریپو + `mod_rewrite` + `AllowOverride All` — فایل `.htaccess` موجود بقیه کار (front controller و محافظ دایرکتوری‌ها) را می‌کند. پوشه `uploads/` در وب‌روت باید برای کاربر وب قابل نوشتن باشد.

**Nginx + php-fpm** (از `.htaccess` استفاده نمی‌کند؛ محافظ را خودتان باید داشته باشید):
```nginx
root /var/www/peyrocoo;            # ریشه ریپو
index index.php index.html;

location / {
    try_files $uri $uri/ /index.php?$query_string;
}
location ~ \.php$ {
    include fastcgi_params;
    fastcgi_pass unix:/run/php/php8.1-fpm.sock;
    fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
}
location ~ ^/(\.git|\.github|panel|docker|scripts|tests)(/|$) { deny all; }
```

**پیش‌نمایش سریع بدون وب‌سرور** (مناسب تست/توسعه، نه production):
```bash
php -S 0.0.0.0:8080 router.php     # از ریشه ریپو؛ فایل router.php موجود است
```

### گام ۵ — سخت‌سازی production (بعد از تأیید بالا آمدن سایت)

```bash
# در .env:  APP_ENV=production  APP_DEBUG=false  APP_URL=https://دامنه-واقعی
cd panel
php artisan config:cache && php artisan route:cache && php artisan view:cache
chmod -R u+rwX storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache   # با کاربر وب سیستم خود
```

بعد از بالا آمدن: `/auth/admin/login` با `admin` / `password` — **بلافاصله رمز را عوض کنید.**

## چک‌لیست production

- [ ] رمز ادمین پیش‌فرض (`admin`/`password`) بلافاصله عوض شود
- [ ] `APP_DEBUG=false` و `APP_ENV=production`
- [ ] `APP_URL` با دامنه واقعی (https) هماهنگ باشد
- [ ] volume دائمی برای `/var/www/public_html/uploads` وصل باشد (آپلودهای کاربران)
- [ ] بکاپ دوره‌ای دیتابیس و پوشه آپلودها
- [ ] در `composer install` از `--classmap-authoritative` استفاده **نکنید**؛ namespace های بخش auth این سورس ناسازگاری case با روت‌ها دارند که فقط با PSR-4 fallback کار می‌کند (در CI و همین دیپلوی محلی تأیید شده)
- [ ] `composer audit` را بررسی کنید (در زمان بررسی، ۵۳ هشدار برای پکیج‌های قدیمی Laravel 8 فعال بود)
- [ ] ایمیل: پیش‌فرض `MAIL_MAILER=log` است؛ برای ارسال واقعی SMTP را تنظیم کنید

## چرا Vercel نه؟

Vercel برای اپ‌های serverless/Node طراحی شده و PHP Laravel را به‌صورت بومی اجرا نمی‌کند. این اپ فایل‌ها را روی دیسک محلی می‌نویسد (`uploads/`) و session روی فایل است — روی Vercel هر دو می‌شکنند و نیازمند بازنویسی کد (S3 + session دیتابیسی) است. پلتفرم‌های بالا با همین Dockerfile بدون تغییر کد کار می‌کنند.
