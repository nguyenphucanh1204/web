<?php
class Khuyenmai extends DB {
    // Lấy tất cả voucher đang kích hoạt và còn số lượng
    public function GetVoucherHienCo(){
        $sql = "SELECT * FROM khuyenmai WHERE TrangThai = 1 AND SoLuong > 0";
        return mysqli_query($this->con, $sql);
    }

    // Lấy thông tin 1 voucher cụ thể để trừ số lượng sau khi mua
    public function GetVoucherByCode($code){
        $sql = "SELECT * FROM khuyenmai WHERE TenMa = '$code' AND TrangThai = 1 AND SoLuong > 0";
        $result = mysqli_query($this->con, $sql);
        return mysqli_fetch_assoc($result);
    }
    
    // Giảm số lượng voucher sau khi dùng
    public function GiamSoLuongVoucher($maKM){
        $sql = "UPDATE khuyenmai SET SoLuong = SoLuong - 1 WHERE MaKM = $maKM";
        mysqli_query($this->con, $sql);
    }
}
?>