# Tài liệu API

Tài liệu này mô tả các endpoint API hiện có, bao gồm phương thức HTTP, chức năng thực hiện, dữ liệu gửi kèm và cấu trúc dữ liệu trả về. Đối với dữ liệu trả về, nếu là JSON sẽ liệt kê các key và giá trị tương ứng cho từng trường hợp; nếu là HTML (render trực tiếp), mô tả cấu trúc mảng `$data` với các key và value.

---

## 1. Xác Thực Người Dùng (Authentication)

### Đăng Nhập (Login)
- **Endpoint**: `POST /auth/login`
- **Chức năng**: Xác thực người dùng và khởi tạo phiên làm việc.
- **Dữ liệu gửi kèm (Request Body - JSON)**:
  ```json
  {
    "email": "user@example.com",
    "password": "password123"
  }
  ```
- **Dữ liệu trả về**:
  - **Thành công (JSON)**:
    ```json
    {
      "success": true,
      "message": "Đăng nhập thành công",
      "user": {
        "id": 1,
        "name": "Nguyễn Văn A",
        "role": "hs"
      }
    }
    ```
    - **Các key**:
      - `success`: Boolean, `true` khi đăng nhập thành công.
      - `message`: Chuỗi, thông báo thành công.
      - `user`: Object chứa thông tin người dùng, với các key:
        - `id`: Số, mã định danh của người dùng.
        - `name`: Chuỗi, tên đầy đủ.
        - `role`: Chuỗi, vai trò người dùng (ví dụ: `"hs"` cho học sinh, `"gv"` cho giáo viên).
  - **Thất bại (JSON)**:
    ```json
    {
      "success": false,
      "message": "Email hoặc mật khẩu không đúng"
    }
    ```
    - **Các key**:
      - `success`: Boolean, `false` khi có lỗi.
      - `message`: Chuỗi, thông báo lỗi.

---

### Đăng Ký (Register)
- **Endpoint**: `POST /auth/register`
- **Chức năng**: Đăng ký tài khoản người dùng mới.
- **Dữ liệu gửi kèm (Request Body - JSON)**:
  ```json
  {
    "name": "Nguyễn Văn A",
    "email": "user@example.com",
    "password": "password123",
    "role": "hocsinh"
  }
  ```
- **Dữ liệu trả về**:
  - **Thành công (JSON)**:
    ```json
    {
      "success": true,
      "message": "Đăng ký thành công"
    }
    ```
  - **Thất bại (JSON)**:
    ```json
    {
      "success": false,
      "message": "Email đã tồn tại"
    }
    ```
    - **Các key (cả hai trường hợp)**:
      - `success`: Boolean, chỉ ra kết quả của quá trình.
      - `message`: Chuỗi, mô tả kết quả.

---

## 2. Quản Lý Lớp Học

### Tạo Lớp Học (Create Classroom)
- **Endpoint**: `POST /classroom/create`
- **Chức năng**: Tạo lớp học mới (dành cho giáo viên).
- **Dữ liệu gửi kèm (Request Body - JSON)**:
  ```json
  {
    "tenlop": "Toán 101",
    "malop": "TOAN101"
  }
  ```
- **Dữ liệu trả về**:
  - **Thành công (JSON)**:
    ```json
    {
      "success": true,
      "message": "Lớp học đã được tạo thành công"
    }
    ```
  - **Thất bại (JSON)**:
    ```json
    {
      "success": false,
      "message": "Lớp học đã tồn tại"
    }
    ```
    - **Các key**:
      - `success`: Boolean.
      - `message`: Chuỗi thông báo.

---

### Tham Gia Lớp Học (Join Classroom)
- **Endpoint**: `POST /classroom/join`
- **Chức năng**: Học sinh tham gia lớp học.
- **Dữ liệu gửi kèm (Request Body - JSON)**:
  ```json
  {
    "malop": "TOAN101"
  }
  ```
- **Dữ liệu trả về**:
  - **Thành công (JSON)**:
    ```json
    {
      "success": true,
      "message": "Tham gia lớp học thành công"
    }
    ```
  - **Thất bại (JSON)**:
    ```json
    {
      "success": false,
      "message": "Mã lớp không hợp lệ"
    }
    ```
    - **Các key**:
      - `success`: Boolean.
      - `message`: Chuỗi thông báo.

---

## 3. Quản Lý Tài Liệu

### Tải Lên Tài Liệu (Upload Document)
- **Endpoint**: `POST /document/upload`
- **Chức năng**: Tải lên tài liệu cho lớp học (dành cho giáo viên).
- **Dữ liệu gửi kèm**: (Form Data)
  - `tai_lieu_file`: Tệp tin cần tải lên.
  - `tieu_de`: Chuỗi, tiêu đề của tài liệu.
  - `mo_ta`: Chuỗi (tùy chọn), mô tả tài liệu.
- **Dữ liệu trả về**:
  - **Thành công (JSON)**:
    ```json
    {
      "success": true,
      "message": "Tài liệu đã được tải lên thành công"
    }
    ```
  - **Thất bại (JSON)**:
    ```json
    {
      "success": false,
      "message": "Định dạng tệp không hợp lệ"
    }
    ```
    - **Các key**:
      - `success`: Boolean.
      - `message`: Chuỗi thông báo.

---

### Tải Xuống Tài Liệu (Download Document)
- **Endpoint**: `GET /document/download/{id}`
- **Chức năng**: Tải xuống tài liệu (dành cho học sinh) – nếu không tìm thấy, trả về lỗi dưới dạng JSON.
- **Dữ liệu trả về**:
  - **Tải xuống tệp**: Nếu tài liệu hợp lệ, người dùng sẽ tải xuống tệp tin.
  - **Thất bại (JSON)**:
    ```json
    {
      "success": false,
      "message": "Tài liệu không tồn tại"
    }
    ```
    - **Các key**:
      - `success`: Boolean.
      - `message`: Chuỗi thông báo.

---

## 4. Hiển Thị Dữ Liệu trong Giao Diện HTML

Các endpoint này render trực tiếp HTML thông qua mảng `$data` gửi vào view.

### Giao Diện Dashboard
- **File View**: `dashboard.php`
- **Cấu trúc mảng `$data`**:
  - `CSS_FILE`: Mảng chứa chuỗi đường dẫn đến các file CSS, ví dụ:
    ```php
    ["public/css/style.css"]
    ```
  - `JS_FILE`: Mảng chứa chuỗi đường dẫn đến các file JavaScript, ví dụ:
    ```php
    ["public/js/dashboard.js"]
    ```
- **Chức năng**: View sẽ sử dụng các key này để nhúng các file CSS và JS tương ứng trên trang dashboard.

---

### Giao Diện Quản Lý Lớp Học của Giáo Viên
- **File View**: `giaovien/pages/quan-ly-lop.php`
- **Cấu trúc mảng `$data`**:
  - `info_students`: Mảng chứa các object thông tin học sinh, mỗi object có các key sau:
    - `hs_id`: Số, mã học sinh.
    - `ho_ten`: Chuỗi, họ và tên.
    - `email`: Chuỗi, email của học sinh.
    - `diem_tb_hs`: Số thực, điểm trung bình của học sinh.
  - `info_classes`: Mảng kết hợp chứa thông tin lớp học, với các key:
    - `id`: Số, mã lớp.
    - `ten_lop`: Chuỗi, tên lớp.
    - `mo_ta`: Chuỗi, mô tả lớp học.
  - `CSS_FILE`: Mảng chứa các chuỗi đường dẫn đến file CSS.
  - `JS_FILE`: Mảng chứa các chuỗi đường dẫn đến file JavaScript.
- **Chức năng**: View sử dụng các thông tin trên để hiển thị danh sách học sinh, thông tin lớp học, và nhúng các file CSS, JS cần thiết.

---

*Lưu ý:* 
- Với các endpoint trả về JSON, kết quả trả về thường bao gồm ít nhất 2 key: `success` (Boolean) và `message` (Chuỗi), cùng với các trường bổ sung khác khi cần.
- Với các endpoint render HTML, mảng `$data` được sử dụng để truyền thông tin động, như đường dẫn file CSS/JS, nội dung trang và các dữ liệu cần hiển thị.
