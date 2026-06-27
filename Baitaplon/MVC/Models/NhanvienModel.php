<?php
class NhanvienModel extends connectDB {
    
    // 1. Lấy danh sách
    public function DanhSach($keyword = "") {
        $sql = "SELECT * FROM Nhanvien";
        if($keyword != "") {
            $sql .= " WHERE MaNV LIKE '%$keyword%' OR HoTen LIKE '%$keyword%'";
        }
        $sql .= " ORDER BY MaNV ASC";
        return mysqli_query($this->con, $sql);
    }

    // 2. Thêm mới (ĐÃ BỎ MD5)
    public function Them($manv, $ten, $email, $chucvu) {
        // Lưu trực tiếp mật khẩu, không mã hóa nữa
        $sql = "INSERT INTO Nhanvien (MaNV, HoTen, Email, ChucVu) 
                VALUES ('$manv', '$ten', '$email', '$chucvu')";
        return mysqli_query($this->con, $sql);
    }

    // 2. Check trùng mã (Quan trọng)
    public function CheckTrungMa($manv) {
        $sql = "SELECT * FROM nhanvien WHERE MaNV = '$manv'";
        $kq = mysqli_query($this->con, $sql);
        return mysqli_num_rows($kq) > 0;
    }

public function Sua($ma, $ten, $email, $chucvu) {
    $sql = "UPDATE nhanvien SET HoTen='$ten', Email='$email', ChucVu='$chucvu' 
            WHERE MaNV='$ma'";
    return mysqli_query($this->con, $sql);
}

    // Các hàm Xóa, GetByID giữ nguyên
    public function Xoa($id) {
        return mysqli_query($this->con, "DELETE FROM Nhanvien WHERE MaNV='$id'");
    }

    public function GetByID($id) {
        return mysqli_query($this->con, "SELECT * FROM Nhanvien WHERE MaNV='$id'");
    }
}
?>