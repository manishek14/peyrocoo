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
