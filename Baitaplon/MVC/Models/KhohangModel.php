<?php
class KhohangModel extends connectDB {
    
    // --- CÁC HÀM CƠ BẢN ---
    public function GetAllNCC() { return mysqli_query($this->con, "SELECT * FROM Nhacungcap"); }
    public function GetAllSP() { return mysqli_query($this->con, "SELECT * FROM Sanpham"); }
    public function GetSP($id) { return mysqli_query($this->con, "SELECT * FROM Sanpham WHERE MaSP='$id'"); }
    
    // Tìm Mã SP theo Tên (Hỗ trợ nhập Excel)
    public function GetMaSPByTen($ten) {
        $ten = trim($ten);
        $sql = "SELECT MaSP FROM Sanpham WHERE TenSP = '$ten' LIMIT 1";
        $kq = mysqli_query($this->con, $sql);
        if(mysqli_num_rows($kq) > 0) {
            $row = mysqli_fetch_array($kq);
            return $row['MaSP'];
        }
        return null;
    }
    public function GetMaNCCByTen($ten) { /* ... (Giữ nguyên code cũ) ... */ 
        $sql = "SELECT MaNCC FROM Nhacungcap WHERE TenNCC LIKE '%$ten%' LIMIT 1";
        $kq = mysqli_query($this->con, $sql);
        if(mysqli_num_rows($kq) > 0) { $row = mysqli_fetch_array($kq); return $row['MaNCC']; }
        return null;
    }

    // --- 1. PHẦN NHẬP KHO (Giữ nguyên) ---
    public function NhapHang($mancc, $manv, $tongtien, $giohang_nhap) {
        $ngaynhap = date('Y-m-d H:i:s');
        $sql1 = "INSERT INTO Phieunhap (MaNCC, MaNV, NgayNhap, TongTien) VALUES ('$mancc', '$manv', '$ngaynhap', '$tongtien')";
        if(mysqli_query($this->con, $sql1)) {
            $mapn = mysqli_insert_id($this->con);
            foreach($giohang_nhap as $item) {
                $masp = $item['id']; $sl = $item['soluong']; $gia = $item['gia'];
                mysqli_query($this->con, "INSERT INTO ChitietPhieunhap VALUES ('$mapn', '$masp', '$sl', '$gia')");
                mysqli_query($this->con, "UPDATE Sanpham SET SoLuongTon = SoLuongTon + $sl WHERE MaSP = '$masp'");
            }
            return true;
        }
        return false;
    }

    public function GetLichSuNhap($keyword = "") {
        $searchID = preg_replace('/[^0-9]/', '', $keyword);
        $sql = "SELECT Phieunhap.MaPN, Phieunhap.NgayNhap, Phieunhap.TongTien, Nhacungcap.TenNCC,
                       GROUP_CONCAT(CONCAT('<div style=\"border-bottom:1px dashed #eee; padding:3px 0;\">','<b>', Sanpham.TenSP, '</b>',' - SL: <b style=\"color:blue\">', ChitietPhieunhap.SoLuong, '</b>',' - Giá: ', FORMAT(ChitietPhieunhap.DonGia, 0), 'đ','</div>') SEPARATOR '') as ChiTietNhap
                FROM Phieunhap
                LEFT JOIN Nhacungcap ON Phieunhap.MaNCC = Nhacungcap.MaNCC
                LEFT JOIN ChitietPhieunhap ON Phieunhap.MaPN = ChitietPhieunhap.MaPN
                LEFT JOIN Sanpham ON ChitietPhieunhap.MaSP = Sanpham.MaSP";
        if($keyword != "") $sql .= " WHERE Phieunhap.MaPN LIKE '%$searchID%' OR Nhacungcap.TenNCC LIKE '%$keyword%'";
        $sql .= " GROUP BY Phieunhap.MaPN ORDER BY Phieunhap.NgayNhap DESC";
        return mysqli_query($this->con, $sql);
    }

    public function ImportPhieuLichSuFull($mancc, $manv, $ngay, $tongtien, $chitiet_array) {
        $sql = "INSERT INTO Phieunhap (MaNCC, MaNV, NgayNhap, TongTien) VALUES ('$mancc', '$manv', '$ngay', '$tongtien')";
        if(mysqli_query($this->con, $sql)) {
            $mapn = mysqli_insert_id($this->con);
            foreach($chitiet_array as $item) {
                $masp = $item['masp']; $sl = $item['sl']; $gia = $item['gia'];
                mysqli_query($this->con, "INSERT INTO ChitietPhieunhap VALUES ('$mapn', '$masp', '$sl', '$gia')");
            }
            return true;
        } return false;
    }

    // =================================================================================
    // --- 2. PHẦN KIỂM KÊ (MỚI CẬP NHẬT) ---
    // =================================================================================

    // Lưu phiếu kiểm
    public function LuuKiemKho($manv, $ghichu, $giohang_kiem) {
        $ngay = date('Y-m-d H:i:s');
        $sql1 = "INSERT INTO phieukiem (MaNV, NgayKiem, GhiChu) VALUES ('$manv', '$ngay', '$ghichu')";
        if(mysqli_query($this->con, $sql1)) {
            $mapk = mysqli_insert_id($this->con);
            foreach($giohang_kiem as $item) {
                $masp = $item['id'];
                $tonmay = $item['tonmay'];
                $tonthuc = $item['tonthuc'];
                $lydo = $item['lydo'];
                mysqli_query($this->con, "INSERT INTO chitietkiem VALUES ('$mapk', '$masp', '$tonmay', '$tonthuc', '$lydo')");
                // Cập nhật lại tồn kho theo thực tế
                mysqli_query($this->con, "UPDATE Sanpham SET SoLuongTon = '$tonthuc' WHERE MaSP = '$masp'");
            }
            return true;
        }
        return false;
    }

    // Lấy lịch sử kiểm kê (Kèm chi tiết lệch kho)
    public function GetLichSuKiemKe($keyword = "") {
        $searchID = preg_replace('/[^0-9]/', '', $keyword);
        
        $sql = "SELECT phieukiem.MaPK, phieukiem.NgayKiem, phieukiem.GhiChu, Nhanvien.HoTen,
                       GROUP_CONCAT(
                           CONCAT(
                               '<div style=\"border-bottom:1px dashed #eee; padding:3px 0;\">',
                               '<b>', Sanpham.TenSP, '</b>',
                               ' | Máy: ', chitietkiem.TonMay,
                               ' -> <b style=\"color:blue\">Thực: ', chitietkiem.TonThuc, '</b>',
                               ' <i style=\"color:#777\">(', chitietkiem.LyDo, ')</i>',
                               '</div>'
                           ) 
                       SEPARATOR '') as ChiTietKiem
                FROM phieukiem
                LEFT JOIN Nhanvien ON phieukiem.MaNV = Nhanvien.MaNV
                LEFT JOIN chitietkiem ON phieukiem.MaPK = chitietkiem.MaPK
                LEFT JOIN Sanpham ON chitietkiem.MaSP = Sanpham.MaSP";
        
        if($keyword != "") {
            $sql .= " WHERE phieukiem.MaPK LIKE '%$searchID%' OR phieukiem.GhiChu LIKE '%$keyword%'";
        }
        
        $sql .= " GROUP BY phieukiem.MaPK ORDER BY phieukiem.NgayKiem DESC";
        return mysqli_query($this->con, $sql);
    }

    // Import Excel Kiểm Kê (Chỉ lưu lịch sử)
    public function ImportPhieuKiemFull($manv, $ngay, $ghichu, $chitiet_array) {
        $sql = "INSERT INTO phieukiem (MaNV, NgayKiem, GhiChu) VALUES ('$manv', '$ngay', '$ghichu')";
        if(mysqli_query($this->con, $sql)) {
            $mapk = mysqli_insert_id($this->con);
            foreach($chitiet_array as $item) {
                $masp = $item['masp'];
                $tonmay = $item['may'];
                $tonthuc = $item['thuc'];
                $lydo = $item['lydo'];
                mysqli_query($this->con, "INSERT INTO chitietkiem VALUES ('$mapk', '$masp', '$tonmay', '$tonthuc', '$lydo')");
            }
            return true;
        }
        return false;
    }
}
?>