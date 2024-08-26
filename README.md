.
<details>
<summary>نسخه فارسی (کلیک کنید)</summary>

# 🚀 پروژه API لاراول پژواک

این پروژه یک API مبتنی بر Laravel برای پژواک است، یک پلتفرم رسانه اجتماعی که به کاربران امکان می‌دهد پست ایجاد کنند، نظر دهند، لایک کنند و کاربران دیگر را دنبال کنند.

## 📋 پیش‌نیازها

- PHP >= 8.2
- Composer
- MySQL (یا هر پایگاه داده‌ای که Laravel پشتیبانی می‌کند)

## 🛠️ نصب و راه‌اندازی

1. کلون کردن مخزن:
   ```
   git clone https://github.com/alimanian/pezhvaak.git
   ```

2. نصب وابستگی‌ها:
   ```
   composer install
   ```

3. کپی کردن `.env.example` به `.env` و پیکربندی متغیرهای محیطی:
   ```
   cp .env.example .env
   ```

4. تنظیم اطلاعات دیتابیس در فایل `.env`:
   اطلاعات ورود به دیتابیس و نام دیتابیس را در فایل `.env` تنظیم کنید. مثال:
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=pezhvaak
   DB_USERNAME=root
   DB_PASSWORD=
   ```

5. تولید کلید برنامه:
   ```
   php artisan key:generate
   ```

6. اجرای مایگریشن‌ها و سیدرها:
   ```
   php artisan migrate --seed
   ```

7. شروع سرور توسعه:
   ```
   php artisan serve
   ```

## 📚 مستندات API

### 📌 نمای کلی
- URL پایه: `http://pezhvaak.test/api`
- احراز هویت: توکن Bearer (برای اکثر نقاط پایانی مورد نیاز است)

### 🔐 نقاط پایانی احراز هویت

| نقطه پایانی | متد | پارامترها | توضیحات |
|-------------|------|-----------|---------|
| `/register` | POST | `name`, `email`, `password` | ایجاد حساب کاربری جدید |
| `/login` | POST | `email`, `password` | احراز هویت و دریافت توکن API |

### 👤 نقاط پایانی کاربر

| نقطه پایانی | متد | نیاز به احراز هویت | پارامترها | توضیحات |
|-------------|------|---------------------|-----------|---------|
| `/v1/users/` | GET | خیر | - | دریافت همه کاربران |
| `/v1/users/{user_id}/posts` | GET | خیر | `user_id` (مسیر) | دریافت پست‌های کاربر |
| `/v1/users/{user_id}/comments` | GET | خیر | `user_id` (مسیر) | دریافت نظرات کاربر |
| `/v1/users/{user_id}/likes` | GET | خیر | `user_id` (مسیر) | دریافت لایک‌های کاربر |
| `/v1/users/{user_id}/followers` | GET | خیر | `user_id` (مسیر) | دریافت دنبال‌کنندگان کاربر |
| `/v1/users/{user_id}/following` | GET | خیر | `user_id` (مسیر) | دریافت کاربرانی که کاربر دنبال می‌کند |
| `/v1/users/{user_id}/follow` | POST | بله | `user_id` (مسیر) | دنبال کردن کاربر |
| `/v1/users/{user_id}/unfollow` | DELETE | بله | `user_id` (مسیر) | لغو دنبال کردن کاربر |

### 📝 نقاط پایانی پست

| نقطه پایانی | متد | نیاز به احراز هویت | پارامترها | توضیحات |
|-------------|------|---------------------|-----------|---------|
| `/v1/posts` | GET | خیر | - | دریافت همه پست‌ها |
| `/v1/posts/{post_id}` | GET | خیر | `post_id` (مسیر) | دریافت پست با شناسه |
| `/v1/posts` | POST | بله | `content`, `attachments[]` (اختیاری) | ایجاد پست |
| `/v1/posts/{post_id}` | PUT | بله | `post_id` (مسیر), `content` | به‌روزرسانی پست |
| `/v1/posts/{post_id}` | DELETE | بله | `post_id` (مسیر) | حذف پست |

### 💬 نقاط پایانی نظر

| نقطه پایانی | متد | نیاز به احراز هویت | پارامترها | توضیحات |
|-------------|------|---------------------|-----------|---------|
| `/v1/comments` | POST | بله | `content`, `post_id` | ایجاد نظر |
| `/v1/comments/{comment_id}` | DELETE | بله | `comment_id` (مسیر) | حذف نظر |

### ❤️ نقاط پایانی لایک

| نقطه پایانی | متد | نیاز به احراز هویت | پارامترها | توضیحات |
|-------------|------|---------------------|-----------|---------|
| `/v1/likes` | POST | بله | `post_id` | ایجاد لایک |
| `/v1/likes/{like_id}` | DELETE | بله | `like_id` (مسیر) | حذف لایک |

### ℹ️ اطلاعات اضافی

1. **مدیریت خطا**: تمام نقاط پایانی پاسخ‌های JSON را برمی‌گردانند. در صورت بروز خطا، پاسخ شامل پیام خطا و کد وضعیت HTTP مناسب خواهد بود.

2. **محدودیت نرخ**: اطلاعات مربوط به محدودیت نرخ در مشخصات فعلی API ارائه نشده است. لطفاً برای جزئیات سیاست‌های محدودیت نرخ با ارائه‌دهنده API تماس بگیرید.

3. **هدرهای درخواست**:
    - برای درخواست‌های احراز هویت شده، توکن Bearer را در هدر Authorization قرار دهید.
    - برای تمام فراخوانی‌های API، `Accept: application/json` را در هدرهای درخواست تنظیم کنید.

4. **فرمت پاسخ**: تمام پاسخ‌های موفق در قالب JSON خواهند بود.

5. **صفحه‌بندی**: در صورت پیاده‌سازی، جزئیات مربوط به صفحه‌بندی (مانند اندازه صفحه، لینک‌های صفحه بعدی/قبلی) باید در پاسخ برای نقاط پایانی که چندین مورد را برمی‌گردانند، گنجانده شود.

6. **نسخه‌بندی**: نسخه فعلی API v1 است، همانطور که از مسیرهای نقطه پایانی مشخص است.

7. **پارامترهای مسیر**: پارامترهایی که با (مسیر) مشخص شده‌اند، بخشی از مسیر URL هستند و باید هنگام ارسال درخواست‌ها با مقادیر واقعی جایگزین شوند.

توجه: برای اطلاعات دقیق‌تر در مورد نمونه‌های درخواست/پاسخ، کدهای خطای خاص و راهنمای استفاده، لطفاً به مستندات کامل API مراجعه کنید یا با ارائه‌دهنده API تماس بگیرید.

## 📮 کالکشن Postman

برای سهولت در تست و استفاده از API، یک فایل کالکشن Postman با نام `pezhvaak.postman_collection.json` در دایرکتوری اصلی پروژه قرار داده شده است. شما می‌توانید این فایل را در Postman وارد کرده و به راحتی API را تست کنید.

### راهنمای استفاده از کالکشن Postman:

1. فایل `pezhvaak.postman_collection.json` را در Postman وارد کنید.
2. در Postman، به بخش `Pezhvaak` بروید و سپس قسمت `Variables` را باز کنید.
3. مقدار `base_url` را در هر دو فیلد `INITIAL VALUE` و `CURRENT VALUE` تنظیم کنید (معمولاً `http://localhost:8000/api`).
4. یک درخواست `/login` ارسال کنید تا توکن دریافت کنید.
5. توکن دریافت شده را در بخش `Variables` در `Pezhvaak` برای متغیر `token` در هر دو فیلد `INITIAL VALUE` و `CURRENT VALUE` قرار دهید.
6. اکنون می‌توانید از API‌های مختلف استفاده کنید.


## 🤝 مشارکت

لطفاً قبل از مشارکت در این پروژه با ما تماس بگیرید. شما می‌توانید با ایجاد یک issue یا ارسال یک pull request مشارکت کنید.

## 📄 مجوز

این پروژه تحت مجوز [نام مجوز] منتشر شده است. برای جزئیات بیشتر، فایل LICENSE را مشاهده کنید.

</details>

# 🚀 Pezhvaak Laravel API Project

<details open>
<summary>English Version (Click to collapse)</summary>

This project is a Laravel-based API for Pezhvaak, a social media platform allowing users to create posts, comment, like, and follow other users.

## 📋 Prerequisites

- PHP >= 8.2
- Composer
- MySQL (or any database supported by Laravel)

## 🛠️ Installation and Setup

1. Clone the repository:
   ```
   git clone https://github.com/alimanian/pezhvaak.git
   ```

2. Install dependencies:
   ```
   composer install
   ```

3. Copy `.env.example` to `.env` and configure environment variables:
   ```
   cp .env.example .env
   ```

4. Set up database information in the `.env` file:
   Configure your database credentials and database name in the `.env` file. For example:
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=pezhvaak
   DB_USERNAME=root
   DB_PASSWORD=
   ```

5. Generate application key:
   ```
   php artisan key:generate
   ```

6. Run migrations and seeders:
   ```
   php artisan migrate --seed
   ```

7. Start the development server:
   ```
   php artisan serve
   ```

## 📚 API Documentation

### 📌 Overview
- Base URL: `http://pezhvaak.test/api`
- Authentication: Bearer Token (required for most endpoints)

### 🔐 Authentication Endpoints

| Endpoint | Method | Parameters | Description |
|----------|--------|------------|-------------|
| `/register` | POST | `name`, `email`, `password` | Create a new user account |
| `/login` | POST | `email`, `password` | Authenticate and receive API token |

### 👤 User Endpoints

| Endpoint | Method | Auth Required | Parameters | Description |
|----------|--------|---------------|------------|-------------|
| `/v1/users/` | GET | No | - | Get all users |
| `/v1/users/{user_id}/posts` | GET | No | `user_id` (path) | Get user posts |
| `/v1/users/{user_id}/comments` | GET | No | `user_id` (path) | Get user comments |
| `/v1/users/{user_id}/likes` | GET | No | `user_id` (path) | Get user likes |
| `/v1/users/{user_id}/followers` | GET | No | `user_id` (path) | Get user followers |
| `/v1/users/{user_id}/following` | GET | No | `user_id` (path) | Get user following |
| `/v1/users/{user_id}/follow` | POST | Yes | `user_id` (path) | Follow user |
| `/v1/users/{user_id}/unfollow` | DELETE | Yes | `user_id` (path) | Unfollow user |

### 📝 Post Endpoints

| Endpoint | Method | Auth Required | Parameters | Description |
|----------|--------|---------------|------------|-------------|
| `/v1/posts` | GET | No | - | Get all posts |
| `/v1/posts/{post_id}` | GET | No | `post_id` (path) | Get post by ID |
| `/v1/posts` | POST | Yes | `content`, `attachments[]` (optional) | Create post |
| `/v1/posts/{post_id}` | PUT | Yes | `post_id` (path), `content` | Update post |
| `/v1/posts/{post_id}` | DELETE | Yes | `post_id` (path) | Delete post |

### 💬 Comment Endpoints

| Endpoint | Method | Auth Required | Parameters | Description |
|----------|--------|---------------|------------|-------------|
| `/v1/comments` | POST | Yes | `content`, `post_id` | Create comment |
| `/v1/comments/{comment_id}` | DELETE | Yes | `comment_id` (path) | Delete comment |

### ❤️ Like Endpoints

| Endpoint | Method | Auth Required | Parameters | Description |
|----------|--------|---------------|------------|-------------|
| `/v1/likes` | POST | Yes | `post_id` | Create like |
| `/v1/likes/{like_id}` | DELETE | Yes | `like_id` (path) | Delete like |

### ℹ️ Additional Information

1. **Error Handling**: All endpoints return JSON responses. In case of an error, the response will include an error message and appropriate HTTP status code.

2. **Rate Limiting**: Information about rate limiting is not provided in the current API specification. Please contact the API provider for details on rate limiting policies.

3. **Request Headers**:
    - For authenticated requests, include the Bearer token in the Authorization header.
    - Set `Accept: application/json` in the request headers for all API calls.

4. **Response Format**: All successful responses will be in JSON format.

5. **Pagination**: If implemented, details about pagination (e.g., page size, next/previous page links) should be included in the response for endpoints that return multiple items.

6. **Versioning**: The current API version is v1, as evident from the endpoint paths.

7. **Path Parameters**: Parameters marked with (path) are part of the URL path

## 📮 Postman Collection

For ease of testing and using the API, a Postman collection file named `pezhvaak.postman_collection.json` is provided in the root directory of the project. You can import this file into Postman and easily test the API.

### Guide to using the Postman collection:

1. Import the `pezhvaak.postman_collection.json` file into Postman.
2. In Postman, go to the `Pezhvaak` section and then open the `Variables` tab.
3. Set the `base_url` value in both the `INITIAL VALUE` and `CURRENT VALUE` fields (typically `http://localhost:8000/api`).
4. Send a `/login` request to receive a token.
5. Place the received token in the `Variables` section under `Pezhvaak` for the `token` variable in both `INITIAL VALUE` and `CURRENT VALUE` fields.
6. You can now use the various APIs.

## 🤝 Contributing

Please contact us before contributing to this project. You can contribute by creating an issue or submitting a pull request.

## 📄 License

This project is licensed under the [License Name]. See the LICENSE file for details.

</details>
