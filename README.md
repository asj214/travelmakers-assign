## 과제

### 개발 환경
- php 8.2
- laravel 9x
- sqlite

### url
- swagger: http://127.0.0.1:8000/api/documentation

### 초기 설정

```sh
cd travelmakers-assign

# sqlite 파일 생성
touch database/database.sqlite

# 마이그레이션
php artisan migrate

# 어드민 계정 생성
php artisan db:seed --class=UserSeeder

# 호텔 생성
php artisan db:seed --class=HotelTableSeeder

# 실행
php artisan serve
```