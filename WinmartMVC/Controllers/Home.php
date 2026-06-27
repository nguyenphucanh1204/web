<?php
class Home extends Controller {
    function Index(){
        // Gọi Model
        $teolab = $this->model("Sanpham");
        $dssp = $teolab->GetSanphamNoiBat();

        // Gọi View (Gửi kèm dữ liệu)
        $this->view("Master", [
            "Page" => "Home_page",
            "Sanpham" => $dssp
        ]);
    }
    // 1. Chức năng Tìm kiếm
    function TimKiem(){
        // Mặc định rỗng
        $dssp = []; 
        
        if(isset($_POST['keyword'])){
            $key = $_POST['keyword'];
            $model = $this->model("Sanpham");
            $dssp = $model->TimKiem($key);
        }

        // Tái sử dụng giao diện Home_page để hiển thị kết quả
        $this->view("Master", [
            "Page" => "Home_page",
            "Sanpham" => $dssp,
            "TieuDe" => "Kết quả tìm kiếm cho: '" . (isset($key) ? $key : "") . "'"
        ]);
    }

    // 2. Chức năng Lọc theo danh mục
    function Loc($idDM){
        $model = $this->model("Sanpham");
        $dssp = $model->GetTheoDanhMuc($idDM);

        $this->view("Master", [
            "Page" => "Home_page",
            "Sanpham" => $dssp,
            "TieuDe" => "Sản phẩm theo danh mục"
        ]);
    }
}
?>