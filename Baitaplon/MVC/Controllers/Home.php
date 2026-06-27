<?php
class Home extends controller {
    public $homeModel;

    public function __construct() {
        $this->homeModel = $this->model("HomeModel");
    }

    public function Get_data() {
        // 1. Chuẩn bị dữ liệu cho biểu đồ
        $chartData = $this->homeModel->GetRevenueLast7Days();
        $labels = [];
        $values = [];
        
        while($row = mysqli_fetch_array($chartData)){
            $labels[] = date('d/m', strtotime($row['Ngay'])); // Định dạng ngày: 20/12
            $values[] = $row['DoanhThu'];
        }

        // 2. Gửi sang View
        $this->view("Master", [
            "page" => "Home_V",
            // Số liệu thống kê cũ
            "doanhthu" => $this->homeModel->TongDoanhThu(),
            "donhang" => $this->homeModel->TongDonHang(),
            "sanpham" => $this->homeModel->TongSanPham(),
            "khachhang" => $this->homeModel->TongKhachHang(),
            
            // Dữ liệu MỚI
            "chartLabels" => json_encode($labels), // Chuyển sang JSON cho JS đọc
            "chartValues" => json_encode($values),
            "newOrders" => $this->homeModel->GetNewOrders()
        ]);
    }
}
?>