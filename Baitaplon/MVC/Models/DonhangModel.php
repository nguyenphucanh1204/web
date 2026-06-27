<?php
class DonhangModel extends connectDB {
    
    // 1. Lấy danh sách đơn hàng (CÓ TÌM KIẾM)
    public function DanhSach($keyword = "") {
        // Kết nối bảng Donhang với Khachhang và Nhanvien để lấy tên
        $sql = "SELECT Donhang.*, Khachhang.TenKH, Nhanvien.HoTen 
                FROM Donhang
                LEFT JOIN Khachhang ON Donhang.MaKH = Khachhang.MaKH
                LEFT JOIN Nhanvien ON Donhang.MaNV = Nhanvien.MaNV";
        
        // Nếu có từ khóa tìm kiếm thì nối thêm điều kiện WHERE
        if($keyword != "") {
            $sql .= " WHERE Donhang.MaHD LIKE '%$keyword%' OR Khachhang.TenKH LIKE '%$keyword%'";
        }
        
        $sql .= " ORDER BY NgayLap DESC"; // Đơn mới nhất lên đầu
        return mysqli_query($this->con, $sql);
    }

    // 2. Lấy thông tin chi tiết của 1 đơn hàng (để xem món gì bên trong)
    public function ChiTiet($mahd) {
        $sql = "SELECT ChitietDonhang.*, Sanpham.TenSP, Sanpham.HinhAnh
                FROM ChitietDonhang
                LEFT JOIN Sanpham ON ChitietDonhang.MaSP = Sanpham.MaSP
                WHERE MaHD = '$mahd'";
        return mysqli_query($this->con, $sql);
    }

    // 3. Lấy thông tin 1 đơn hàng cụ thể (để in hóa đơn)
// File: DonhangModel.php

public function GetDonhangByID($mahd) {
    // Thêm Khachhang.DiaChi vào danh sách cột cần lấy (SELECT)
    $sql = "SELECT Donhang.*, Khachhang.TenKH, Khachhang.DienThoai, Khachhang.DiaChi, Nhanvien.HoTen 
            FROM Donhang
            LEFT JOIN Khachhang ON Donhang.MaKH = Khachhang.MaKH
            LEFT JOIN Nhanvien ON Donhang.MaNV = Nhanvien.MaNV
            WHERE MaHD = '$mahd'";
    return mysqli_query($this->con, $sql);
}
}
?>