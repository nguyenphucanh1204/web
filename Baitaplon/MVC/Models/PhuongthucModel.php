<?php
class PhuongthucModel extends connectDB {
    
    public function DanhSach() {
        return mysqli_query($this->con, "SELECT * FROM phuongthucthanhtoan ORDER BY MaPT DESC");
    }

    // SỬA: Thêm tham số $hinh
    public function Them($ten, $hinh) {
        $sql = "INSERT INTO phuongthucthanhtoan (TenPT, TrangThai, HinhAnh) VALUES ('$ten', 1, '$hinh')";
        return mysqli_query($this->con, $sql);
    }

    // SỬA: Thêm tham số $hinh
    public function Sua($id, $ten, $trangthai, $hinh) {
        if($hinh == "") {
            // Nếu không chọn ảnh mới -> Giữ nguyên ảnh cũ
            $sql = "UPDATE phuongthucthanhtoan SET TenPT='$ten', TrangThai='$trangthai' WHERE MaPT='$id'";
        } else {
            // Nếu có ảnh mới -> Cập nhật cả ảnh
            $sql = "UPDATE phuongthucthanhtoan SET TenPT='$ten', TrangThai='$trangthai', HinhAnh='$hinh' WHERE MaPT='$id'";
        }
        return mysqli_query($this->con, $sql);
    }

    // Các hàm Xóa, GetByID, CheckTrung giữ nguyên...
    public function Xoa($id) {
        return mysqli_query($this->con, "DELETE FROM phuongthucthanhtoan WHERE MaPT='$id'");
    }
    public function GetByID($id) {
        return mysqli_query($this->con, "SELECT * FROM phuongthucthanhtoan WHERE MaPT='$id'");
    }
    public function CheckTrung($ten) {
        $kq = mysqli_query($this->con, "SELECT * FROM phuongthucthanhtoan WHERE TenPT='$ten'");
        return mysqli_num_rows($kq) > 0;
    }
}
?>