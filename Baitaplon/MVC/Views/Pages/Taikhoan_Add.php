<div class="card" style="width: 50%; margin: 0 auto;">
    <div class="card-header">
        <h3>Thêm Tài khoản mới</h3>
    </div>
    <div class="card-body">
        <form action="http://localhost/Baitaplon/Taikhoan/Store" method="POST">
            <div style="margin-bottom: 15px;">
                <label>Tên đăng nhập (*)</label>
                <input type="text" name="username" required style="width: 100%; padding: 8px; margin-top: 5px;">
            </div>
            
            <div style="margin-bottom: 15px;">
                <label>Mật khẩu (*)</label>
                <input type="password" name="password" required style="width: 100%; padding: 8px; margin-top: 5px;">
            </div>

            <div style="margin-bottom: 15px;">
                <label>Họ và tên</label>
                <input type="text" name="hoten" required style="width: 100%; padding: 8px; margin-top: 5px;">
            </div>

            <div style="margin-bottom: 20px;">
                <label>Phân quyền</label>
                <select name="role" style="width: 100%; padding: 8px; margin-top: 5px;">
                    <option value="0">Nhân viên</option>
                    <option value="1">Quản trị viên (Admin)</option>
                </select>
            </div>

            <button type="submit" name="btnLuu" style="background: #28a745; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer;">Lưu lại</button>
            <a href="http://localhost/Baitaplon/Taikhoan" style="margin-left: 10px; text-decoration: none; color: #666;">Hủy bỏ</a>
        </form>
    </div>
</div>