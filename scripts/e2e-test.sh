#!/usr/bin/env bash
# E2E test — سفر کامل کاربر و ادمین روی HTTP واقعی:
#   ثبت‌نام کاربر → ورود → ثبت درخواست با عکس → ورود ادمین → ثبت پاسخ → مشاهده پاسخ → خروج
# مصرف:  bash scripts/e2e-test.sh [BASE_URL]     (پیش‌فرض: http://127.0.0.1:8080)
# توجه: نیازمند دیتابیس seed شده (ادمین admin/password) است.
set -u

BASE="${1:-http://127.0.0.1:8080}"
JAR=$(mktemp)
PASS=0
FAIL=0

ok()  { echo "PASS: $1"; PASS=$((PASS + 1)); }
bad() { echo "FAIL: $1"; FAIL=$((FAIL + 1)); }

# شماره موبایل یکتا برای هر اجرا
PHONE="09$(date +%s%N | cut -c1-9)"
TITLE="درخواست E2E $PHONE"
DESC="پاسخ ادمین در تست E2E $PHONE"

csrf_from() {
    # کوکی جلسه هم ارسال می‌شود تا صفحات محافظت‌شده ریدایرکت نشوند و توکن برگردد
    curl -s -b "$JAR" -c "$JAR" "$BASE$1" \
        | grep -oE 'name="_token" value="[^"]+"' | head -1 | sed 's/.*value="//;s/"//'
}

make_png() {
    if command -v python3 >/dev/null 2>&1 && python3 -c "import PIL" 2>/dev/null; then
        python3 -c "from PIL import Image; Image.new('RGB',(60,40),'#3366aa').save('$1')"
    else
        base64 -d > "$1" <<'PNGEOF'
iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==
PNGEOF
    fi
}

step() { echo; echo "== $1 =="; }

# ---------- ۱) ثبت‌نام کاربر ----------
step "۱) ثبت‌نام کاربر جدید ($PHONE)"
TOKEN=$(csrf_from "/register")
REQ=$(curl -s -b "$JAR" -c "$JAR" -o /dev/null -w "%{http_code} %{redirect_url}" \
    -X POST "$BASE/register" \
    --data-urlencode "_token=$TOKEN" \
    --data-urlencode "name=کاربر" \
    --data-urlencode "family=E2E" \
    --data-urlencode "phone=$PHONE" \
    --data-urlencode "code_mli=00$(date +%s%N | cut -c1-8)" \
    --data-urlencode "password=e2e-secret")
echo "$REQ" | grep -q "^302" && ok "ثبت‌نام انجام شد ($REQ)" || bad "ثبت‌نام ناموفق ($REQ)"

# ---------- ۲) ورود خودکار و داشبورد ----------
step "۲) داشبورد کاربر بعد از ثبت‌نام"
CODE=$(curl -s -b "$JAR" -o /dev/null -w "%{http_code}" "$BASE/")
[ "$CODE" = "200" ] && ok "داشبورد کاربر 200" || bad "داشبورد کاربر $CODE"

# ---------- ۳) ثبت درخواست با عکس ----------
step "۳) ثبت درخواست جدید با تصویر"
TOKEN=$(csrf_from "/create-applying")
make_png /tmp/e2e_req.png
REQ=$(curl -s -b "$JAR" -c "$JAR" -o /dev/null -w "%{http_code} %{redirect_url}" \
    -X POST "$BASE/create-applying" \
    -F "_token=$TOKEN" \
    -F "title=$TITLE" \
    -F "file=@/tmp/e2e_req.png;type=image/png")
echo "$REQ" | grep -qE "^302 .*applying-for" && ok "درخواست ثبت شد ($REQ)" || bad "ثبت درخواست ناموفق ($REQ)"

CODE=$(curl -s -b "$JAR" -o /tmp/e2e_user_list.html -w "%{http_code}" "$BASE/applying-for")
if [ "$CODE" = "200" ] && grep -q "$TITLE" /tmp/e2e_user_list.html; then
    ok "درخواست در فهرست کاربر دیده می‌شود"
else
    bad "درخواست در فهرست کاربر نیست (کد $CODE)"
fi

# ---------- ۴) ورود ادمین ----------
step "۴) ورود ادمین"
rm -f "$JAR"
TOKEN=$(csrf_from "/auth/admin/login")
REQ=$(curl -s -b "$JAR" -c "$JAR" -o /dev/null -w "%{http_code} %{redirect_url}" \
    -X POST "$BASE/auth/admin/login" \
    --data-urlencode "_token=$TOKEN" \
    --data-urlencode "username=admin" \
    --data-urlencode "password=password")
echo "$REQ" | grep -qE "^302 .*admin/dashboard" && ok "ورود ادمین ($REQ)" || bad "ورود ادمین ناموفق ($REQ)"

# ---------- ۵) یافتن درخواست و ثبت پاسخ ----------
step "۵) پاسخ ادمین به درخواست"
CODE=$(curl -s -b "$JAR" -o /tmp/e2e_admin_list.html -w "%{http_code}" "$BASE/admin/applying-for")
ID=$(grep -oE "applying-for/answer/[0-9]+" /tmp/e2e_admin_list.html | head -1 | grep -oE "[0-9]+$")
if [ "$CODE" = "200" ] && [ -n "${ID:-}" ]; then
    ok "درخواست #$ID در پنل ادمین پیدا شد"
else
    bad "درخواست در پنل ادمین پیدا نشد (کد $CODE)"
fi

make_png /tmp/e2e_ans.png
TOKEN=$(curl -s -b "$JAR" "$BASE/admin/applying-for/answer/$ID" \
    | grep -oE 'name="_token" value="[^"]+"' | head -1 | sed 's/.*value="//;s/"//')
REQ=$(curl -s -b "$JAR" -o /dev/null -w "%{http_code} %{redirect_url}" \
    -X POST "$BASE/admin/applying-for/answer/store/$ID" \
    -F "_token=$TOKEN" \
    -F "description=$DESC" \
    -F "javab=@/tmp/e2e_ans.png;type=image/png")
echo "$REQ" | grep -qE "^302" && ok "پاسخ ثبت شد ($REQ)" || bad "ثبت پاسخ ناموفق ($REQ)"

# ---------- ۶) تأیید پاسخ در پنل ادمین و سمت کاربر ----------
step "۶) تأیید نمایش پاسخ"
curl -s -b "$JAR" "$BASE/admin/applying-for" | grep -q "$TITLE" \
    && ok "درخواست در پنل ادمین دیده می‌شود" || bad "درخواست در پنل ادمین نیست"

# ورود دوباره به‌عنوان کاربر؛ توضیحات پاسخ فقط در فهرست کاربر نمایش داده می‌شود
rm -f "$JAR"
TOKEN=$(csrf_from "/login")
curl -s -b "$JAR" -c "$JAR" -o /dev/null -X POST "$BASE/login" \
    --data-urlencode "_token=$TOKEN" \
    --data-urlencode "phone=$PHONE" \
    --data-urlencode "password=e2e-secret"
curl -s -b "$JAR" "$BASE/applying-for" | grep -q "$DESC" \
    && ok "پاسخ ادمین در فهرست کاربر دیده می‌شود" || bad "پاسخ ادمین در فهرست کاربر نیست"

# ---------- ۷) خروج کاربر ----------
step "۷) خروج از حساب"
CODE=$(curl -s -b "$JAR" -o /dev/null -w "%{http_code}" "$BASE/logout")
CODE2=$(curl -s -b "$JAR" -o /dev/null -w "%{http_code}" "$BASE/")
if [ "$CODE" = "302" ] && [ "$CODE2" = "302" ]; then
    ok "خروج انجام شد و داشبورد قفل شد"
else
    bad "خروج ناموفق ($CODE سپس $CODE2)"
fi

rm -f "$JAR" /tmp/e2e_req.png /tmp/e2e_ans.png /tmp/e2e_user_list.html /tmp/e2e_admin_list.html
echo
echo "----------------------------------------"
echo "نتیجه E2E: $PASS موفق، $FAIL ناموفق"
[ "$FAIL" -eq 0 ]
