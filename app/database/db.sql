-- BẢNG NGƯỜI DÙNG
CREATE TABLE NguoiDung (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ho_ten VARCHAR(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
    email VARCHAR(100) UNIQUE,
    mk VARCHAR(255),
    vai_tro ENUM('hs', 'gv'),
    anh VARCHAR(255)
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- BẢNG LỚP HỌC
CREATE TABLE LopHoc (
    id INT AUTO_INCREMENT PRIMARY KEY,
    gv_id INT,
    ma_lop VARCHAR(20) UNIQUE,
    ten_lop VARCHAR(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
    mo_ta TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
    ngay_tao DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (gv_id) REFERENCES NguoiDung(id)
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- BẢNG HỌC SINH THAM GIA LỚP
CREATE TABLE HocSinhLop (
    id INT AUTO_INCREMENT PRIMARY KEY,
    lh_id INT,
    hs_id INT,
    ngay_tham_gia DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (lh_id) REFERENCES LopHoc(id),
    FOREIGN KEY (hs_id) REFERENCES NguoiDung(id)
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- BẢNG THÔNG BÁO
CREATE TABLE ThongBao (
    id INT AUTO_INCREMENT PRIMARY KEY,
    lh_id INT,
    nguoi_gui_id INT,
    tieu_de VARCHAR(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
    noi_dung TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
    ngay_tao DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (lh_id) REFERENCES LopHoc(id),
    FOREIGN KEY (nguoi_gui_id) REFERENCES NguoiDung(id)
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- BẢNG TÀI LIỆU
CREATE TABLE TaiLieu (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nguoi_dang_id INT,
    lh_id INT,
    tieu_de VARCHAR(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
    mo_ta TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
    file_dir VARCHAR(255),
    ngay_dang DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (nguoi_dang_id) REFERENCES NguoiDung(id),
    FOREIGN KEY (lh_id) REFERENCES LopHoc(id)
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- BẢNG CÂU HỎI
CREATE TABLE CauHoi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nguoi_tao_id INT,
    do_kho INT,
    noi_dung TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
    hinh VARCHAR(255),
    am_thanh VARCHAR(255),
    cong_thuc TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
    ngay_tao DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (nguoi_tao_id) REFERENCES NguoiDung(id)
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- BẢNG ĐÁP ÁN
CREATE TABLE DapAn (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cau_hoi_id INT,
    noi_dung TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
    da_dung BOOLEAN,
    FOREIGN KEY (cau_hoi_id) REFERENCES CauHoi(id)
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- BẢNG ĐỀ THI
CREATE TABLE DeThi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nguoi_tao_id INT,
    lh_id INT,
    tieu_de VARCHAR(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
    mo_ta TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
    tg_phut INT,
    tong_diem FLOAT,
    ngay_thi DATETIME,
    ngay_tao DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (nguoi_tao_id) REFERENCES NguoiDung(id),
    FOREIGN KEY (lh_id) REFERENCES LopHoc(id)
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- BẢNG CÂU HỎI TRONG ĐỀ THI
CREATE TABLE CauHoiDeThi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    de_id INT,
    cau_id INT,
    FOREIGN KEY (de_id) REFERENCES DeThi(id),
    FOREIGN KEY (cau_id) REFERENCES CauHoi(id)
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- BẢNG HỌC SINH LÀM BÀI THI
CREATE TABLE HocSinhThi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    de_thi_id INT,
    hs_id INT,
    bat_dau DATETIME,
    ket_thuc DATETIME,
    diem FLOAT,
    FOREIGN KEY (de_thi_id) REFERENCES DeThi(id),
    FOREIGN KEY (hs_id) REFERENCES NguoiDung(id)
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- BẢNG TRẢ LỜI BÀI THI
CREATE TABLE TraLoiBaiThi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    hs_thi_id INT,
    cau_hoi_id INT,
    da_id INT,
    dung BOOLEAN,
    FOREIGN KEY (hs_thi_id) REFERENCES HocSinhThi(id),
    FOREIGN KEY (cau_hoi_id) REFERENCES CauHoi(id),
    FOREIGN KEY (da_id) REFERENCES DapAn(id)
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
