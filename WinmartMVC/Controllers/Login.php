<?php
class Login extends Controller {

    // 1. Hiển thị form đăng nhập
    function Index(){
        $this->view("Master", [
            "Page" => "Login_v"
        ]);
    }

    // 2. Xử lý khi bấm nút Đăng nhập
function Authentication(){
        if(isset($_POST['btnLogin'])){
            $u = $_POST['username'];
            $p = $_POST['password'];

            $userModel = $this->model("User");
            $kq = $userModel->CheckLogin($u, $p);

            if($kq){
                // 1. Lưu session
                $_SESSION['user_login'] = $kq;

                // 2. [QUAN TRỌNG] Chuyển hướng sang folder Baitaplon
                // Vì Baitaplon là thư mục riêng, nên ta điền trực tiếp link localhost
                header("Location: /Baitaplon/"); 
                exit;
            } else {
                echo "<script>alert('Sai tài khoản hoặc mật khẩu!'); window.location.href='".BASE_URL."Login';</script>";
            }
        }
    }
    // 3. Đăng xuất
    function Logout(){
        unset($_SESSION['user_login']);
        // Quay về trang chủ bán hàng
        header("Location: " . BASE_URL . "Home");
    }
}
?>