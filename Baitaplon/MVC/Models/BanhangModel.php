<?php
class BanhangModel extends connectDB {
    
    // SỬA: Thêm tham số $keyword
    public function GetAllSanpham($keyword = "") {
        $sql = "SELECT * FROM Sanpham WHERE SoLuongTon > 0";
        if($keyword != "") {
            $sql .= " AND (TenSP LIKE '%$keyword%' OR MaSP LIKE '%$keyword%')";
        }
        return mysqli_query($this->con, $sql);
    }

    // Các hàm khác giữ nguyên
    public function GetAllKhachhang() { return mysqli_query($this->con, "SELECT * FROM Khachhang"); }
    public function GetAllNhanvien() { return mysqli_query($this->con, "SELECT * FROM Nhanvien"); }
    public function GetSanphamByID($id) { return mysqli_query($this->con, "SELECT * FROM Sanpham WHERE MaSP='$id'"); }
    public function GetAllPhuongthuc() { return mysqli_query($this->con, "SELECT * FROM phuongthucthanhtoan WHERE TrangThai = 1"); }
    
    public function TaoDonHang($makh, $manv, $tongtien, $giohang, $tiengiam, $mapt) {
        $ngaylap = date('Y-m-d H:i:s');
        $sql_donhang = "INSERT INTO Donhang (MaKH, MaNV, NgayLap, TongTien, GiamGia, MaPT, TrangThai) 
                        VALUES ('$makh', '$manv', '$ngaylap', '$tongtien', '$tiengiam', '$mapt', 1)";
        $kq = mysqli_query($this->con, $sql_donhang);
        if($kq) {
            $mahd = mysqli_insert_id($this->con);
            foreach($giohang as $item) {
                $masp = $item['id']; $sl = $item['soluong']; $gia = $item['gia'];
                mysqli_query($this->con, "INSERT INTO ChitietDonhang VALUES ('$mahd', '$masp', '$sl', '$gia')");
                mysqli_query($this->con, "UPDATE Sanpham SET SoLuongTon = SoLuongTon - $sl WHERE MaSP = '$masp'");
            }
            return true;
        }
        return false;
    }
}
?>