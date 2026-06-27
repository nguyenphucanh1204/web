<?php
class HomeModel extends connectDB {
    
    // 1. Tính tổng doanh thu (Cộng cột TongTien)
    public function TongDoanhThu() {
        $sql = "SELECT SUM(TongTien) as Tong FROM Donhang";
        $kq = mysqli_query($this->con, $sql);
        $row = mysqli_fetch_array($kq);
        return $row['Tong'];
    }

    // 2. Đếm tổng số đơn hàng
    public function TongDonHang() {
        $sql = "SELECT COUNT(*) as SoLuong FROM Donhang";
        $kq = mysqli_query($this->con, $sql);
        $row = mysqli_fetch_array($kq);
        return $row['SoLuong'];
    }

    // 3. Đếm tổng sản phẩm đang kinh doanh
    public function TongSanPham() {
        $sql = "SELECT COUNT(*) as SoLuong FROM Sanpham";
        $kq = mysqli_query($this->con, $sql);
        $row = mysqli_fetch_array($kq);
        return $row['SoLuong'];
    }

    // 4. Đếm tổng khách hàng
    public function TongKhachHang() {
        $sql = "SELECT COUNT(*) as SoLuong FROM Khachhang";
        $kq = mysqli_query($this->con, $sql);
        $row = mysqli_fetch_array($kq);
        return $row['SoLuong'];
    }

    // 5. MỚI: Lấy doanh thu 7 ngày gần nhất để vẽ biểu đồ
    public function GetRevenueLast7Days() {
        // Lấy ngày và tổng tiền, nhóm theo ngày
        $sql = "SELECT DATE(NgayLap) as Ngay, SUM(TongTien) as DoanhThu 
                FROM Donhang 
                WHERE NgayLap >= DATE(NOW()) - INTERVAL 7 DAY 
                GROUP BY DATE(NgayLap) 
                ORDER BY DATE(NgayLap) ASC";
        return mysqli_query($this->con, $sql);
    }

    // 6. MỚI: Lấy 5 đơn hàng mới nhất
    public function GetNewOrders() {
        // Kết nối bảng Khachhang để lấy tên khách
        $sql = "SELECT d.MaHD, k.TenKH, d.TongTien, d.TrangThai, d.NgayLap 
                FROM Donhang d 
                LEFT JOIN Khachhang k ON d.MaKH = k.MaKH 
                ORDER BY d.MaHD DESC 
                LIMIT 5";
        return mysqli_query($this->con, $sql);
    }
}
?>
}
?>

