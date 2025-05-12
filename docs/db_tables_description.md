# Mô tả chi tiết các bảng và trường dữ liệu (Tiếng Việt không dấu)

## 1. Bảng `NguoiDung` (Người dùng)

| Tên Cột | Kiểu Dữ liệu | Ràng buộc/Ghi chú | Mô tả |
| :------ | :----------- | :---------------- | :---- |
| `nguoi_dung_id` | INT | PK, Auto Increment | Khoa chinh dinh danh duy nhat cho moi nguoi dung |
| `ho_ten` | VARCHAR(100) | | Ten day du cua nguoi dung |
| `email` | VARCHAR(100) | UNIQUE | Dia chi email dung de dang ky va dang nhap |
| `mat_khau` | VARCHAR(255) | | Chuoi mat khau da duoc ma hoa an toan |
| `vai_tro` | ENUM('hoc_sinh', 'giao_vien') | | Phan biet vai tro la 'hoc sinh' hay 'giao vien' |
| `duong_dan_anh` | VARCHAR(255) | NULLABLE | Duong dan URL den anh dai dien cua nguoi dung |
| `ngay_tao` | DATETIME | Default: CURRENT_TIMESTAMP | Thoi diem tai khoan duoc tao |

## 2. Bảng `LopHoc` (Lớp học)

| Tên Cột | Kiểu Dữ liệu | Ràng buộc/Ghi chú | Mô tả |
| :------ | :----------- | :---------------- | :---- |
| `lop_hoc_id` | INT | PK, Auto Increment | Khoa chinh dinh danh duy nhat cho moi lop hoc |
| `giao_vien_id` | INT | FK -> NguoiDung.nguoi_dung_id | ID cua giao vien tao va quan ly lop hoc |
| `ma_lop` | VARCHAR(20) | UNIQUE | Ma duy nhat de hoc sinh co the tham gia lop hoc |
| `ten_lop` | VARCHAR(100) | | Ten cua lop hoc |
| `mo_ta` | TEXT | NULLABLE | Mo ta chi tiet hon ve lop hoc (muc tieu, noi dung...) |
| `ngay_tao` | DATETIME | Default: CURRENT_TIMESTAMP | Thoi diem lop hoc duoc tao |

## 3. Bảng `HocSinhLopHoc` (Học sinh tham gia lớp)

| Tên Cột | Kiểu Dữ liệu | Ràng buộc/Ghi chú | Mô tả |
| :------ | :----------- | :---------------- | :---- |
| `id` | INT | PK, Auto Increment | Khoa chinh dinh danh cho moi luot ghi danh |
| `lop_hoc_id` | INT | FK -> LopHoc.lop_hoc_id | ID cua lop hoc ma hoc sinh tham gia |
| `hoc_sinh_id` | INT | FK -> NguoiDung.nguoi_dung_id | ID cua hoc sinh tham gia lop hoc |
| `ngay_tham_gia` | DATETIME | Default: CURRENT_TIMESTAMP | Thoi diem hoc sinh duoc ghi danh vao lop |

## 4. Bảng `ThongBao` (Thông báo)

| Tên Cột | Kiểu Dữ liệu | Ràng buộc/Ghi chú | Mô tả |
| :------ | :----------- | :---------------- | :---- |
| `thong_bao_id` | INT | PK, Auto Increment | Khoa chinh dinh danh duy nhat cho moi thong bao |
| `lop_hoc_id` | INT | FK -> LopHoc.lop_hoc_id | ID cua lop hoc ma thong bao nay duoc gui toi |
| `nguoi_gui_id` | INT | FK -> NguoiDung.nguoi_dung_id | ID cua nguoi dung gui thong bao |
| `tieu_de` | VARCHAR(200) | | Tieu de cua thong bao |
| `noi_dung` | TEXT | | Noi dung chi tiet cua thong bao |
| `ngay_tao` | DATETIME | Default: CURRENT_TIMESTAMP | Thoi diem thong bao duoc tao va gui di |

## 5. Bảng `TaiLieu` (Tài liệu)

| Tên Cột | Kiểu Dữ liệu | Ràng buộc/Ghi chú | Mô tả |
| :------ | :----------- | :---------------- | :---- |
| `tai_lieu_id` | INT | PK, Auto Increment | Khoa chinh dinh danh duy nhat cho moi tai lieu |
| `nguoi_dang_id` | INT | FK -> NguoiDung.nguoi_dung_id | ID cua nguoi dung tai len hoac chia se tai lieu nay |
| `lop_hoc_id` | INT | FK -> LopHoc.lop_hoc_id | ID cua lop hoc ma tai lieu nay duoc chia se cho |
| `tieu_de` | VARCHAR(200) | | Tieu de cua tai lieu hoc tap |
| `mo_ta` | TEXT | NULLABLE | Mo ta chi tiet hon ve noi dung hoac cach su dung tai lieu |
| `duong_dan_file` | VARCHAR(255) | | Duong dan URL de truy cap/tai ve file tai lieu |
| `chia_se_voi_giao_vien` | BOOLEAN | Default: FALSE | Danh dau co chia se voi giao vien khac hay khong |
| `ngay_dang` | DATETIME | Default: CURRENT_TIMESTAMP | Thoi diem tai lieu duoc tai len he thong |

## 6. Bảng `CauHoi` (Câu hỏi)

| Tên Cột | Kiểu Dữ liệu | Ràng buộc/Ghi chú | Mô tả |
| :------ | :----------- | :---------------- | :---- |
| `cau_hoi_id` | INT | PK, Auto Increment | Khoa chinh dinh danh duy nhat cho moi cau hoi |
| `nguoi_tao_id` | INT | FK -> NguoiDung.nguoi_dung_id | ID cua giao vien da tao cau hoi nay |
| `mon_hoc` | VARCHAR(100) | | Ten mon hoc lien quan (phan loai) |
| `chuong` | VARCHAR(100) | | Phan chuong/bai lien quan den cau hoi |
| `noi_dung` | TEXT | | Noi dung day du cua cau hoi (co the chua ma HTML/LaTeX) |
| `duong_dan_hinh` | VARCHAR(255) | NULLABLE | Duong dan URL den hinh anh minh hoa cho cau hoi |
| `duong_dan_am_thanh` | VARCHAR(255) | NULLABLE | Duong dan URL den file am thanh (huu ich cho mon ngoai ngu) |
| `cong_thuc_toan` | TEXT | NULLABLE | Luu tru cong thuc toan hoc duoi dang ma LaTeX |
| `ngay_tao` | DATETIME | Default: CURRENT_TIMESTAMP | Thoi diem cau hoi duoc tao |

## 7. Bảng `DapAn` (Đáp án)

| Tên Cột | Kiểu Dữ liệu | Ràng buộc/Ghi chú | Mô tả |
| :------ | :----------- | :---------------- | :---- |
| `dap_an_id` | INT | PK, Auto Increment | Khoa chinh dinh danh duy nhat cho moi lua chon |
| `cau_hoi_id` | INT | FK -> CauHoi.cau_hoi_id | ID cua cau hoi ma lua chon nay thuoc ve |
| `noi_dung` | TEXT | | Noi dung cua phuong an lua chon (co the chua LaTeX) |
| `la_dap_an_dung` | BOOLEAN | | Danh dau `true` neu day la dap an dung |

## 8. Bảng `DeThi` (Đề thi)

| Tên Cột | Kiểu Dữ liệu | Ràng buộc/Ghi chú | Mô tả |
| :------ | :----------- | :---------------- | :---- |
| `de_thi_id` | INT | PK, Auto Increment | Khoa chinh dinh danh duy nhat cho moi de thi |
| `nguoi_tao_id` | INT | FK -> NguoiDung.nguoi_dung_id | ID cua giao vien da tao de thi nay |
| `lop_hoc_id` | INT | FK -> LopHoc.lop_hoc_id | ID cua lop hoc ma de thi nay duoc giao |
| `tieu_de` | VARCHAR(200) | | Tieu de cua de thi |
| `mo_ta` | TEXT | NULLABLE | Mo ta chi tiet hon hoac huong dan cho de thi |
| `thoi_gian_phut` | INT | | Thoi gian lam bai cho phep (tinh bang phut) |
| `tong_diem` | FLOAT | | Tong diem toi da co the dat duoc cho de thi |
| `ngay_thi` | DATETIME | | Thoi diem dien ra bai thi |
| `ngay_tao` | DATETIME | Default: CURRENT_TIMESTAMP | Thoi diem de thi duoc tao |

## 9. Bảng `CauHoiDeThi` (Câu hỏi trong đề thi)

| Tên Cột | Kiểu Dữ liệu | Ràng buộc/Ghi chú | Mô tả |
| :------ | :----------- | :---------------- | :---- |
| `cau_hoi_de_thi_id` | INT | PK, Auto Increment | Khoa chinh dinh danh moi lien ket giua cau hoi va de thi cu the |
| `de_thi_id` | INT | FK -> DeThi.de_thi_id | ID cua de thi chua cau hoi nay |
| `cau_hoi_id` | INT | FK -> CauHoi.cau_hoi_id | ID cua cau hoi duoc su dung trong de thi nay |

## 10. Bảng `HocSinhLamBaiThi` (Học sinh làm bài thi)

| Tên Cột | Kiểu Dữ liệu | Ràng buộc/Ghi chú | Mô tả |
| :------ | :----------- | :---------------- | :---- |
| `hoc_sinh_bai_thi_id` | INT | PK, Auto Increment | Khoa chinh dinh danh duy nhat cho moi luot nop bai thi |
| `de_thi_id` | INT | FK -> DeThi.de_thi_id | ID cua de thi ma bai nop nay tuong ung |
| `hoc_sinh_id` | INT | FK -> NguoiDung.nguoi_dung_id | ID cua hoc sinh da thuc hien bai nop nay |
| `thoi_gian_bat_dau` | DATETIME | | Thoi diem chinh xac hoc sinh bat dau lam bai |
| `thoi_gian_ket_thuc` | DATETIME | NULLABLE | Thoi diem hoc sinh nop bai hoac thoi diem het gio lam bai |
| `diem` | FLOAT | NULLABLE | Diem so cuoi cung hoc sinh dat duoc |

## 11. Bảng `TraLoiBaiThi` (Trả lời bài thi)

| Tên Cột | Kiểu Dữ liệu | Ràng buộc/Ghi chú | Mô tả |
| :------ | :----------- | :---------------- | :---- |
| `tra_loi_id` | INT | PK, Auto Increment | Khoa chinh dinh danh duy nhat cho moi cau tra loi cua hoc sinh |
| `hoc_sinh_bai_thi_id` | INT | FK -> HocSinhLamBaiThi.hoc_sinh_bai_thi_id | ID cua bai nop ma cau tra loi nay thuoc ve |
| `cau_hoi_id` | INT | FK -> CauHoi.cau_hoi_id | ID cua cau hoi cu the ma hoc sinh dang tra loi |
| `dap_an_chon_id` | INT | FK -> DapAn.dap_an_id | ID cua phuong an lua chon ma hoc sinh da chon |
| `la_dap_an_dung` | BOOLEAN | | Danh dau cau tra loi nay dung hay sai |

## 12. Bảng `BaiTap` (Bài tập)

| Tên Cột | Kiểu Dữ liệu | Ràng buộc/Ghi chú | Mô tả |
| :------ | :----------- | :---------------- | :---- |
| `bai_tap_id` | INT | PK, Auto Increment | Khoa chinh dinh danh duy nhat cho moi bai tap |
| `nguoi_tao_id` | INT | FK -> NguoiDung.nguoi_dung_id, NOT NULL | ID cua giao vien da tao bai tap nay |
| `lop_hoc_id` | INT | FK -> LopHoc.lop_hoc_id, NOT NULL | ID cua lop hoc ma bai tap nay duoc giao |
| `tieu_de` | VARCHAR(100) | NOT NULL | Tieu de cua bai tap |
| `mo_ta` | TEXT | NULLABLE | Mo ta chi tiet hon hoac huong dan cho bai tap |
| `han_nop` | DATETIME | | Thoi han cuoi cung de nop bai tap |
| `ngay_tao` | DATETIME | Default: CURRENT_TIMESTAMP | Thoi diem bai tap duoc tao |

## 13. Bảng `CauHoiBaiTap` (Câu hỏi trong bài tập)

| Tên Cột | Kiểu Dữ liệu | Ràng buộc/Ghi chú | Mô tả |
| :------ | :----------- | :---------------- | :---- |
| `cau_hoi_bai_tap_id` | INT | PK, Auto Increment | Khoa chinh dinh danh moi lien ket giua cau hoi va bai tap cu the |
| `bai_tap_id` | INT | FK -> BaiTap.bai_tap_id, NOT NULL | ID cua bai tap chua cau hoi nay |
| `cau_hoi_id` | INT | FK -> CauHoi.cau_hoi_id, NOT NULL | ID cua cau hoi duoc su dung trong bai tap nay |

## 14. Bảng `HocSinhLamBaiTap` (Học sinh làm bài tập)

| Tên Cột | Kiểu Dữ liệu | Ràng buộc/Ghi chú | Mô tả |
| :------ | :----------- | :---------------- | :---- |
| `hoc_sinh_bai_tap_id` | INT | PK, Auto Increment | Khoa chinh dinh danh duy nhat cho moi luot nop bai tap |
| `bai_tap_id` | INT | FK -> BaiTap.bai_tap_id, NOT NULL | ID cua bai tap ma bai nop nay tuong ung |
| `hoc_sinh_id` | INT | FK -> NguoiDung.nguoi_dung_id, NOT NULL | ID cua hoc sinh da thuc hien bai nop nay |
| `thoi_gian_bat_dau` | DATETIME | | Thoi diem chinh xac hoc sinh bat dau lam bai |
| `thoi_gian_ket_thuc` | DATETIME | NULLABLE | Thoi diem hoc sinh nop bai hoac thoi diem het gio lam bai |
| `diem` | FLOAT | NULLABLE | Diem so cuoi cung hoc sinh dat duoc |

## 15. Bảng `TraLoiBaiTap` (Trả lời bài tập)

| Tên Cột | Kiểu Dữ liệu | Ràng buộc/Ghi chú | Mô tả |
| :------ | :----------- | :---------------- | :---- |
| `tra_loi_bai_tap_id` | INT | PK, Auto Increment | Khoa chinh dinh danh duy nhat cho moi cau tra loi cua hoc sinh |
| `hoc_sinh_bai_tap_id` | INT | FK -> HocSinhLamBaiTap.hoc_sinh_bai_tap_id, NOT NULL | ID cua bai nop ma cau tra loi nay thuoc ve |
| `cau_hoi_id` | INT | FK -> CauHoi.cau_hoi_id, NOT NULL | ID cua cau hoi cu the ma hoc sinh dang tra loi |
| `dap_an_chon_id` | INT | FK -> DapAn.dap_an_id, NULLABLE | ID cua phuong an lua chon ma hoc sinh da chon |
| `la_dap_an_dung` | BOOLEAN | | Danh dau cau tra loi nay dung hay sai |

<!-- *Ghi chú: Có thể cần bảng `AnnouncementReadStatus(announcement_id, student_id)` để theo dõi trạng thái đọc chi tiết của từng học sinh.* -->

Link sơ đồ diagram cho cơ sở dữ liệu: [Diagram](https://dbdiagram.io/d/onthitracnghi-68160fa91ca52373f54ef56a)