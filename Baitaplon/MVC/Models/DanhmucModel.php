<?php
class DanhmucModel extends connectDB {
    
    // 1. Lấy danh sách (Tìm kiếm theo Tên hoặc Mã)
    public function DanhSach($keyword = "") {
        $sql = "SELECT * FROM Danhmuc";
        if($keyword != "") {
            $sql .= " WHERE TenDM LIKE '%$keyword%' OR MaCode LIKE '%$keyword%'";
        }
        return mysqli_query($this->con, $sql);
    }

    // 2. Thêm mới (Có Mã Code)
    public function Them($macode, $ten, $mota) {
        $sql = "INSERT INTO Danhmuc (MaCode, TenDM, MoTa) VALUES ('$macode', '$ten', '$mota')";
        return mysqli_query($this->con, $sql);
    }

    // 3. Cập nhật (Có Mã Code)
    public function Sua($id, $macode, $ten, $mota) {
        $sql = "UPDATE Danhmuc SET MaCode='$macode', TenDM='$ten', MoTa='$mota' WHERE MaDM='$id'";
        return mysqli_query($this->con, $sql);
    }

    // ... (Các hàm Xoa, GetByID giữ nguyên) ...
    public function Xoa($id) {
        return mysqli_query($this->con, "DELETE FROM Danhmuc WHERE MaDM='$id'");
    }
    public function GetByID($id) {
        return mysqli_query($this->con, "SELECT * FROM Danhmuc WHERE MaDM='$id'");
    }
    
    // Check trùng mã danh mục (Nếu cần kỹ hơn)
    public function CheckTrungMa($macode) {
        $kq = mysqli_query($this->con, "SELECT * FROM Danhmuc WHERE MaCode='$macode'");
        return mysqli_num_rows($kq) > 0;
    }
}
?>