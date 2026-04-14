# 🌸 Hương Hoa Xinh - Website Bán Hoa Tươi

![Laravel](https://img.shields.io/badge/Laravel-12.x-red?style=flat&logo=laravel)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5.x-blue?style=flat&logo=bootstrap)
![PHP](https://img.shields.io/badge/PHP-8.2-blue?style=flat&logo=php)
![MySQL](https://img.shields.io/badge/MySQL-8.0-orange?style=flat&logo=mysql)
![License](https://img.shields.io/badge/License-MIT-green?style=flat)
![Status](https://img.shields.io/badge/Status-In%20Development-yellow?style=flat)

Hệ thống website bán hoa tươi trực tuyến chuyên nghiệp, được xây dựng bằng **Laravel 12** với giao diện hiện đại và đầy đủ tính năng quản trị.

---

## 📖 Mục lục

- [Giới thiệu](#-giới-thiệu)
- [Tính năng chính](#-tính-năng-chính)
- [Công nghệ sử dụng](#-công-nghệ-sử-dụng)
- [Yêu cầu hệ thống](#-yêu-cầu-hệ-thống)
- [Cấu trúc thư mục](#-cấu-trúc-thư-mục-chính)
- [Hướng dẫn cài đặt](#-hướng-dẫn-cài-đặt)
- [Tài khoản mặc định](#-tài-khoản-mặc-định)
- [Trạng thái hoàn thành](#-trạng-thái-hoàn-thành)
- [Ảnh chụp màn hình](#-ảnh-chụp-màn-hình)
- [Đóng góp](#-đóng-góp)
- [Giấy phép](#-giấy-phép)

---

## ✨ Giới thiệu

**Hương Hoa Xinh** là nền tảng thương mại điện tử chuyên về hoa tươi, hỗ trợ đầy đủ quy trình từ xem sản phẩm, đặt hàng đến quản lý backend. Dự án tập trung vào trải nghiệm người dùng đẹp mắt và hệ thống quản trị mạnh mẽ.

> 💡 Dự án được phát triển nhằm mục đích học tập và thực hành xây dựng ứng dụng web thương mại điện tử hoàn chỉnh với Laravel.

---

## 🚀 Tính năng chính

### 🛍️ Frontend (Khách hàng)

| Tính năng | Mô tả |
|-----------|-------|
| Trang chủ | Banner nổi bật, sản phẩm khuyến nghị, danh mục nổi bật |
| Trang Shop | Tìm kiếm sản phẩm, lọc theo danh mục, phân trang |
| Xác thực | Đăng ký / Đăng nhập / Đăng xuất tài khoản |
| Chi tiết sản phẩm | Xem thông tin, hình ảnh, mô tả sản phẩm |
| Responsive | Giao diện tương thích mọi thiết bị (mobile, tablet, desktop) |

### 🔧 Backend (Admin)

| Tính năng | Mô tả |
|-----------|-------|
| Dashboard | Thống kê doanh thu, đơn hàng, khách hàng với biểu đồ Chart.js |
| Quản lý Sản phẩm | CRUD đầy đủ, upload ảnh, tìm kiếm, lọc theo danh mục |
| Quản lý Danh mục | Hỗ trợ danh mục cha - con (nested categories) |
| Quản lý Đơn hàng | Xem chi tiết, cập nhật trạng thái đơn hàng |
| Quản lý Khách hàng | Xem danh sách, khóa/mở tài khoản, xóa khách hàng |
| Phân quyền | Phân quyền rõ ràng giữa Admin và Khách hàng |

---

## 🛠 Công nghệ sử dụng

| Thành phần | Công nghệ |
|------------|-----------|
| Backend Framework | [Laravel 12](https://laravel.com/) |
| Template Engine | Blade |
| CSS Framework | [Bootstrap 5](https://getbootstrap.com/) |
| Icon | [Font Awesome 6](https://fontawesome.com/) |
| Database | MySQL 8.0 |
| Authentication | [Laravel Breeze](https://laravel.com/docs/starter-kits) |
| Phân quyền | Custom Role (`role` column) |
| Upload ảnh | Laravel Storage |
| Biểu đồ | [Chart.js](https://www.chartjs.org/) |
| Ngôn ngữ | PHP 8.2 |

---

## ⚙️ Yêu cầu hệ thống

Trước khi cài đặt, hãy đảm bảo máy tính của bạn đáp ứng các yêu cầu sau:

- **PHP** >= 8.2
- **Composer** >= 2.x
- **Node.js** >= 18.x & **NPM** >= 9.x
- **MySQL** >= 8.0
- **Git**

---

## 📁 Cấu trúc thư mục chính

```
project-ban-hoa/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/           # Controller quản trị (Dashboard, Product, Order...)
│   │   │   └── Frontend/        # Controller khách hàng (Home, Shop, Auth...)
│   │   └── Middleware/          # Middleware phân quyền
│   └── Models/                  # Eloquent Models (Product, Order, User, Category...)
├── resources/
│   ├── views/
│   │   ├── admin/               # Giao diện Admin
│   │   │   ├── dashboard/
│   │   │   ├── products/
│   │   │   ├── orders/
│   │   │   ├── categories/
│   │   │   └── customers/
│   │   └── frontend/            # Giao diện khách hàng
│   │       ├── home/
│   │       ├── shop/
│   │       └── auth/
│   └── layouts/                 # Layout chung (admin.blade.php, app.blade.php)
├── routes/
│   └── web.php                  # Định nghĩa tất cả routes
├── database/
│   ├── migrations/              # Cấu trúc bảng dữ liệu
│   └── seeders/                 # Dữ liệu mẫu
├── public/
│   └── storage/                 # Ảnh sản phẩm (symlink)
└── storage/
    └── app/public/              # Nơi lưu trữ ảnh thực tế
```

---

## 🚀 Hướng dẫn cài đặt

### 1. Clone dự án

```bash
git clone <link-repo-của-bạn>.git
cd project-ban-hoa
```

### 2. Cài đặt dependencies

```bash
# Cài đặt PHP dependencies
composer install

# Cài đặt Node.js dependencies
npm install
```

### 3. Cấu hình môi trường

```bash
cp .env.example .env
```

Mở file `.env` và chỉnh sửa các thông tin sau:

```env
APP_NAME="Hương Hoa Xinh"
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ban_hoa
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Tạo Application Key

```bash
php artisan key:generate
```

### 5. Tạo Database

Tạo database tên `ban_hoa` trong phpMyAdmin hoặc MySQL Workbench:

```sql
CREATE DATABASE ban_hoa CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 6. Chạy Migration & Seeder

```bash
# Chạy migration để tạo bảng
php artisan migrate

# Chạy seeder để tạo dữ liệu mẫu
php artisan db:seed
```

> ⚠️ Hoặc chạy cả hai lệnh cùng lúc: `php artisan migrate --seed`

### 7. Tạo Storage Link

```bash
php artisan storage:link
```

### 8. Build Assets

```bash
# Development (với hot reload)
npm run dev

# Hoặc build cho production
npm run build
```

### 9. Chạy Server

```bash
php artisan serve
```

🎉 Truy cập website tại: **http://127.0.0.1:8000**

---

## 🔑 Tài khoản mặc định

Sau khi chạy seeder, bạn có thể đăng nhập với các tài khoản sau:

| Loại tài khoản | Email | Mật khẩu | Truy cập |
|----------------|-------|----------|---------|
| 👑 Admin | admin@huonghoaxinh.com | 12345678 | `/admin/dashboard` |
| 👤 Khách hàng | minhanh@gmail.com | password | `/` |

> 🔒 **Lưu ý bảo mật**: Hãy đổi mật khẩu mặc định trước khi deploy lên môi trường production.

---

## ✅ Trạng thái hoàn thành

### Đã hoàn thành ✅

- [x] Hệ thống Authentication (Đăng ký / Đăng nhập / Đăng xuất)
- [x] Phân quyền Admin & Customer
- [x] Quản lý Sản phẩm (CRUD + Upload ảnh + Tìm kiếm + Lọc)
- [x] Quản lý Danh mục Hoa (Cha - Con)
- [x] Quản lý Đơn hàng (Xem chi tiết + Cập nhật trạng thái)
- [x] Quản lý Khách hàng (Khóa / Mở tài khoản + Xóa)
- [x] Dashboard với biểu đồ thống kê (Chart.js)
- [x] Giao diện Responsive (Bootstrap 5)
- [x] Trang Shop với tìm kiếm và lọc sản phẩm

### Đang phát triển 🔄

- [ ] Giỏ hàng & Thanh toán trực tuyến (VNPay / MoMo)
- [ ] Quản lý Banner và Khuyến mãi
- [ ] Hệ thống đánh giá sản phẩm (Rating & Review)
- [ ] Gửi email xác nhận đơn hàng
- [ ] Tính năng tìm kiếm nâng cao (Elasticsearch)
- [ ] API RESTful cho ứng dụng mobile

---

## 📸 Ảnh chụp màn hình

> 📌 *Thêm ảnh chụp màn hình của dự án vào đây để người dùng có cái nhìn trực quan hơn.*

| Trang chủ | Trang Shop | Dashboard Admin |
|-----------|------------|-----------------|
| *(screenshot)* | *(screenshot)* | *(screenshot)* |

---

## 🤝 Đóng góp

Mọi đóng góp đều được chào đón! Nếu bạn muốn đóng góp cho dự án:

1. **Fork** repository này
2. Tạo branch mới: `git checkout -b feature/ten-tinh-nang`
3. Commit thay đổi: `git commit -m 'feat: thêm tính năng X'`
4. Push lên branch: `git push origin feature/ten-tinh-nang`
5. Tạo **Pull Request**

### Quy ước commit

```
feat: thêm tính năng mới
fix: sửa lỗi
docs: cập nhật tài liệu
style: thay đổi giao diện
refactor: tái cấu trúc code
```

---

## 🐛 Báo cáo lỗi

Nếu bạn gặp lỗi hoặc có đề xuất, hãy [tạo issue mới](../../issues) với mô tả chi tiết về:
- Môi trường (OS, PHP version, Laravel version)
- Các bước tái hiện lỗi
- Kết quả mong đợi và kết quả thực tế

---

## 📄 Giấy phép

Dự án này được phân phối theo giấy phép [MIT License](LICENSE).

```
MIT License - Copyright (c) 2024 Hương Hoa Xinh
```

---

## 👨‍💻 Tác giả

**Hương Hoa Xinh Team**

- Website: [huonghoaxinh.com](https://huonghoaxinh.com)
- Email: admin@huonghoaxinh.com

---

<p align="center">
  Được xây dựng với ❤️ bằng <a href="https://laravel.com/">Laravel</a>
</p>
