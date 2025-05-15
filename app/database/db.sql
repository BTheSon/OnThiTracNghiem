-- BẢNG NGƯỜI DÙNG
CREATE TABLE NguoiDung (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ho_ten VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    mk VARCHAR(255),
    vai_tro ENUM('hs', 'gv'),
    anh VARCHAR(255)
);

-- BẢNG LỚP HỌC
CREATE TABLE LopHoc (
    id INT AUTO_INCREMENT PRIMARY KEY,
    gv_id INT,
    ma_lop VARCHAR(20) UNIQUE,
    ten_lop VARCHAR(100),
    mo_ta TEXT,
    ngay_tao DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (gv_id) REFERENCES NguoiDung(id)
);

-- BẢNG HỌC SINH THAM GIA LỚP
CREATE TABLE HocSinhLop (
    id INT AUTO_INCREMENT PRIMARY KEY,
    lh_id INT,      -- lớp học id
    hs_id INT,  -- học sinh id
    ngay_tham_gia DATETIME DEFAULT CURRENT_TIMESTAMP, -- ngày tham gia lớp học
    FOREIGN KEY (lh_id) REFERENCES LopHoc(id),
    FOREIGN KEY (hs_id) REFERENCES NguoiDung(id)
);

-- BẢNG THÔNG BÁO
CREATE TABLE ThongBao (
    id INT AUTO_INCREMENT PRIMARY KEY,
    lh_id INT,
    nguoi_gui_id INT,  -- người gủi thông báo
    tieu_de VARCHAR(200),
    noi_dung TEXT,
    ngay_tao DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (lh_id) REFERENCES LopHoc(id),
    FOREIGN KEY (nguoi_gui_id) REFERENCES NguoiDung(id) -- Đã sửa 'nguoi_dung' thành 'NguoiDung'
);

-- BẢNG TÀI LIỆU
CREATE TABLE TaiLieu (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nguoi_dang_id INT,
    lh_id INT,
    tieu_de VARCHAR(200),
    mo_ta TEXT,
    file_dir VARCHAR(255),
    ngay_dang DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (nguoi_dang_id) REFERENCES NguoiDung(id), -- Đã sửa 'nguoi_dung' thành 'NguoiDung'
    FOREIGN KEY (lh_id) REFERENCES LopHoc(id) -- Đã sửa 'lop_hoc' thành 'LopHoc'
);

-- BẢNG CÂU HỎI
CREATE TABLE CauHoi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nguoi_tao_id INT,
    mon_hoc VARCHAR(100),
    noi_dung TEXT,
    hinh VARCHAR(255),
    am_thanh VARCHAR(255),
    cong_thuc TEXT,
    ngay_tao DATETIME DEFAULT CURRENT_TIMESTAMP
);


-- BẢNG ĐÁP ÁN
CREATE TABLE DapAn (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cau_hoi_id INT,
    noi_dung TEXT,
    da_dung BOOLEAN,
    FOREIGN KEY (cau_hoi_id) REFERENCES CauHoi(id) -- Đã sửa 'cau_hoi' thành 'CauHoi'
);

-- BẢNG ĐỀ THI
CREATE TABLE DeThi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nguoi_tao_id INT,
    lh_id INT,
    tieu_de VARCHAR(200),
    mo_ta TEXT,
    tg_phut INT,
    tong_diem FLOAT,
    ngay_thi DATETIME,
    ngay_tao DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (nguoi_tao_id) REFERENCES NguoiDung(id), -- Đã sửa 'nguoi_dung' thành 'NguoiDung'
    FOREIGN KEY (lh_id) REFERENCES LopHoc(id) -- Đã sửa 'lop_hoc' thành 'LopHoc'
);

-- BẢNG CÂU HỎI TRONG ĐỀ THI
CREATE TABLE CauHoiDeThi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    de_id INT,
    cau_id INT,
    FOREIGN KEY (de_id) REFERENCES DeThi(id), -- Đã sửa 'de_thi' thành 'DeThi'
    FOREIGN KEY (cau_id) REFERENCES CauHoi(id) -- Đã sửa 'cau_hoi' thành 'CauHoi'
);

-- BẢNG HỌC SINH LÀM BÀI THI
CREATE TABLE HocSinhThi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    de_thi_id INT,
    hs_id INT,
    bat_dau DATETIME,
    ket_thuc DATETIME,
    diem FLOAT,
    FOREIGN KEY (de_thi_id) REFERENCES DeThi(id), -- Đã sửa 'de_thi' thành 'DeThi'
    FOREIGN KEY (hoc_sinh_id) REFERENCES NguoiDung(id) -- Đã sửa 'nguoi_dung' thành 'NguoiDung'
);

-- BẢNG TRẢ LỜI BÀI THI
CREATE TABLE TraLoiBaiThi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    hs_thi_id INT,
    cau_hoi_id INT, -- câu hỏi id
    da_id INT, -- đáp án chon
    dung BOOLEAN, -- là đáp án đúng
    FOREIGN KEY (hs_thi_id) REFERENCES HocSinhThi(id),
    FOREIGN KEY (cau_hoi_id) REFERENCES CauHoi(id),
    FOREIGN KEY (da_id) REFERENCES DapAn(id)
);
