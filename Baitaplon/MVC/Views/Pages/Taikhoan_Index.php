<style>
    .modal { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); }
    .modal-content { background-color: white; margin: 10% auto; padding: 20px; border-radius: 8px; width: 400px; box-shadow: 0 5px 15px rgba(0,0,0,0.3); position: relative; animation: slideDown 0.3s; }
    @keyframes slideDown { from {top: -50px; opacity: 0} to {top: 0; opacity: 1} }
    .close { float: right; font-size: 24px; font-weight: bold; cursor: pointer; color: #aaa; }
    .close:hover { color: black; }
    .form-group { margin-bottom: 15px; }
    .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
    .form-group input, .form-group select { width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
    .btn-green { background: #28a745; color: white; border: none; padding: 8px 15px; border-radius: 4px; cursor: pointer; text-decoration: none; display: inline-block;}
    .btn-blue { background: #007bff; color: white; border: none; padding: 5px 10px; border-radius: 3px; cursor: pointer; }
    .btn-red { background: #dc3545; color: white; border: none; padding: 5px 10px; border-radius: 3px; cursor: pointer; }
</style>

<div class="card">
    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
        <h3>Quản lý Tài khoản</h3>
        <button onclick="openModal('add')" class="btn-green"><i class="fas fa-plus"></i> Thêm tài khoản</button>
    </div>
    
    <div class="card-body">
        <table style="width: 100%; border-collapse: collapse;">
            <tr style="background: #f8f9fa; border-bottom: 2px solid #dee2e6;">
                <th style="padding: 10px; text-align: left;">Username</th>
                <th style="padding: 10px; text-align: left;">Họ tên</th>
                <th style="padding: 10px; text-align: left;">Quyền</th>
                <th style="padding: 10px; text-align: center;">Hành động</th>
            </tr>
            
            <?php if(isset($data['dulieu'])) {
                while($row = mysqli_fetch_array($data['dulieu'])){
            ?>
            <tr style="border-bottom: 1px solid #eee;">
                <td style="padding: 10px;"><b><?php echo $row['username'] ?></b></td>
                <td style="padding: 10px;"><?php echo $row['hoten'] ?></td>
                <td style="padding: 10px;">
                    <?php echo ($row['role'] == 1) ? '<span style="color:blue">Admin</span>' : 'User'; ?>
                </td>
                <td style="padding: 10px; text-align: center;">
                    <button class="btn-blue" onclick='openModal("edit", <?php echo json_encode($row); ?>)'>
                        <i class="fas fa-edit"></i>
                    </button>
                    
                    <?php if($row['id'] != 1) { ?>
                    <a href="http://localhost/Baitaplon/Taikhoan/Delete/<?php echo $row['id'] ?>" onclick="return confirm('Xóa tài khoản này?')" class="btn-red">
                        <i class="fas fa-trash"></i>
                    </a>
                    <?php } ?>
                </td>
            </tr>
            <?php }} ?>
        </table>
    </div>
</div>

<div id="myModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <h3 id="modalTitle" style="margin-top: 0; text-align: center; color: #e31d2b;">Thêm Tài khoản</h3>
        
        <form action="http://localhost/Baitaplon/Taikhoan/Save" method="POST">
            <input type="hidden" name="id" id="userId">

            <div class="form-group">
                <label>Tên đăng nhập (*)</label>
                <input type="text" name="username" id="username" required>
            </div>
            
            <div class="form-group">
                <label>Mật khẩu</label>
                <input type="password" name="password" id="password" placeholder="Nhập pass mới (Nếu sửa thì để trống là giữ nguyên)">
            </div>

            <div class="form-group">
                <label>Họ và tên (*)</label>
                <input type="text" name="hoten" id="hoten" required>
            </div>

            <div class="form-group">
                <label>Quyền hạn</label>
                <select name="role" id="role">
                    <option value="0">Nhân viên</option>
                    <option value="1">Quản trị viên (Admin)</option>
                </select>
            </div>

            <button type="submit" name="btnLuu" class="btn-green" style="width: 100%;">Lưu dữ liệu</button>
        </form>
    </div>
</div>

<script>
    var modal = document.getElementById("myModal");

    // Hàm mở modal
    function openModal(mode, data = null) {
        modal.style.display = "block";
        
        if (mode == 'add') {
            // Chế độ THÊM: Reset form trống
            document.getElementById("modalTitle").innerText = "Thêm Tài khoản mới";
            document.getElementById("userId").value = ""; // ID rỗng -> Controller biết là Thêm
            document.getElementById("username").value = "";
            document.getElementById("username").readOnly = false; // Cho phép nhập user
            document.getElementById("password").required = true;  // Bắt buộc nhập pass
            document.getElementById("hoten").value = "";
            document.getElementById("role").value = "0";
        } else {
            // Chế độ SỬA: Điền dữ liệu cũ vào form
            document.getElementById("modalTitle").innerText = "Cập nhật Tài khoản";
            document.getElementById("userId").value = data.id; // Gán ID -> Controller biết là Sửa
            document.getElementById("username").value = data.username;
            document.getElementById("username").readOnly = true; // Không cho sửa user
            document.getElementById("password").required = false; // Không bắt buộc nhập pass
            document.getElementById("hoten").value = data.hoten;
            document.getElementById("role").value = data.role;
        }
    }

    // Hàm đóng modal
    function closeModal() {
        modal.style.display = "none";
    }

    // Click ra ngoài thì đóng modal
    window.onclick = function(event) {
        if (event.target == modal) {
            closeModal();
        }
    }
</script>