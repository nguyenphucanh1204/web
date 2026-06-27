<?php
class Donhang extends DB {
    public function ThemDonHang($ten, $sdt, $diachi, $email, $tongtien, $giamgia, $giohang){
        
        // 1. TẠO MÃ KHÁCH HÀNG (Dùng time() để sinh mã số không trùng)
        $maKH = time(); 

        // 2. THÊM KHÁCH HÀNG (Giờ đã có cột DiaChi)
        // Ta lưu tách biệt: Tên riêng, Địa chỉ riêng
        $sql_kh = "INSERT INTO khachhang (MaKH, TenKH, DienThoai, DiaChi) 
                   VALUES ('$maKH', '$ten', '$sdt', '$diachi')";
        
        if(!mysqli_query($this->con, $sql_kh)){
             echo "<script>alert('Lỗi thêm khách: " . mysqli_error($this->con) . "');</script>";
             return false;
        }

        // 3. THÊM ĐƠN HÀNG (Mặc định NV001 - Online)
        $ngaylap = date('Y-m-d H:i:s');
        $sql_dh = "INSERT INTO donhang (MaKH, MaNV, NgayLap, TongTien, GiamGia, TrangThai) 
                   VALUES ($maKH, 'NV001', '$ngaylap', $tongtien, $giamgia, 1)";
        
        if(mysqli_query($this->con, $sql_dh)){
            $maHD = mysqli_insert_id($this->con); 
            
            // 4. THÊM CHI TIẾT
            foreach($giohang as $item){
                $maSP = $item['id'];
                $soLuong = $item['qty'];
                $donGia = $item['price'];
                $sql_ct = "INSERT INTO chitietdonhang (MaHD, MaSP, SoLuong, DonGia) 
                           VALUES ($maHD, '$maSP', $soLuong, $donGia)";
                mysqli_query($this->con, $sql_ct);
            }
            return true; 
        }
        return false;
    }
}
?>