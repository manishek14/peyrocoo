#!/usr/bin/env bash
# Smoke test — بررسی سلامت صفحات و فایل‌های کلیدی روی URL داده‌شده
# مصرف:  bash scripts/smoke-test.sh [BASE_URL]     (پیش‌فرض: http://127.0.0.1:8080)
set -u

BASE="${1:-http://127.0.0.1:8080}"
PASS=0
FAIL=0

check() {
    local name="$1" path="$2" expected="$3"
    local code
    code=$(curl -s -o /dev/null -w "%{http_code}" "$BASE$path")
    if [ "$code" = "$expected" ]; then
        echo "PASS [$code] $name  ($path)"
        PASS=$((PASS + 1))
    else
        echo "FAIL [$code؛ انتظار $expected] $name  ($path)"
        FAIL=$((FAIL + 1))
    fi
}

echo "Smoke test روی $BASE"
check "فرم لاگین کاربر"              "/login"   200
check "فرم ثبت‌نام کاربر"            "/register" 200
check "فرم لاگین ادمین"              "/auth/admin/login" 200
check "داشبورد بدون لاگین ریدایرکت می‌شود"  "/" 302
check "راهنما بدون لاگین ریدایرکت می‌شود"   "/rahnama" 302
check "درخواست‌ها بدون لاگین ریدایرکت می‌شود" "/applying-for" 302
check "فایل CSS پنل (Metronic)"      "/admin-assets/css/bootstrap-select.min.css" 200
check "تصویر استاتیک سایت"           "/1.png" 200
check "robots.txt"                   "/robots.txt" 200
check "مسیر ناموجود پاسخ 404"        "/this-route-does-not-exist" 404

echo "----------------------------------------"
echo "نتیجه Smoke: $PASS موفق، $FAIL ناموفق"
[ "$FAIL" -eq 0 ]
