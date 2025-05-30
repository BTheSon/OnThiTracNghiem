# Web Session & Cookie Variables Documentation

## 1. Session Variables (`$_SESSION`)

| Tên biến | Kiểu dữ liệu | Mô tả | Thời điểm khởi tạo | Thời gian sống |
|----------|--------------|-------|---------------------|----------------|
| `user_id` | int | ID người dùng sau khi đăng nhập | Sau khi đăng nhập thành công | Đến khi logout hoặc session timeout |
| `user_role` | string | Vai trò người dùng (gv, hs) | Sau khi đăng nhập | Như trên |
|`user_name`| string | Tên hiển thị của người dùng | Sau khi đăng nhập | Đến khi logout hoặc session timeout |
|`user_email`| string | Tên hiển thị của người dùng | Sau khi đăng nhập | Đến khi logout hoặc session timeout |
|`class_id`| string | ID lớp học hiện tại | Sau khi đăng nhập và khi chọn lớp học | Đến khi logout hoặc session timeout hoặc về trang chủ |
|`class_name`| string | Tên lớp học hiện tại | Sau khi đăng nhập và khi chọn lớp học | Đến khi logout hoặc session timeout hoặc về trang chủ |
|`exam_info`| array | Thông tin về bài thi đang làm, gồm `hst_id` (ID bài thi học sinh) và `dethi_id` (ID đề thi) | Khi học sinh bắt đầu làm bài thi | Đến khi nộp bài, logout hoặc session timeout |
---

## 2. Cookie Variables (`$_COOKIE`)


