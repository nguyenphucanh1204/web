<?php
class KhachhangModel extends connectDB {
    
    // 1. Lấy danh sách (Bổ sung tìm kiếm theo cả Mã KH)
    public function DanhSach($keyword = "") {
        $sql = "SELECT * FROM Khachhang";
        if($keyword != "") {
            $sql .= " WHERE TenKH LIKE '%$keyword%' OR DienThoai LIKE '%$keyword%' OR MaKH LIKE '%$keyword%'";
        }
        $sql .= " ORDER BY DiemTichLuy DESC, MaKH DESC"; 
        return mysqli_query($this->con, $sql);
    }

    // 2. Thêm mới (SỬA: Nhận thêm biến $id để insert)
    public function Them($id, $ten, $sdt) {
        // Chèn trực tiếp MaKH vào câu lệnh SQL
        $sql = "INSERT INTO Khachhang (MaKH, TenKH, DienThoai, DiemTichLuy) 
                VALUES ('$id', '$ten', '$sdt', 0)";
        return mysqli_query($this->con, $sql);
    }

    // 3. Sửa (Chỉ sửa Tên và SĐT)
    public function Sua($id, $ten, $sdt) {
        $sql = "UPDATE Khachhang SET TenKH='$ten', DienThoai='$sdt' WHERE MaKH='$id'";
        return mysqli_query($this->con, $sql);
    }

    // 4. Xóa
    public function Xoa($id) {
        return mysqli_query($this->con, "DELETE FROM Khachhang WHERE MaKH='$id'");
    }

    // 5. Lấy theo ID
    public function GetByID($id) {
        return mysqli_query($this->con, "SELECT * FROM Khachhang WHERE MaKH='$id'");
    }

    // 6. Kiểm tra trùng mã (MỚI)
    public function CheckTrung($id) {
        $kq = mysqli_query($this->con, "SELECT * FROM Khachhang WHERE MaKH='$id'");
        return mysqli_num_rows($kq) > 0;
    }

    // 7. Tích điểm
    public function TichDiem($makh, $tongtien) {
        $diem = floor($tongtien / 10000);
        if($diem > 0) {
            $sql = "UPDATE Khachhang SET DiemTichLuy = DiemTichLuy + $diem WHERE MaKH = '$makh'";
            mysqli_query($this->con, $sql);
        }
    }
}
?>