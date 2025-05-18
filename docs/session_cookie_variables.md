# Web Session & Cookie Variables Documentation

## 1. Session Variables (`$_SESSION`)

| Tên biến | Kiểu dữ liệu | Mô tả | Thời điểm khởi tạo | Thời gian sống |
|----------|--------------|-------|---------------------|----------------|
| `user_id` | int | ID người dùng sau khi đăng nhập | Sau khi đăng nhập thành công | Đến khi logout hoặc session timeout |
| `user_role` | string | Vai trò người dùng (admin, teacher, student) | Sau khi đăng nhập | Như trên |
|`user_name`| string | Tên hiển thị của người dùng | Sau khi đăng nhập | Đến khi logout hoặc session timeout |
---

## 2. Cookie Variables (`$_COOKIE`)


