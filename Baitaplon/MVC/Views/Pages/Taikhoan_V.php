<style>
    /* ... (Giữ nguyên CSS cũ) ... */
    /* CSS Card & Layout */
    .card-custom { background: #fff; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); border: 1px solid #e0e0e0; overflow: hidden; margin-bottom: 20px; }
    .card-header-custom { padding: 15px 20px; color: white; font-weight: bold; font-size: 16px; display: flex; align-items: center; gap: 10px; }
    .bg-gradient-green { background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); }
    .bg-blue { background: linear-gradient(to right, #1a237e, #3949ab); }
    .form-group { margin-bottom: 15px; position: relative; }
    .form-label { font-weight: 600; display: block; margin-bottom: 5px; color: #444; }
    .form-control { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; box-sizing: border-box; transition: 0.3s; }
    .form-control:focus { border-color: #38ef7d; box-shadow: 0 0 5px rgba(56, 239, 125, 0.4); outline: none; }
    .form-control[readonly] { background: #f9f9f9; color: #777; cursor: not-allowed; }
   
    /* CSS Icon Mắt */
    .password-wrapper { position: relative; }
    .toggle-password { position: absolute; right: 10px; top: 12px; cursor: pointer; color: #888; z-index: 10; font-size: 16px; }
    .toggle-password:hover { color: #11998e; }
    
    /* CSS Icon Mắt trong Bảng */
    .btn-eye-table { cursor: pointer; color: #1a237e; margin-left: 8px; transition: 0.3s; font-size: 14px; }
    .btn-eye-table:hover { color: #ff9800; transform: scale(1.2); }

    .btn-action { width: 100%; padding: 12px; border: none; border-radius: 6px; color: white; font-weight: bold; cursor: pointer; transition: 0.3s; }
    .btn-add { background: #11998e; } .btn-add:hover { background: #0e857b; }
    .btn-save { background: #28a745; } .btn-save:hover { background: #218838; }
    .search-container { display: flex; gap: 10px; margin-bottom: 15px; }
    .btn-search { background: #1a237e; color: white; border: none; padding: 0 20px; border-radius: 6px; cursor: pointer; }
    .table-cust { width: 100%; border-collapse: collapse; }
    .table-cust th { background: #f8f9fa; padding: 12px; text-align: left; color: #555; border-bottom: 2px solid #ddd; font-size: 13px; }
    .table-cust td { padding: 12px; border-bottom: 1px solid #eee; color: #333; font-size: 14px; vertical-align: middle; }
    .badge-role { padding: 5px 12px; border-radius: 20px; font-size: 11px; font-weight: bold; text-transform: uppercase; }
    .role-admin { background: #e3f2fd; color: #1565c0; border: 1px solid #90caf9; }
    .role-user { background: #f3e5f5; color: #7b1fa2; border: 1px solid #ce93d8; }
</style>

<?php
    $isEdit = false;
    $row_edit = [];
    if(isset($data['editData'])) {
        $isEdit = true;
        $row_edit = $data['editData'];
    }
?>

<div class="row" style="display:flex; gap: 25px; align-items: flex-start;">
    
    <div class="col-left" style="flex: 1; position: sticky; top: 20px;">
        <div class="card-custom">
            <div class="card-header-custom bg-gradient-green">
                <i class="fas fa-user-shield"></i> <?php echo $isEdit ? 'Cập Nhật Tài Khoản' : 'Thêm Tài Khoản Mới'; ?>
            </div>
            <div style="padding: 20px;">
                <form action="http://localhost/Baitaplon/Taikhoan/Save" method="post">
                    <input type="hidden" name="txtID" value="<?php echo $isEdit ? $row_edit['id'] : ''; ?>">
                    <div class="form-group">
                        <label class="form-label">Tên đăng nhập (Username):</label>
                        <input type="text" name="txtUser" class="form-control" required 
                               value="<?php echo $isEdit ? $row_edit['username'] : ''; ?>"
                               placeholder="VD: admin, nhanvien..."
                               <?php echo $isEdit ? 'readonly' : ''; ?>>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Mật khẩu:</label>
                        <div class="password-wrapper">
                            <input type="password" name="txtPass" class="form-control" id="inpPass"
                                   placeholder="<?php echo $isEdit ? 'Để trống nếu không đổi' : 'Nhập mật khẩu...'; ?>" 
                                   <?php echo $isEdit ? '' : 'required'; ?>>
                            <i class="fas fa-eye toggle-password" onclick="toggleInputPass(this)"></i>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Họ và tên:</label>
                        <input type="text" name="txtHoTen" class="form-control" required 
                               value="<?php echo $isEdit ? $row_edit['hoten'] : ''; ?>">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Phân quyền:</label>
                        <select name="ddlRole" class="form-control">
                            <option value="1" selected>Quản trị viên (Admin)</option>
                        </select>
                    </div>

                    <button type="submit" name="btnLuu" class="btn-action <?php echo $isEdit ? 'btn-save' : 'btn-add'; ?>">
                        <i class="fas <?php echo $isEdit ? 'fa-save' : 'fa-check-circle'; ?>"></i> 
                        <?php echo $isEdit ? 'LƯU THAY ĐỔI' : 'THÊM TÀI KHOẢN'; ?>
                    </button>
                    <?php if($isEdit) { ?>
                        <a href="http://localhost/Baitaplon/Taikhoan" class="btn-action" style="background:#6c757d; margin-top:10px; display:block; text-align:center; text-decoration:none;">Hủy bỏ</a>
                    <?php } ?>
                </form>
            </div>
        </div>
    </div>

    <div class="col-right" style="flex: 2;">
        <div class="card-custom">
            <div class="card-header-custom bg-blue">
                <i class="fas fa-users-cog"></i> Danh Sách Tài Khoản
            </div>
            <div style="padding: 20px;">
                
                
                <div class="search-container" style="justify-content: space-between; align-items: center;">
                    
                    <form action="http://localhost/Baitaplon/Taikhoan/Get_data" method="POST" style="display:flex; gap: 10px; flex: 1;">
                        <input type="text" name="txtTimKiem" class="form-control" 
                               placeholder="Nhập Username hoặc Họ tên..." 
                               value="<?php echo (isset($data['keyword']) && $data['keyword']!='') ? $data['keyword'] : '' ?>">
                        <button type="submit" name="btnTimKiem" class="btn-search"><i class="fas fa-search"></i> Tìm</button>
                        <a href="http://localhost/Baitaplon/Taikhoan" class="btn-search" style="background:#6c757d; display:flex; align-items:center; text-decoration:none;" title="Tải lại"><i class="fas fa-sync-alt"></i></a>
                    </form>

                    <div style="display:flex; gap: 10px;">
                        <a href="http://localhost/Baitaplon/Taikhoan/XuatExcel" class="btn-action btn-save" style="width:auto; padding: 0 15px; display:flex; align-items:center; text-decoration:none; background: #217346;">
                            <i class="fas fa-file-excel" style="margin-right:5px;"></i> Xuất
                        </a>
                        
                        <button onclick="document.getElementById('importModalTK').style.display='block'" class="btn-action btn-save" style="width:auto; padding: 0 15px; background: #1d6f42;">
                            <i class="fas fa-file-upload" style="margin-right:5px;"></i> Nhập
                        </button>
                    </div>
                </div>



                <table class="table-cust">
                    <thead>
                        <tr>
                            <th style="width: 40px;">ID</th>
                            <th>Username</th>
                            <th>Mật khẩu</th>
                            <th>Họ và Tên</th>
                            <th style="text-align:center">Quyền</th>
                            <th style="text-align:center">Thao Tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if(isset($data['dulieu']) && mysqli_num_rows($data['dulieu']) > 0){
                            $cnt = 0;
                            while($row = mysqli_fetch_array($data['dulieu'])){
                                $cnt++;
                                $passID = "pass_" . $cnt; // ID riêng cho từng dòng
                        ?>
                            <tr>
                                <td style="color:#888; text-align:center;"><?php echo $row['id']; ?></td>
                                <td style="font-weight:bold; color:#11998e;"><?php echo $row['username']; ?></td>
                                
                                <td style="font-family: monospace; font-weight: bold; color: #d32f2f;">
                                    <span id="<?php echo $passID; ?>">******</span>
                                    <i class="fas fa-eye btn-eye-table" 
                                       title="Xem mật khẩu"
                                       onclick="toggleTablePass('<?php echo $passID; ?>', '<?php echo $row['password']; ?>', this)">
                                    </i>
                                </td>

                                <td><?php echo $row['hoten']; ?></td>
                                <td style="text-align:center;">
                                    <?php if($row['role'] == 1) { ?>
                                        <span class="badge-role role-admin">Admin</span>
                                    <?php } else { ?>
                                        <span class="badge-role role-user">User</span>
                                    <?php } ?>
                                </td>
                                <td style="text-align:center;">
                                    <a href="http://localhost/Baitaplon/Taikhoan/Sua/<?php echo $row['id']; ?>" style="color:#ff9800; margin-right:10px; font-size:16px;" title="Sửa">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    
                                    <?php if($row['id'] != 1) { ?>
                                        <a href="http://localhost/Baitaplon/Taikhoan/Xoa/<?php echo $row['id']; ?>" onclick="return confirm('Xóa tài khoản <?php echo $row['username']; ?>?')" style="color:#dc3545; font-size:16px;" title="Xóa">
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
                                    <?php } else { ?>
                                        <span style="color:#ccc; cursor: not-allowed;"><i class="fas fa-trash-alt"></i></span>
                                    <?php } ?>
                                </td>
                            </tr>
                        <?php 
                            }
                        } else {
                            echo "<tr><td colspan='6' style='text-align:center; padding: 30px; color:#999;'>Không tìm thấy tài khoản nào!</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    // 1. Xử lý icon mắt ở FORM nhập liệu
    function toggleInputPass(icon) {
        var input = document.getElementById("inpPass");
        if (input.type === "password") {
            input.type = "text";
            icon.classList.remove("fa-eye");
            icon.classList.add("fa-eye-slash");
        } else {
            input.type = "password";
            icon.classList.remove("fa-eye-slash");
            icon.classList.add("fa-eye");
        }
    }

    // 2. Xử lý icon mắt ở DANH SÁCH (TABLE)
    function toggleTablePass(spanID, realPass, iconObj) {
        var spanObj = document.getElementById(spanID);
        
        if (spanObj.innerText === "******") {
            // Đang ẩn -> Hiện mật khẩu thật
            spanObj.innerText = realPass;
            iconObj.classList.remove("fa-eye");
            iconObj.classList.add("fa-eye-slash");
            iconObj.style.color = "#d32f2f";
        } else {
            // Đang hiện -> Ẩn lại
            spanObj.innerText = "******";
            iconObj.classList.remove("fa-eye-slash");
            iconObj.classList.add("fa-eye");
            iconObj.style.color = "#1a237e";
        }
    }
</script>


<div id="importModalTK" style="display:none; position: fixed; z-index: 999; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5);">
    <div style="background-color: #fefefe; margin: 10% auto; padding: 20px; border: 1px solid #888; width: 450px; border-radius: 8px; position: relative; box-shadow: 0 4px 8px rgba(0,0,0,0.2);">
        <span onclick="document.getElementById('importModalTK').style.display='none'" 
              style="color: #aaa; float: right; font-size: 28px; font-weight: bold; cursor: pointer;">&times;</span>
        
        <h3 style="color: #217346; margin-top: 0; border-bottom: 1px solid #eee; padding-bottom: 10px;">
            <i class="fas fa-file-excel"></i> Nhập Tài Khoản từ Excel
        </h3>
        
        <p style="font-size: 13px; color: #555; background: #e8f5e9; padding: 10px; border-radius: 4px;">
            <b><i class="fas fa-info-circle"></i> Hướng dẫn:</b><br>
            File Excel cần có 4 cột theo thứ tự:<br>
            <b>A: Username | B: Mật khẩu | C: Họ Tên | D: Quyền (1=Admin, 0=User)</b>
        </p>
        
        <form action="http://localhost/Baitaplon/Taikhoan/NhapExcel" method="post" enctype="multipart/form-data">
            <label style="display:block; margin-bottom:5px; font-weight:bold;">Chọn file Excel (.xlsx, .xls):</label>
            <input type="file" name="fileExcel" class="form-control" required accept=".xlsx, .xls" style="margin-bottom: 15px;">
            
            <button type="submit" name="btnNhapExcel" class="btn-action btn-save" style="background: #217346;">
                <i class="fas fa-upload"></i> Tải lên hệ thống
            </button>
        </form>
    </div>
</div>

<script>
    // Click ra ngoài thì đóng modal
    window.onclick = function(event) {
        var modal = document.getElementById('importModalTK');
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
</script>