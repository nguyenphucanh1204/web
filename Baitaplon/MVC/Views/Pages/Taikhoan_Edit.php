<?php $row = $data['item']; ?>
<div class="card" style="width: 50%; margin: 0 auto;">
    <div class="card-header">
        <h3>Sửa thông tin tài khoản: <?php echo $row['username'] ?></h3>
    </div>
    <div class="card-body">
        <form action="http://localhost/Baitaplon/Taikhoan/Update/<?php echo $row['id'] ?>" method="POST">
            
            <div style="margin-bottom: 15px;">
                <label>Tên đăng nhập</label>
                <input type="text" value="<?php echo $row['username'] ?>" disabled style="width: 100%; padding: 8px; background: #eee; margin-top: 5px;">
            </div>
            
            <div style="margin-bottom: 15px;">
                <label>Mật khẩu mới</label>
                <input type="password" name="password" placeholder="Để trống nếu không muốn đổi mật khẩu" style="width: 100%; padding: 8px; margin-top: 5px;">
            </div>

            <div style="margin-bottom: 15px;">
                <label>Họ và tên</label>
                <input type="text" name="hoten" value="<?php echo $row['hoten'] ?>" required style="width: 100%; padding: 8px; margin-top: 5px;">
            </div>

            <div style="margin-bottom: 20px;">
                <label>Phân quyền</label>
                <select name="role" style="width: 100%; padding: 8px; margin-top: 5px;">
                    <option value="0" <?php if($row['role']==0) echo 'selected'; ?>>Nhân viên</option>
                    <option value="1" <?php if($row['role']==1) echo 'selected'; ?>>Quản trị viên (Admin)</option>
                </select>
            </div>

            <button type="submit" name="btnLuu" style="background: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer;">Cập nhật</button>
            <a href="http://localhost/Baitaplon/Taikhoan" style="margin-left: 10px; text-decoration: none; color: #666;">Hủy bỏ</a>
        </form>
    </div>
</div>