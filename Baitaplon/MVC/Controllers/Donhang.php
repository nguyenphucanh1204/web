<?php
class Donhang extends controller {
    public $dhModel;

    public function __construct() {
        $this->dhModel = $this->model("DonhangModel");
    }

    // 1. Hiển thị danh sách (ĐÃ CẬP NHẬT TÌM KIẾM)
    public function Get_data() {
        // Mặc định từ khóa rỗng
        $keyword = "";
        
        // Nếu người dùng nhấn nút Tìm kiếm
        if(isset($_POST['btnTimKiem'])) {
            $keyword = $_POST['txtTimKiem'];
        }

        $this->view("Master", [
            "page" => "Donhang_V",
            "dulieu" => $this->dhModel->DanhSach($keyword), // Gửi từ khóa xuống Model
            "keyword" => $keyword // Gửi lại từ khóa ra View để giữ trong ô input
        ]);
    }

    // 2. Xem chi tiết đơn hàng (GIỮ NGUYÊN CODE CỦA BẠN)
    public function Chitiet($mahd) {
        // Gọi Model lấy thông tin đơn hàng
        $dh = $this->dhModel->GetDonhangByID($mahd);
        
        // KIỂM TRA: Nếu tìm thấy đơn hàng (số dòng > 0) thì mới hiện
        if(mysqli_num_rows($dh) > 0) {
            
            // Lấy dữ liệu chi tiết sản phẩm
            $ct = $this->dhModel->ChiTiet($mahd);

            // Gọi View hiển thị
            $this->view("Master", [
                "page" => "ChitietDonhang_V",
                "donhang" => mysqli_fetch_array($dh),
                "chitiet" => $ct
            ]);
        } 
        else {
            // NẾU KHÔNG TÌM THẤY: Thông báo và đẩy về trang danh sách
            echo "<script>
                    alert('Đơn hàng không tồn tại hoặc đã bị xóa!');
                    window.location.href = 'http://localhost/Baitaplon/Donhang';
                  </script>";
        }
    }
}
?>