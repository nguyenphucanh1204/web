<?php
class Cart extends Controller {

    function Index(){
        $this->view("Master", [
            "Page" => "Giohang_v",
            "Cart" => isset($_SESSION['cart']) ? $_SESSION['cart'] : []
        ]);
    }

    function Add($id){
        $model = $this->model("Sanpham");
        $sp = $model->GetSanphamChiTiet($id);

        if($sp){
            if(isset($_SESSION['cart'][$id])){
                $_SESSION['cart'][$id]['qty'] += 1;
            } else {
                $_SESSION['cart'][$id] = [
                    'id' => $sp['MaSP'],
                    'name' => $sp['TenSP'],
                    'image' => $sp['HinhAnh'],
                    'price' => $sp['GiaBan'],
                    'qty' => 1
                ];
            }
        }
        // [SỬA LỖI] Chuyển hướng tuyệt đối
        header("Location: " . BASE_URL . "Cart");
    }

    function Delete($id){
        if(isset($_SESSION['cart'][$id])){
            unset($_SESSION['cart'][$id]);
        }
        // [SỬA LỖI] Chuyển hướng tuyệt đối
        header("Location: " . BASE_URL . "Cart");
    }

    function Update($id, $qty){
        if(isset($_SESSION['cart'][$id])){
            $qty = intval($qty); // Ép kiểu số cho an toàn
            if($qty > 0){
                $_SESSION['cart'][$id]['qty'] = $qty;
            } else {
                unset($_SESSION['cart'][$id]);
            }
        }
        // [SỬA LỖI] Chuyển hướng tuyệt đối
        header("Location: " . BASE_URL . "Cart");
    }
}
?>