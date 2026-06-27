<?php
class NhacungcapModel extends connectDB {
    
    // 1. Lấy danh sách (Có tìm kiếm theo Mã hoặc Tên)
    public function DanhSach($keyword = "") {
        $sql = "SELECT * FROM nhacungcap";
        if($keyword != "") {
            $sql .= " WHERE TenNCC LIKE '%$keyword%' OR MaCode LIKE '%$keyword%'";
        }
        return mysqli_query($this->con, $sql);
    }

    // 2. Check trùng mã (Quan trọng)
    public function CheckTrungMa($macode) {
        $sql = "SELECT * FROM nhacungcap WHERE MaCode = '$macode'";
        $kq = mysqli_query($this->con, $sql);
        return mysqli_num_rows($kq) > 0;
    }

    // 3. Thêm mới (Có Mã Code)
    public function Them($macode, $ten, $sdt, $diachi) {
        $sql = "INSERT INTO nhacungcap (MaCode, TenNCC, DienThoai, DiaChi) 
                VALUES ('$macode', '$ten', '$sdt', '$diachi')";
        return mysqli_query($this->con, $sql);
    }

    // 4. Sửa (Có Mã Code)
    public function Sua($id, $macode, $ten, $sdt, $diachi) {
        $sql = "UPDATE nhacungcap SET MaCode='$macode', TenNCC='$ten', DienThoai='$sdt', DiaChi='$diachi' 
                WHERE MaNCC='$id'";
        return mysqli_query($this->con, $sql);
    }

    // ... Các hàm cũ
    public function GetByID($id) {
        return mysqli_query($this->con, "SELECT * FROM nhacungcap WHERE MaNCC='$id'");
    }
    
    public function Xoa($id) {
        return mysqli_query($this->con, "DELETE FROM nhacungcap WHERE MaNCC='$id'");
    }
}
?>