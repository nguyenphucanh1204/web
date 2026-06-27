<?php
class KhuyenmaiModel extends connectDB {
    
    // 1. Lấy danh sách (Có tìm kiếm theo Tên Mã)
    public function DanhSach($keyword = "") {
        $sql = "SELECT * FROM Khuyenmai";
        if($keyword != "") {
            $sql .= " WHERE TenMa LIKE '%$keyword%'";
        }
        $sql .= " ORDER BY MaKM DESC"; 
        return mysqli_query($this->con, $sql);
    }

    // 2. Thêm mới
    public function ThemMoi($ten, $tien, $soluong) {
        $sql = "INSERT INTO Khuyenmai (TenMa, SoTienGiam, SoLuong, TrangThai) 
                VALUES ('$ten', '$tien', '$soluong', 1)";
        return mysqli_query($this->con, $sql);
    }

    // 3. Cập nhật (Sửa)
    public function Sua($id, $ten, $tien, $soluong) {
        $sql = "UPDATE Khuyenmai SET TenMa='$ten', SoTienGiam='$tien', SoLuong='$soluong' WHERE MaKM='$id'";
        return mysqli_query($this->con, $sql);
    }

    // 4. Lấy thông tin 1 mã (Để đổ vào form sửa)
    public function GetByID($id) {
        return mysqli_query($this->con, "SELECT * FROM Khuyenmai WHERE MaKM='$id'");
    }

    // 5. Xóa (Giữ nguyên)
    public function XoaMa($id) {
        return mysqli_query($this->con, "DELETE FROM Khuyenmai WHERE MaKM='$id'");
    }

    // 6. Check trùng tên mã (Tránh tạo 2 mã giống nhau)
    public function CheckTrung($ten) {
        $kq = mysqli_query($this->con, "SELECT * FROM Khuyenmai WHERE TenMa='$ten'");
        return mysqli_num_rows($kq) > 0;
    }

    public function GetActiveCodes() {
        // Chỉ lấy những mã còn số lượng > 0
        $sql = "SELECT * FROM Khuyenmai WHERE SoLuong > 0 ORDER BY SoTienGiam DESC";
        return mysqli_query($this->con, $sql);
    }

    // --- BỔ SUNG: CÁC HÀM CẦN THIẾT CHO BÁN HÀNG (Nếu chưa có) ---
    // Hàm kiểm tra mã (Dùng khi bấm Áp dụng)
    public function CheckCode($code) {
        $sql = "SELECT * FROM Khuyenmai WHERE TenMa = '$code' AND SoLuong > 0";
        return mysqli_query($this->con, $sql);
    }

    // Hàm trừ số lượng khi thanh toán thành công
    public function TruSoLuong($makm) {
        $sql = "UPDATE Khuyenmai SET SoLuong = SoLuong - 1 WHERE MaKM = '$makm'";
        return mysqli_query($this->con, $sql);
    }
}
?>