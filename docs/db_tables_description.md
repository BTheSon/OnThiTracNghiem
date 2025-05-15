# Mô tả chi tiết các bảng và trường dữ liệu

## 1. Bảng `NguoiDung` (Người dùng)

-  Lưu trữ thông tin cơ bản của tất cả người dùng trong hệ thống, bao gồm cả học sinh và giáo viên. Đây là bảng trung tâm để quản lý danh tính và vai trò người dùng.

| Tên Cột     | Kiểu Dữ liệu        | Ràng buộc/Ghi chú              | Mô tả                                                             |
| :---------- | :------------------ | :----------------------------- | :---------------------------------------------------------------- |
| `id`        | INT                 | PK, Auto Increment             | Khóa chính định danh duy nhất cho mỗi người dùng                  |
| `ho_ten`    | VARCHAR(100)        |                                | Tên đầy đủ của người dùng                                         |
| `email`     | VARCHAR(100)        | UNIQUE                         | Địa chỉ email dùng để đăng ký và đăng nhập                        |
| `mk`        | VARCHAR(255)        |                                | Chuỗi mật khẩu đã được mã hóa an toàn                             |
| `vai_tro`   | ENUM('hs', 'gv')    |                                | Phân biệt vai trò là 'hs' (học sinh) hay 'gv' (giáo viên)          |
| `anh`       | VARCHAR(255)        | NULLABLE                       | Đường dẫn URL đến ảnh đại diện của người dùng                     |

## 2. Bảng `LopHoc` (Lớp học)

-  Lưu trữ thông tin về các lớp học được tạo ra trong hệ thống, bao gồm giáo viên phụ trách, mã lớp, tên lớp và các mô tả liên quan.

| Tên Cột    | Kiểu Dữ liệu        | Ràng buộc/Ghi chú              | Mô tả                                                                 |
| :--------- | :------------------ | :----------------------------- | :-------------------------------------------------------------------- |
| `id`       | INT                 | PK, Auto Increment             | Khóa chính định danh duy nhất cho mỗi lớp học                         |
| `gv_id`    | INT                 | FK -> NguoiDung.id             | ID của giáo viên tạo và quản lý lớp học                               |
| `ma_lop`   | VARCHAR(20)         | UNIQUE                         | Mã duy nhất để học sinh có thể tham gia lớp học                        |
| `ten_lop`  | VARCHAR(100)        |                                | Tên của lớp học                                                       |
| `mo_ta`    | TEXT                | NULLABLE                       | Mô tả chi tiết hơn về lớp học (mục tiêu, nội dung...)                 |
| `ngay_tao` | DATETIME            | Default: CURRENT_TIMESTAMP     | Thời điểm lớp học được tạo                                             |

## 3. Bảng `HocSinhLop` (Học sinh tham gia lớp)

-  Quản lý mối quan hệ giữa học sinh và lớp học, ghi nhận học sinh nào đã tham gia vào lớp học nào và thời điểm tham gia.

| Tên Cột         | Kiểu Dữ liệu        | Ràng buộc/Ghi chú              | Mô tả                                                 |
| :-------------- | :------------------ | :----------------------------- | :---------------------------------------------------- |
| `id`            | INT                 | PK, Auto Increment             | Khóa chính định danh cho mỗi lượt ghi danh             |
| `lh_id`         | INT                 | FK -> LopHoc.id                | ID của lớp học mà học sinh tham gia                   |
| `hs_id`         | INT                 | FK -> NguoiDung.id             | ID của học sinh tham gia lớp học                      |
| `ngay_tham_gia` | DATETIME            | Default: CURRENT_TIMESTAMP     | Thời điểm học sinh được ghi danh vào lớp              |

## 4. Bảng `ThongBao` (Thông báo)

-  Lưu trữ các thông báo được gửi từ giáo viên đến các lớp học cụ thể, giúp truyền đạt thông tin quan trọng.

| Tên Cột        | Kiểu Dữ liệu        | Ràng buộc/Ghi chú              | Mô tả                                                         |
| :------------- | :------------------ | :----------------------------- | :------------------------------------------------------------ |
| `id`           | INT                 | PK, Auto Increment             | Khóa chính định danh duy nhất cho mỗi thông báo               |
| `lh_id`        | INT                 | FK -> LopHoc.id                | ID của lớp học mà thông báo này được gửi tới                 |
| `nguoi_gui_id` | INT                 | FK -> NguoiDung.id             | ID của người dùng (thường là giáo viên) gửi thông báo        |
| `tieu_de`      | VARCHAR(200)        |                                | Tiêu đề của thông báo                                         |
| `noi_dung`     | TEXT                |                                | Nội dung chi tiết của thông báo                               |
| `ngay_tao`     | DATETIME            | Default: CURRENT_TIMESTAMP     | Thời điểm thông báo được tạo và gửi đi                        |

## 5. Bảng `TaiLieu` (Tài liệu)

-  Quản lý các tài liệu học tập được giáo viên đăng tải và chia sẻ cho các lớp học.

| Tên Cột         | Kiểu Dữ liệu        | Ràng buộc/Ghi chú              | Mô tả                                                                   |
| :-------------- | :------------------ | :----------------------------- | :---------------------------------------------------------------------- |
| `id`            | INT                 | PK, Auto Increment             | Khóa chính định danh duy nhất cho mỗi tài liệu                          |
| `nguoi_dang_id` | INT                 | FK -> NguoiDung.id             | ID của người dùng (giáo viên) tải lên hoặc chia sẻ tài liệu này        |
| `lh_id`         | INT                 | FK -> LopHoc.id                | ID của lớp học mà tài liệu này được chia sẻ cho                         |
| `tieu_de`       | VARCHAR(200)        |                                | Tiêu đề của tài liệu học tập                                             |
| `mo_ta`         | TEXT                | NULLABLE                       | Mô tả chi tiết hơn về nội dung hoặc cách sử dụng tài liệu                |
| `file_dir`      | VARCHAR(255)        |                                | Đường dẫn URL để truy cập/tải về file tài liệu                          |
| `ngay_dang`     | DATETIME            | Default: CURRENT_TIMESTAMP     | Thời điểm tài liệu được tải lên hệ thống                                 |

## 6. Bảng `CauHoi` (Câu hỏi)

-  Là ngân hàng câu hỏi, lưu trữ các câu hỏi trắc nghiệm được tạo bởi giáo viên, có thể kèm theo hình ảnh, âm thanh, công thức và phân loại theo môn học.

| Tên Cột        | Kiểu Dữ liệu        | Ràng buộc/Ghi chú                                 | Mô tả                                                                       |
| :------------- | :------------------ | :----------------------------------------------- | :-------------------------------------------------------------------------- |
| `id`           | INT                 | PK, Auto Increment                               | Khóa chính định danh duy nhất cho mỗi câu hỏi                              |
| `nguoi_tao_id` | INT                 | FK -> NguoiDung.id (ngầm định, nên có ràng buộc) | ID của giáo viên đã tạo câu hỏi này                                         |
| `mon_hoc`      | VARCHAR(100)        |                                                  | Tên môn học liên quan (phân loại)                                           |
| `noi_dung`     | TEXT                |                                                  | Nội dung đầy đủ của câu hỏi (có thể chứa mã HTML/LaTeX)                     |
| `hinh`         | VARCHAR(255)        | NULLABLE                                         | Đường dẫn URL đến hình ảnh minh họa cho câu hỏi                             |
| `am_thanh`     | VARCHAR(255)        | NULLABLE                                         | Đường dẫn URL đến file âm thanh (hữu ích cho môn ngoại ngữ)                 |
| `cong_thuc`    | TEXT                | NULLABLE                                         | Lưu trữ công thức toán học dưới dạng mã LaTeX                               |
| `ngay_tao`     | DATETIME            | Default: CURRENT_TIMESTAMP                       | Thời điểm câu hỏi được tạo                                                  |

## 7. Bảng `DapAn` (Đáp án)

-  Lưu trữ các phương án trả lời cho mỗi câu hỏi trong bảng `CauHoi`, đồng thời chỉ định đâu là đáp án đúng.

| Tên Cột      | Kiểu Dữ liệu        | Ràng buộc/Ghi chú              | Mô tả                                                               |
| :----------- | :------------------ | :----------------------------- | :------------------------------------------------------------------ |
| `id`         | INT                 | PK, Auto Increment             | Khóa chính định danh duy nhất cho mỗi lựa chọn                      |
| `cau_hoi_id` | INT                 | FK -> CauHoi.id                | ID của câu hỏi mà lựa chọn này thuộc về                              |
| `noi_dung`   | TEXT                |                                | Nội dung của phương án lựa chọn (có thể chứa LaTeX)                  |
| `da_dung`    | BOOLEAN             |                                | Đánh dấu `true` nếu đây là đáp án đúng                               |

## 8. Bảng `DeThi` (Đề thi)

-  Quản lý thông tin về các đề thi được tạo bởi giáo viên, bao gồm tiêu đề, mô tả, thời gian làm bài, tổng điểm và thời điểm thi.

| Tên Cột        | Kiểu Dữ liệu        | Ràng buộc/Ghi chú              | Mô tả                                                                 |
| :------------- | :------------------ | :----------------------------- | :-------------------------------------------------------------------- |
| `id`           | INT                 | PK, Auto Increment             | Khóa chính định danh duy nhất cho mỗi đề thi                          |
| `nguoi_tao_id` | INT                 | FK -> NguoiDung.id             | ID của giáo viên đã tạo đề thi này                                     |
| `lh_id`        | INT                 | FK -> LopHoc.id                | ID của lớp học mà đề thi này được giao                                 |
| `tieu_de`      | VARCHAR(200)        |                                | Tiêu đề của đề thi                                                    |
| `mo_ta`        | TEXT                | NULLABLE                       | Mô tả chi tiết hơn hoặc hướng dẫn cho đề thi                           |
| `tg_phut`      | INT                 |                                | Thời gian làm bài cho phép (tính bằng phút)                           |
| `tong_diem`    | FLOAT               |                                | Tổng điểm tối đa có thể đạt được cho đề thi                            |
| `ngay_thi`     | DATETIME            |                                | Thời điểm diễn ra bài thi                                              |
| `ngay_tao`     | DATETIME            | Default: CURRENT_TIMESTAMP     | Thời điểm đề thi được tạo                                              |

## 9. Bảng `CauHoiDeThi` (Câu hỏi trong đề thi)

-  Tạo mối liên kết giữa đề thi và các câu hỏi cụ thể được chọn từ ngân hàng câu hỏi để cấu thành nên đề thi đó.

| Tên Cột | Kiểu Dữ liệu        | Ràng buộc/Ghi chú              | Mô tả                                                                          |
| :------ | :------------------ | :----------------------------- | :----------------------------------------------------------------------------- |
| `id`    | INT                 | PK, Auto Increment             | Khóa chính định danh mỗi liên kết giữa câu hỏi và đề thi cụ thể                |
| `de_id` | INT                 | FK -> DeThi.id                 | ID của đề thi chứa câu hỏi này                                                 |
| `cau_id`| INT                 | FK -> CauHoi.id                | ID của câu hỏi được sử dụng trong đề thi này                                    |

## 10. Bảng `HocSinhThi` (Học sinh làm bài thi)

-  Ghi lại quá trình làm bài thi của học sinh, bao gồm thời gian bắt đầu, kết thúc và điểm số đạt được cho mỗi lượt làm bài.

| Tên Cột       | Kiểu Dữ liệu        | Ràng buộc/Ghi chú              | Mô tả                                                                       |
| :------------ | :------------------ | :----------------------------- | :-------------------------------------------------------------------------- |
| `id`          | INT                 | PK, Auto Increment             | Khóa chính định danh duy nhất cho mỗi lượt làm bài thi của học sinh        |
| `de_thi_id`   | INT                 | FK -> DeThi.id                 | ID của đề thi mà bài làm này tương ứng                                       |
| `hoc_sinh_id` | INT                 | FK -> NguoiDung.id             | ID của học sinh đã thực hiện bài làm này                                     |
| `bat_dau`     | DATETIME            |                                | Thời điểm chính xác học sinh bắt đầu làm bài                                 |
| `ket_thuc`    | DATETIME            | NULLABLE                       | Thời điểm học sinh nộp bài hoặc thời điểm hết giờ làm bài                     |
| `diem`        | FLOAT               | NULLABLE                       | Điểm số cuối cùng học sinh đạt được                                          |

## 11. Bảng `TraLoiBaiThi` (Trả lời bài thi)

-  Lưu trữ chi tiết từng câu trả lời của học sinh cho mỗi câu hỏi trong một lượt làm bài thi cụ thể, đồng thời ghi nhận câu trả lời đó đúng hay sai.

| Tên Cột      | Kiểu Dữ liệu        | Ràng buộc/Ghi chú              | Mô tả                                                                                   |
| :----------- | :------------------ | :----------------------------- | :-------------------------------------------------------------------------------------- |
| `id`         | INT                 | PK, Auto Increment             | Khóa chính định danh duy nhất cho mỗi câu trả lời của học sinh                          |
| `hs_thi_id`  | INT                 | FK -> HocSinhThi.id            | ID của lượt làm bài thi (trong bảng `HocSinhThi`) mà câu trả lời này thuộc về          |
| `cau_hoi_id` | INT                 | FK -> CauHoi.id                | ID của câu hỏi cụ thể mà học sinh đang trả lời                                          |
| `da_id`      | INT                 | FK -> DapAn.id                 | ID của phương án lựa chọn (trong bảng `DapAn`) mà học sinh đã chọn                       |
| `dung`       | BOOLEAN             |                                | Đánh dấu câu trả lời này đúng (`true`) hay sai (`false`)                                |

---

[Minh họa sơ đồ cơ sở dữ liệu trên dbdiagram.io](https://dbdiagram.io/d/onthitracnghi-68160fa91ca52373f54ef56a)