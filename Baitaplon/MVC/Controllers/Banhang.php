<?php
class Banhang extends controller {
    public $bhModel; public $kmModel;

    public function __construct() {
        $this->bhModel = $this->model("BanhangModel");
        $this->kmModel = $this->model("KhuyenmaiModel");
    }

    public function Get_data() {
        if(!isset($_SESSION['giohang'])) $_SESSION['giohang'] = [];

        $keyword = "";
        if(isset($_POST['btnTimKiem'])) {
            $keyword = $_POST['txtTimKiem'];
        }

        $this->view("Master", [
            "page" => "Banhang_V",
            "sp" => $this->bhModel->GetAllSanpham($keyword),
            "kh" => $this->bhModel->GetAllKhachhang(),
            "nv" => $this->bhModel->GetAllNhanvien(),
            "pt" => $this->bhModel->GetAllPhuongthuc(),
            
            // --- THÊM DÒNG NÀY: Lấy danh sách mã khuyến mãi ---
            "dskm" => $this->kmModel->GetActiveCodes(), 
            
            "keyword" => $keyword
        ]);
    }

    // --- 1. SỬA HÀM THÊM GIỎ HÀNG (Có check tồn kho) ---
    public function ThemGioHang($id) {
        // Lấy thông tin mới nhất của sản phẩm từ DB
        $kq = $this->bhModel->GetSanphamByID($id); 
        $row = mysqli_fetch_array($kq);
        
        if($row) {
            // Lấy số lượng đang có trong giỏ (nếu có)
            $current_qty = 0;
            if(isset($_SESSION['giohang'][$id])) {
                $current_qty = $_SESSION['giohang'][$id]['soluong'];
            }

            // KIỂM TRA: Nếu (Trong giỏ + 1) > Tồn kho thực tế -> Báo lỗi
            if (($current_qty + 1) > $row['SoLuongTon']) {
                echo "<script>
                        alert('Không thể thêm! Sản phẩm [".$row['TenSP']."] chỉ còn tồn ".$row['SoLuongTon']." cái.');
                        window.location.href='http://localhost/Baitaplon/Banhang';
                      </script>";
                return; // Dừng ngay, không chạy đoạn code thêm bên dưới
            }

            // Nếu đủ hàng thì thêm bình thường
            if(isset($_SESSION['giohang'][$id])) {
                $_SESSION['giohang'][$id]['soluong']++;
            } else {
                $_SESSION['giohang'][$id] = [
                    'id' => $row['MaSP'], 
                    'ten' => $row['TenSP'], 
                    'gia' => $row['GiaBan'], 
                    'hinh' => $row['HinhAnh'], 
                    'soluong' => 1
                ];
            }
        }
        header("Location: http://localhost/Baitaplon/Banhang");
    }

    public function XoaGioHang($id) { 
        if(isset($_SESSION['giohang'][$id])) unset($_SESSION['giohang'][$id]); 
        header("Location: http://localhost/Baitaplon/Banhang"); 
    }
    
    public function HuyDon() { 
        unset($_SESSION['giohang']); 
        unset($_SESSION['giamgia']); 
        header("Location: http://localhost/Baitaplon/Banhang"); 
    }
    
    public function ApDungMa() {
        if(isset($_POST['btnApDung'])) {
            $kq = $this->kmModel->CheckCode($_POST['txtMaGiamGia']);
            if(mysqli_num_rows($kq) > 0) {
                $row = mysqli_fetch_array($kq);
                $_SESSION['giamgia'] = ['MaKM'=>$row['MaKM'], 'TenMa'=>$row['TenMa'], 'SoTien'=>$row['SoTienGiam']];
                echo "<script>alert('Áp dụng thành công!'); window.location.href='http://localhost/Baitaplon/Banhang';</script>";
            } else {
                unset($_SESSION['giamgia']);
                echo "<script>alert('Mã sai!'); window.location.href='http://localhost/Baitaplon/Banhang';</script>";
            }
        }
    }

    // --- 2. SỬA HÀM THANH TOÁN (Check lại lần cuối) ---
    public function ThanhToan() {
        if(isset($_POST['btnThanhToan']) && !empty($_SESSION['giohang'])) {
            
            // A. KIỂM TRA TỒN KHO LẦN CUỐI (Cho chắc chắn)
            foreach($_SESSION['giohang'] as $item) {
                $kqCheck = $this->bhModel->GetSanphamByID($item['id']);
                $rowCheck = mysqli_fetch_array($kqCheck);
                
                if($item['soluong'] > $rowCheck['SoLuongTon']) {
                    echo "<script>
                            alert('Lỗi thanh toán: Sản phẩm [".$item['ten']."] hiện chỉ còn ".$rowCheck['SoLuongTon']." cái (Bạn mua ".$item['soluong']."). Vui lòng điều chỉnh lại!');
                            window.location.href='http://localhost/Baitaplon/Banhang';
                          </script>";
                    return; // Hủy thanh toán
                }
            }

            // B. Xử lý thanh toán
            $makh = $_POST['ddlKhachHang']; 
            $manv = $_POST['ddlNhanVien'];
            $mapt = isset($_POST['radPhuongThuc']) ? $_POST['radPhuongThuc'] : 1;
            
            $tong = 0; 
            foreach($_SESSION['giohang'] as $item) $tong += $item['gia']*$item['soluong'];
            
            $giam = isset($_SESSION['giamgia']) ? $_SESSION['giamgia']['SoTien'] : 0;
            if(isset($_SESSION['giamgia'])) $this->kmModel->TruSoLuong($_SESSION['giamgia']['MaKM']);
            
            $final = max(0, $tong - $giam);

            if($this->bhModel->TaoDonHang($makh, $manv, $final, $_SESSION['giohang'], $giam, $mapt)) {
                $this->model("KhachhangModel")->TichDiem($makh, $final);
                unset($_SESSION['giohang']); 
                unset($_SESSION['giamgia']);
                echo "<script>alert('Thanh toán thành công!'); window.location.href='http://localhost/Baitaplon/Banhang';</script>";
            }
        }
    }
}
?>