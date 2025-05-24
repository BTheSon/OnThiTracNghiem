# Web Session & Cookie Variables Documentation

## 1. Session Variables (`$_SESSION`)

| Tên biến | Kiểu dữ liệu | Mô tả | Thời điểm khởi tạo | Thời gian sống |
|----------|--------------|-------|---------------------|----------------|
| `user_id` | int | ID người dùng sau khi đăng nhập | Sau khi đăng nhập thành công | Đến khi logout hoặc session timeout |
| `user_role` | string | Vai trò người dùng (gv, hs) | Sau khi đăng nhập | Như trên |
|`user_name`| string | Tên hiển thị của người dùng | Sau khi đăng nhập | Đến khi logout hoặc session timeout |
|`class_id`| string | ID lớp học hiện tại | Sau khi đăng nhập và khi chọn lớp học | Đến khi logout hoặc session timeout hoặc về trang chủ |
|`class_name`| string | Tên lớp học hiện tại | Sau khi đăng nhập và khi chọn lớp học | Đến khi logout hoặc session timeout hoặc về trang chủ |
---

## 2. Cookie Variables (`$_COOKIE`)


