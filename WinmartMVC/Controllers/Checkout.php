<?php
class Checkout extends Controller {

    function Index(){
        // 1. Kiểm tra giỏ hàng
        if(!isset($_SESSION['cart']) || empty($_SESSION['cart'])){
            header("Location: " . BASE_URL . "Home");
            exit;
        }

        // 2. Gọi View (Không còn truyền danh sách Voucher nữa)
        $this->view("Master", [
            "Page" => "Checkout_v",
            "Cart" => $_SESSION['cart']
        ]);
    }

    // Đã xóa hàm SuDungVoucher và HuyVoucher vì không dùng nữa

    function Order(){
        if(isset($_POST['btnDatHang'])){
            // Lấy dữ liệu từ form
            $ten = $_POST['hoten'];
            $sdt = $_POST['sdt'];
            $diachi = $_POST['diachi'];
            $email = isset($_POST['email']) ? $_POST['email'] : ""; 
            $ghichu = isset($_POST['ghichu']) ? $_POST['ghichu'] : "";
            
            // 1. Tính toán tổng tiền
            $cart = $_SESSION['cart'];
            $tongtien = 0;
            foreach($cart as $item){
                $tongtien += $item['price'] * $item['qty'];
            }

            // Mặc định giảm giá bằng 0
            $giamgia = 0; 
            $tongTienSauGiam = $tongtien;

            // 2. Gọi Model lưu vào DB
            $model = $this->model("Donhang");
            
            // Gọi hàm ThemDonHang: Truyền $giamgia = 0 để khớp với Model đã sửa trước đó
            $kq = $model->ThemDonHang($ten, $sdt, $diachi, $email, $tongTienSauGiam, $giamgia, $cart);

            if($kq){
                // 3. CHUẨN BỊ DỮ LIỆU ĐỂ HIỂN THỊ TRANG SUCCESS
                $orderInfo = [
                    'khachhang' => [
                        'ten' => $ten,
                        'sdt' => $sdt,
                        'diachi' => $diachi,
                        'ghichu' => $ghichu
                    ],
                    'products' => $cart,
                    'bill' => [
                        'tam_tinh' => $tongtien,
                        'giam_gia' => 0,
                        'ten_voucher' => '',
                        'tong_tien' => $tongTienSauGiam
                    ]
                ];

                // 4. Xóa giỏ hàng (Và xóa session voucher nếu còn sót lại)
                unset($_SESSION['cart']);
                if(isset($_SESSION['voucher_applied'])) unset($_SESSION['voucher_applied']);
                
                // 5. Load View OrderSuccess
                $this->view("Master", [
                    "Page" => "OrderSuccess",
                    "OrderInfo" => $orderInfo
                ]);
            } else {
                echo "<script>alert('Đặt hàng thất bại!'); window.location.href = '".BASE_URL."Checkout';</script>";
            }
        }
    }
}
?>