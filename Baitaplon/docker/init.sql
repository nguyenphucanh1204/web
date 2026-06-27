-- =============================================
-- DATABASE: Baitaplon
-- Hệ thống Quản lý Siêu thị WinMart
-- File này tự động chạy khi Docker khởi tạo lần đầu
-- =============================================

SET NAMES utf8mb4;
SET CHARACTER SET utf8mb4;

-- 1. Bảng Danh Mục
CREATE TABLE IF NOT EXISTS `Danhmuc` (
    `MaDM` INT AUTO_INCREMENT PRIMARY KEY,
    `MaCode` VARCHAR(20) DEFAULT NULL,
    `TenDM` VARCHAR(100) NOT NULL,
    `MoTa` TEXT DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2. Bảng Nhà Cung Cấp
CREATE TABLE IF NOT EXISTS `Nhacungcap` (
    `MaNCC` INT AUTO_INCREMENT PRIMARY KEY,
    `MaCode` VARCHAR(20) DEFAULT NULL,
    `TenNCC` VARCHAR(150) NOT NULL,
    `DienThoai` VARCHAR(20) DEFAULT NULL,
    `DiaChi` TEXT DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 3. Bảng Sản Phẩm
CREATE TABLE IF NOT EXISTS `Sanpham` (
    `MaSP` VARCHAR(20) PRIMARY KEY,
    `TenSP` VARCHAR(200) NOT NULL,
    `MaDM` INT DEFAULT NULL,
    `MaNCC` INT DEFAULT NULL,
    `GiaBan` DECIMAL(15,0) DEFAULT 0,
    `SoLuongTon` INT DEFAULT 0,
    `HinhAnh` VARCHAR(255) DEFAULT 'no-image.jpg',
    `HanSuDung` DATE DEFAULT NULL,
    FOREIGN KEY (`MaDM`) REFERENCES `Danhmuc`(`MaDM`) ON DELETE SET NULL,
    FOREIGN KEY (`MaNCC`) REFERENCES `Nhacungcap`(`MaNCC`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 4. Bảng Khách Hàng
CREATE TABLE IF NOT EXISTS `Khachhang` (
    `MaKH` VARCHAR(20) PRIMARY KEY,
    `TenKH` VARCHAR(100) NOT NULL,
    `DienThoai` VARCHAR(20) DEFAULT NULL,
    `DiaChi` TEXT DEFAULT NULL,
    `DiemTichLuy` INT DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 5. Bảng Nhân Viên
CREATE TABLE IF NOT EXISTS `Nhanvien` (
    `MaNV` VARCHAR(20) PRIMARY KEY,
    `HoTen` VARCHAR(100) NOT NULL,
    `Email` VARCHAR(100) DEFAULT NULL,
    `ChucVu` VARCHAR(50) DEFAULT 'Nhân viên'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 6. Bảng Phương Thức Thanh Toán
CREATE TABLE IF NOT EXISTS `phuongthucthanhtoan` (
    `MaPT` INT AUTO_INCREMENT PRIMARY KEY,
    `TenPT` VARCHAR(100) NOT NULL,
    `TrangThai` TINYINT DEFAULT 1,
    `HinhAnh` VARCHAR(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 7. Bảng Đơn Hàng
CREATE TABLE IF NOT EXISTS `Donhang` (
    `MaHD` INT AUTO_INCREMENT PRIMARY KEY,
    `MaKH` VARCHAR(20) DEFAULT NULL,
    `MaNV` VARCHAR(20) DEFAULT NULL,
    `NgayLap` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `TongTien` DECIMAL(15,0) DEFAULT 0,
    `GiamGia` DECIMAL(15,0) DEFAULT 0,
    `MaPT` INT DEFAULT NULL,
    `TrangThai` TINYINT DEFAULT 1,
    FOREIGN KEY (`MaKH`) REFERENCES `Khachhang`(`MaKH`) ON DELETE SET NULL,
    FOREIGN KEY (`MaNV`) REFERENCES `Nhanvien`(`MaNV`) ON DELETE SET NULL,
    FOREIGN KEY (`MaPT`) REFERENCES `phuongthucthanhtoan`(`MaPT`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 8. Bảng Chi Tiết Đơn Hàng
CREATE TABLE IF NOT EXISTS `ChitietDonhang` (
    `MaHD` INT NOT NULL,
    `MaSP` VARCHAR(20) NOT NULL,
    `SoLuong` INT DEFAULT 1,
    `DonGia` DECIMAL(15,0) DEFAULT 0,
    PRIMARY KEY (`MaHD`, `MaSP`),
    FOREIGN KEY (`MaHD`) REFERENCES `Donhang`(`MaHD`) ON DELETE CASCADE,
    FOREIGN KEY (`MaSP`) REFERENCES `Sanpham`(`MaSP`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 9. Bảng Khuyến Mãi
CREATE TABLE IF NOT EXISTS `Khuyenmai` (
    `MaKM` INT AUTO_INCREMENT PRIMARY KEY,
    `TenMa` VARCHAR(50) NOT NULL,
    `SoTienGiam` DECIMAL(15,0) DEFAULT 0,
    `SoLuong` INT DEFAULT 100,
    `TrangThai` TINYINT DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 10. Bảng Phiếu Nhập
CREATE TABLE IF NOT EXISTS `Phieunhap` (
    `MaPN` INT AUTO_INCREMENT PRIMARY KEY,
    `MaNCC` INT DEFAULT NULL,
    `MaNV` VARCHAR(20) DEFAULT NULL,
    `NgayNhap` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `TongTien` DECIMAL(15,0) DEFAULT 0,
    FOREIGN KEY (`MaNCC`) REFERENCES `Nhacungcap`(`MaNCC`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 11. Bảng Chi Tiết Phiếu Nhập
CREATE TABLE IF NOT EXISTS `ChitietPhieunhap` (
    `MaPN` INT NOT NULL,
    `MaSP` VARCHAR(20) NOT NULL,
    `SoLuong` INT DEFAULT 1,
    `DonGia` DECIMAL(15,0) DEFAULT 0,
    PRIMARY KEY (`MaPN`, `MaSP`),
    FOREIGN KEY (`MaPN`) REFERENCES `Phieunhap`(`MaPN`) ON DELETE CASCADE,
    FOREIGN KEY (`MaSP`) REFERENCES `Sanpham`(`MaSP`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 12. Bảng Phiếu Kiểm
CREATE TABLE IF NOT EXISTS `phieukiem` (
    `MaPK` INT AUTO_INCREMENT PRIMARY KEY,
    `MaNV` VARCHAR(20) DEFAULT NULL,
    `NgayKiem` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `GhiChu` TEXT DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 13. Bảng Chi Tiết Kiểm
CREATE TABLE IF NOT EXISTS `chitietkiem` (
    `MaPK` INT NOT NULL,
    `MaSP` VARCHAR(20) NOT NULL,
    `TonMay` INT DEFAULT 0,
    `TonThuc` INT DEFAULT 0,
    `LyDo` TEXT DEFAULT NULL,
    PRIMARY KEY (`MaPK`, `MaSP`),
    FOREIGN KEY (`MaPK`) REFERENCES `phieukiem`(`MaPK`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 14. Bảng Tài Khoản
CREATE TABLE IF NOT EXISTS `taikhoan` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(50) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `hoten` VARCHAR(100) NOT NULL,
    `role` TINYINT DEFAULT 0 COMMENT '0=User, 1=Admin'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- =============================================
-- DỮ LIỆU MẪU (Để hệ thống hoạt động được ngay)
-- =============================================

-- Tài khoản Admin mặc định
INSERT INTO `taikhoan` (`id`, `username`, `password`, `hoten`, `role`) VALUES
(1, 'admin', 'admin', 'Quản Trị Viên', 1);

-- Nhân viên mặc định (Cần có vì code hardcode MaNV=1 ở Khohang)
INSERT INTO `Nhanvien` (`MaNV`, `HoTen`, `Email`, `ChucVu`) VALUES
('1', 'Nguyễn Văn A', 'nva@winmart.vn', 'Quản lý');

-- Khách hàng mặc định
INSERT INTO `Khachhang` (`MaKH`, `TenKH`, `DienThoai`, `DiemTichLuy`) VALUES
('KH01', 'Khách lẻ', '0000000000', 0);

-- Phương thức thanh toán mặc định
INSERT INTO `phuongthucthanhtoan` (`TenPT`, `TrangThai`, `HinhAnh`) VALUES
('Tiền mặt', 1, NULL),
('Chuyển khoản', 1, 'QRmb.jpg'),
('Momo', 1, 'QRmomo.jpg');

-- Danh mục mẫu
INSERT INTO `Danhmuc` (`MaCode`, `TenDM`, `MoTa`) VALUES
('DM01', 'Thực phẩm', 'Đồ ăn, thức uống, bánh kẹo'),
('DM02', 'Đồ gia dụng', 'Dụng cụ nhà bếp, vệ sinh'),
('DM03', 'Đồ điện tử', 'Thiết bị điện tử, phụ kiện');

-- Nhà cung cấp mẫu
INSERT INTO `Nhacungcap` (`MaCode`, `TenNCC`, `DienThoai`, `DiaChi`) VALUES
('NCC01', 'Công ty TNHH Hồng Mạnh', '0901234567', 'Hà Nội'),
('NCC02', 'Công ty CP Thực Phẩm Miền Nam', '0912345678', 'TP. Hồ Chí Minh');
