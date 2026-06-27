<div class="container" style="justify-content: center; padding: 50px 0;">
    <div style="background: white; padding: 40px; border-radius: 8px; width: 400px; box-shadow: 0 0 15px rgba(0,0,0,0.1);">
        <h2 style="text-align: center; color: #e31d2b; margin-top: 0;">ĐĂNG NHẬP</h2>
        
        <form action="<?php echo BASE_URL ?>Login/Authentication" method="POST">
            <div style="margin-bottom: 20px;">
                <label style="font-weight: bold; display: block; margin-bottom: 5px;">Tài khoản:</label>
                <input type="text" name="username" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box;" required placeholder="admin">
            </div>
            
            <div style="margin-bottom: 20px;">
                <label style="font-weight: bold; display: block; margin-bottom: 5px;">Mật khẩu:</label>
                <input type="password" name="password" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box;" required placeholder="123">
            </div>

            <button type="submit" name="btnLogin" style="width: 100%; padding: 12px; background: #e31d2b; color: white; border: none; font-weight: bold; border-radius: 4px; cursor: pointer;">ĐĂNG NHẬP NGAY</button>
        </form>
        
        <div style="text-align: center; margin-top: 15px;">
            <a href="<?php echo BASE_URL ?>Home" style="color: #666; text-decoration: none;">&larr; Quay lại trang chủ</a>
        </div>
    </div>
</div>