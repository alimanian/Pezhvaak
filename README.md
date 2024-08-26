# پروژه API لاراول پژواک

<details>
<summary>نسخه فارسی (کلیک کنید)</summary>

این پروژه یک API مبتنی بر Laravel برای پژواک است، یک پلتفرم رسانه اجتماعی که به کاربران امکان می‌دهد پست ایجاد کنند، نظر دهند، لایک کنند و کاربران دیگر را دنبال کنند.

## پیش‌نیازها

- PHP >= 8.2
- Composer
- MySQL (یا هر پایگاه داده‌ای که Laravel پشتیبانی می‌کند)

## نصب و راه‌اندازی

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

4. تولید کلید برنامه:
   ```
   php artisan key:generate
   ```

5. اجرای مهاجرت‌ها و سیدرها:
   ```
   php artisan migrate --seed
   ```

6. شروع سرور توسعه:
   ```
   php artisan serve
   ```

## مستندات API

### نمای کلی
- URL پایه: `http://pezhvaak.test/api`
- احراز هویت: توکن Bearer (برای اکثر نقاط پایانی مورد نیاز است)

### نقاط پایانی احراز هویت

| نقطه پایانی | متد | پارامترها | توضیحات |
|-------------|------|-----------|---------|
| `/register` | POST | `name`, `email`, `password` | ایجاد حساب کاربری جدید |
| `/login` | POST | `email`, `password` | احراز هویت و دریافت توکن API |

### نقاط پایانی کاربر

| نقطه پایانی | متد | نیاز به احراز هویت | توضیحات |
|-------------|------|---------------------|---------|
| `/v1/users/` | GET | خیر | دریافت همه کاربران |
| `/v1/users/{user_id}/posts` | GET | خیر | دریافت پست‌های کاربر |
| `/v1/users/{user_id}/comments` | GET | خیر | دریافت نظرات کاربر |
| `/v1/users/{user_id}/likes` | GET | خیر | دریافت لایک‌های کاربر |
| `/v1/users/{user_id}/followers` | GET | خیر | دریافت دنبال‌کنندگان کاربر |
| `/v1/users/{user_id}/following` | GET | خیر | دریافت کاربرانی که کاربر دنبال می‌کند |
| `/v1/users/{user_id}/follow` | POST | بله | دنبال کردن کاربر |
| `/v1/users/{user_id}/unfollow` | DELETE | بله | لغو دنبال کردن کاربر |

### نقاط پایانی پست

| نقطه پایانی | متد | نیاز به احراز هویت | پارامترها | توضیحات |
|-------------|------|---------------------|-----------|---------|
| `/v1/posts` | GET | خیر | - | دریافت همه پست‌ها |
| `/v1/posts/{post_id}` | GET | خیر | - | دریافت پست با شناسه |
| `/v1/posts` | POST | بله | `content`, `attachments[]` (اختیاری) | ایجاد پست |
| `/v1/posts/{post_id}` | PUT | بله | `content` | به‌روزرسانی پست |
| `/v1/posts/{post_id}` | DELETE | بله | - | حذف پست |

### نقاط پایانی نظر

| نقطه پایانی | متد | نیاز به احراز هویت | پارامترها | توضیحات |
|-------------|------|---------------------|-----------|---------|
| `/v1/comments` | POST | بله | `content`, `post_id` | ایجاد نظر |
| `/v1/comments/{comment_id}` | DELETE | بله | - | حذف نظر |

### نقاط پایانی لایک

| نقطه پایانی | متد | نیاز به احراز هویت | پارامترها | توضیحات |
|-------------|------|---------------------|-----------|---------|
| `/v1/likes` | POST | بله | `post_id` | ایجاد لایک |
| `/v1/likes/{like_id}` | DELETE | بله | - | حذف لایک |

### اطلاعات اضافی

1. **مدیریت خطا**: تمام نقاط پایانی پاسخ‌های JSON را برمی‌گردانند. در صورت بروز خطا، پاسخ شامل پیام خطا و کد وضعیت HTTP مناسب خواهد بود.

2. **محدودیت نرخ**: اطلاعات مربوط به محدودیت نرخ در مشخصات فعلی API ارائه نشده است. لطفاً برای جزئیات سیاست‌های محدودیت نرخ با ارائه‌دهنده API تماس بگیرید.

3. **هدرهای درخواست**:
    - برای درخواست‌های احراز هویت شده، توکن Bearer را در هدر Authorization قرار دهید.
    - برای تمام فراخوانی‌های API، `Accept: application/json` را در هدرهای درخواست تنظیم کنید.

4. **فرمت پاسخ**: تمام پاسخ‌های موفق در قالب JSON خواهند بود.

5. **صفحه‌بندی**: در صورت پیاده‌سازی، جزئیات مربوط به صفحه‌بندی (مانند اندازه صفحه، لینک‌های صفحه بعدی/قبلی) باید در پاسخ برای نقاط پایانی که چندین مورد را برمی‌گردانند، گنجانده شود.

6. **نسخه‌بندی**: نسخه فعلی API v1 است، همانطور که از مسیرهای نقطه پایانی مشخص است.

توجه: برای اطلاعات دقیق‌تر در مورد نمونه‌های درخواست/پاسخ، کدهای خطای خاص و راهنمای استفاده، لطفاً به مستندات کامل API مراجعه کنید یا با ارائه‌دهنده API تماس بگیرید.

## کالکشن Postman

برای سهولت در تست و استفاده از API، یک فایل کالکشن Postman در کنار فایل‌های پروژه قرار داده شده است. می‌توانید این فایل را در Postman وارد کرده و به راحتی API را تست کنید.

## مشارکت

لطفاً قبل از مشارکت در این پروژه با ما تماس بگیرید. شما می‌توانید با ایجاد یک issue یا ارسال یک pull request مشارکت کنید.

## مجوز

این پروژه تحت مجوز [نام مجوز] منتشر شده است. برای جزئیات بیشتر، فایل LICENSE را مشاهده کنید.

</details>

# Pezhvaak Laravel API Project

<details open>
<summary>English Version (Click to collapse)</summary>

This project is a Laravel-based API for Pezhvaak, a social media platform allowing users to create posts, comment, like, and follow other users.

## Prerequisites

- PHP >= 8.2
- Composer
- MySQL (or any database supported by Laravel)

## Installation and Setup

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

4. Generate application key:
   ```
   php artisan key:generate
   ```

5. Run migrations and seeders:
   ```
   php artisan migrate --seed
   ```

6. Start the development server:
   ```
   php artisan serve
   ```

## API Documentation

### Overview
- Base URL: `http://pezhvaak.test/api`
- Authentication: Bearer Token (required for most endpoints)

### Authentication Endpoints

| Endpoint | Method | Parameters | Description |
|----------|--------|------------|-------------|
| `/register` | POST | `name`, `email`, `password` | Create a new user account |
| `/login` | POST | `email`, `password` | Authenticate and receive API token |

### User Endpoints

| Endpoint | Method | Auth Required | Description |
|----------|--------|---------------|-------------|
| `/v1/users/` | GET | No | Get all users |
| `/v1/users/{user_id}/posts` | GET | No | Get user posts |
| `/v1/users/{user_id}/comments` | GET | No | Get user comments |
| `/v1/users/{user_id}/likes` | GET | No | Get user likes |
| `/v1/users/{user_id}/followers` | GET | No | Get user followers |
| `/v1/users/{user_id}/following` | GET | No | Get user following |
| `/v1/users/{user_id}/follow` | POST | Yes | Follow user |
| `/v1/users/{user_id}/unfollow` | DELETE | Yes | Unfollow user |

### Post Endpoints

| Endpoint | Method | Auth Required | Parameters | Description |
|----------|--------|---------------|------------|-------------|
| `/v1/posts` | GET | No | - | Get all posts |
| `/v1/posts/{post_id}` | GET | No | - | Get post by ID |
| `/v1/posts` | POST | Yes | `content`, `attachments[]` (optional) | Create post |
| `/v1/posts/{post_id}` | PUT | Yes | `content` | Update post |
| `/v1/posts/{post_id}` | DELETE | Yes | - | Delete post |

### Comment Endpoints

| Endpoint | Method | Auth Required | Parameters | Description |
|----------|--------|---------------|------------|-------------|
| `/v1/comments` | POST | Yes | `content`, `post_id` | Create comment |
| `/v1/comments/{comment_id}` | DELETE | Yes | - | Delete comment |

### Like Endpoints

| Endpoint | Method | Auth Required | Parameters | Description |
|----------|--------|---------------|------------|-------------|
| `/v1/likes` | POST | Yes | `post_id` | Create like |
| `/v1/likes/{like_id}` | DELETE | Yes | - | Delete like |

### Additional Information

1. **Error Handling**: All endpoints return JSON responses. In case of an error, the response will include an error message and appropriate HTTP status code.

2. **Rate Limiting**: Information about rate limiting is not provided in the current API specification. Please contact the API provider for details on rate limiting policies.

3. **Request Headers**:
    - For authenticated requests, include the Bearer token in the Authorization header.
    - Set `Accept: application/json` in the request headers for all API calls.

4. **Response Format**: All successful responses will be in JSON format.

5. **Pagination**: If implemented, details about pagination (e.g., page size, next/previous page links) should be included in the response for endpoints that return multiple items.

6. **Versioning**: The current API version is v1, as evident from the endpoint paths.

Note: For more detailed information about request/response examples, specific error codes, and usage guidelines, please refer to the full API documentation or contact the API provider.

## Postman Collection

For ease of testing and using the API, a Postman collection file is provided alongside the project files. You can import this file into Postman and easily test the API.

## Contributing

Please contact us before contributing to this project. You can contribute by creating an issue or submitting a pull request.

## License

This project is licensed under the [License Name]. See the LICENSE file for details.

</details>
