<?php
class Login extends Controller {
    
    // Hàm xử lý đăng xuất
    public function Logout(){
        // 1. Hủy bỏ session
        session_unset();
        session_destroy();

        // 2. Chuyển hướng về trang đăng nhập của WinmartMVC (User)
        // Hoặc trang Login riêng của Admin tùy bạn
        header("Location: http://localhost/WinmartMVC/Login");
        exit;
    }
}
?>