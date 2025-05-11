CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

-- BẢNG NGƯỜI DÙNG
CREATE TABLE NguoiDung (
    nguoi_dung_id INT AUTO_INCREMENT PRIMARY KEY,
    ho_ten VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    mat_khau VARCHAR(255),
    vai_tro ENUM('hoc_sinh', 'giao_vien'),
    duong_dan_anh VARCHAR(255),
    ngay_tao DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- BẢNG LỚP HỌC
CREATE TABLE LopHoc (
    lop_hoc_id INT AUTO_INCREMENT PRIMARY KEY,
    giao_vien_id INT,
    ma_lop VARCHAR(20) UNIQUE,
    ten_lop VARCHAR(100),
    mo_ta TEXT,
    ngay_tao DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (giao_vien_id) REFERENCES NguoiDung(nguoi_dung_id)
);

-- BẢNG HỌC SINH THAM GIA LỚP
CREATE TABLE HocSinhLopHoc (
    id INT AUTO_INCREMENT PRIMARY KEY,
    lop_hoc_id INT,
    hoc_sinh_id INT,
    ngay_tham_gia DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (lop_hoc_id) REFERENCES LopHoc(lop_hoc_id),
    FOREIGN KEY (hoc_sinh_id) REFERENCES NguoiDung(nguoi_dung_id)
);

-- BẢNG THÔNG BÁO
CREATE TABLE ThongBao (
    thong_bao_id INT AUTO_INCREMENT PRIMARY KEY,
    lop_hoc_id INT,
    nguoi_gui_id INT,
    tieu_de VARCHAR(200),
    noi_dung TEXT,
    ngay_tao DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (lop_hoc_id) REFERENCES LopHoc(lop_hoc_id),
    FOREIGN KEY (nguoi_gui_id) REFERENCES NguoiDung(nguoi_dung_id)
);

-- BẢNG TÀI LIỆU
CREATE TABLE TaiLieu (
    tai_lieu_id INT AUTO_INCREMENT PRIMARY KEY,
    nguoi_dang_id INT,
    lop_hoc_id INT,
    tieu_de VARCHAR(200),
    mo_ta TEXT,
    duong_dan_file VARCHAR(255),
    chia_se_voi_giao_vien BOOLEAN DEFAULT FALSE,
    ngay_dang DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (nguoi_dang_id) REFERENCES NguoiDung(nguoi_dung_id),
    FOREIGN KEY (lop_hoc_id) REFERENCES LopHoc(lop_hoc_id)
);

-- BẢNG CÂU HỎI
CREATE TABLE CauHoi (
    cau_hoi_id INT AUTO_INCREMENT PRIMARY KEY,
    nguoi_tao_id INT,
    mon_hoc VARCHAR(100),
    chuong VARCHAR(100),
    noi_dung TEXT,
    duong_dan_hinh VARCHAR(255),
    duong_dan_am_thanh VARCHAR(255),
    cong_thuc_toan TEXT,
    ngay_tao DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (nguoi_tao_id) REFERENCES NguoiDung(nguoi_dung_id)
);

-- BẢNG ĐÁP ÁN
CREATE TABLE DapAn (
    dap_an_id INT AUTO_INCREMENT PRIMARY KEY,
    cau_hoi_id INT,
    noi_dung TEXT,
    la_dap_an_dung BOOLEAN,
    FOREIGN KEY (cau_hoi_id) REFERENCES CauHoi(cau_hoi_id)
);

-- BẢNG ĐỀ THI
CREATE TABLE DeThi (
    de_thi_id INT AUTO_INCREMENT PRIMARY KEY,
    nguoi_tao_id INT,
    lop_hoc_id INT,
    tieu_de VARCHAR(200),
    mo_ta TEXT,
    thoi_gian_phut INT,
    tong_diem FLOAT,
    ngay_thi DATETIME,
    ngay_tao DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (nguoi_tao_id) REFERENCES NguoiDung(nguoi_dung_id),
    FOREIGN KEY (lop_hoc_id) REFERENCES LopHoc(lop_hoc_id)
);

-- BẢNG CÂU HỎI TRONG ĐỀ THI
CREATE TABLE CauHoiDeThi (
    cau_hoi_de_thi_id INT AUTO_INCREMENT PRIMARY KEY,
    de_thi_id INT,
    cau_hoi_id INT,
    FOREIGN KEY (de_thi_id) REFERENCES DeThi(de_thi_id),
    FOREIGN KEY (cau_hoi_id) REFERENCES CauHoi(cau_hoi_id)
);

-- BẢNG HỌC SINH LÀM BÀI THI
CREATE TABLE HocSinhLamBaiThi (
    hoc_sinh_bai_thi_id INT AUTO_INCREMENT PRIMARY KEY,
    de_thi_id INT,
    hoc_sinh_id INT,
    thoi_gian_bat_dau DATETIME,
    thoi_gian_ket_thuc DATETIME,
    diem FLOAT,
    FOREIGN KEY (de_thi_id) REFERENCES DeThi(de_thi_id),
    FOREIGN KEY (hoc_sinh_id) REFERENCES NguoiDung(nguoi_dung_id)
);

-- BẢNG TRẢ LỜI CÂU HỎI TRONG BÀI THI
CREATE TABLE TraLoiBaiThi (
    tra_loi_id INT AUTO_INCREMENT PRIMARY KEY,
    hoc_sinh_bai_thi_id INT,
    cau_hoi_id INT,
    dap_an_chon_id INT,
    la_dap_an_dung BOOLEAN,
    FOREIGN KEY (hoc_sinh_bai_thi_id) REFERENCES HocSinhLamBaiThi(hoc_sinh_bai_thi_id),
    FOREIGN KEY (cau_hoi_id) REFERENCES CauHoi(cau_hoi_id),
    FOREIGN KEY (dap_an_chon_id) REFERENCES DapAn(dap_an_id)
);

-- BẢNG BÀI TẬP
CREATE TABLE BaiTap (
    bai_tap_id INT AUTO_INCREMENT PRIMARY KEY,
    nguoi_tao_id INT NOT NULL,
    lop_hoc_id INT NOT NULL,
    tieu_de VARCHAR(100) NOT NULL,
    mo_ta TEXT,
    han_nop DATETIME,
    ngay_tao DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (nguoi_tao_id) REFERENCES NguoiDung(nguoi_dung_id),
    FOREIGN KEY (lop_hoc_id) REFERENCES LopHoc(lop_hoc_id)
);

-- BẢNG CÂU HỎI TRONG BÀI TẬP
CREATE TABLE CauHoiBaiTap (
    cau_hoi_bai_tap_id INT AUTO_INCREMENT PRIMARY KEY,
    bai_tap_id INT NOT NULL,
    cau_hoi_id INT NOT NULL,
    FOREIGN KEY (bai_tap_id) REFERENCES BaiTap(bai_tap_id),
    FOREIGN KEY (cau_hoi_id) REFERENCES CauHoi(cau_hoi_id)
);

-- BẢNG HỌC SINH LÀM BÀI TẬP
CREATE TABLE HocSinhLamBaiTap (
    hoc_sinh_bai_tap_id INT AUTO_INCREMENT PRIMARY KEY,
    bai_tap_id INT NOT NULL,
    hoc_sinh_id INT NOT NULL,
    thoi_gian_bat_dau DATETIME,
    thoi_gian_ket_thuc DATETIME,
    diem FLOAT,
    FOREIGN KEY (bai_tap_id) REFERENCES BaiTap(bai_tap_id),
    FOREIGN KEY (hoc_sinh_id) REFERENCES NguoiDung(nguoi_dung_id)
);

-- BẢNG TRẢ LỜI CÂU HỎI TRONG BÀI TẬP
CREATE TABLE TraLoiBaiTap (
    tra_loi_bai_tap_id INT AUTO_INCREMENT PRIMARY KEY,
    hoc_sinh_bai_tap_id INT NOT NULL,
    cau_hoi_id INT NOT NULL,
    dap_an_chon_id INT,
    la_dap_an_dung BOOLEAN,
    FOREIGN KEY (hoc_sinh_bai_tap_id) REFERENCES HocSinhLamBaiTap(hoc_sinh_bai_tap_id),
    FOREIGN KEY (cau_hoi_id) REFERENCES CauHoi(cau_hoi_id),
    FOREIGN KEY (dap_an_chon_id) REFERENCES DapAn(dap_an_id)
);