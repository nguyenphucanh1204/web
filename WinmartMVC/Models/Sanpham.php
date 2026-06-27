<?php
class Sanpham extends DB {
public function GetSanphamNoiBat(){
    // Lấy tất cả sản phẩm, sắp xếp từ mới nhất xuống cũ nhất
    $sql = "SELECT * FROM sanpham ORDER BY MaSP DESC";
    return mysqli_query($this->con, $sql);
}

    // [MỚI] Hàm lấy chi tiết 1 sản phẩm theo ID
    public function GetSanphamChiTiet($id){
        $sql = "SELECT * FROM sanpham WHERE MaSP = $id";
        $result = mysqli_query($this->con, $sql);
        return mysqli_fetch_assoc($result);
    }
    // Hàm tìm kiếm theo tên
    public function TimKiem($keyword){
        $sql = "SELECT * FROM sanpham WHERE TenSP LIKE '%$keyword%'";
        return mysqli_query($this->con, $sql);
    }

    // Hàm lọc theo Mã danh mục
    public function GetTheoDanhMuc($maDM){
        $sql = "SELECT * FROM sanpham WHERE MaDM = $maDM";
        return mysqli_query($this->con, $sql);
    }
}
?>