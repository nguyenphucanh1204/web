<style>
    /* CSS Card & Form */
    .card-custom {
        background: #fff; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        border: 1px solid #e0e0e0; overflow: hidden; margin-bottom: 20px;
    }
    .card-header-custom {
        padding: 15px 20px; color: white; font-weight: bold; font-size: 16px;
        display: flex; align-items: center; gap: 10px;
    }
    .bg-gradient-primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
    .bg-blue { background: linear-gradient(to right, #1a237e, #3949ab); }

    .form-group { margin-bottom: 15px; }
    .form-label { font-weight: 600; display: block; margin-bottom: 5px; color: #444; }
    .form-control {
        width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;
        box-sizing: border-box; transition: 0.3s;
    }
    .form-control[readonly] { background-color: #f2f2f2; color: #666; cursor: not-allowed; }
    .form-control:focus { border-color: #764ba2; box-shadow: 0 0 5px rgba(118, 75, 162, 0.4); outline: none; }

    /* Buttons */
    .btn-action {
        width: 100%; padding: 12px; border: none; border-radius: 6px; color: white; font-weight: bold;
        cursor: pointer; transition: 0.3s;
    }
    .btn-add { background: #764ba2; color: #fff; } .btn-add:hover { background: #5e3c85; }
    .btn-save { background: #28a745; } .btn-save:hover { background: #218838; }
    
    /* Search Box */
    .search-container { display: flex; gap: 10px; margin-bottom: 15px; }
    .btn-search { background: #1a237e; color: white; border: none; padding: 0 20px; border-radius: 6px; cursor: pointer; }

    /* Table */
    .table-nv { width: 100%; border-collapse: collapse; }
    .table-nv th { background: #f8f9fa; padding: 12px; text-align: left; color: #555; border-bottom: 2px solid #ddd; font-size: 13px; }
    .table-nv td { padding: 12px; border-bottom: 1px solid #eee; color: #333; font-size: 14px; vertical-align: middle; }
    
    .badge-role { padding: 4px 10px; border-radius: 4px; font-weight: bold; font-size: 12px; }
    .role-admin { background: #ffebee; color: #c62828; border: 1px solid #ffcdd2; }
    .role-staff { background: #e3f2fd; color: #1565c0; border: 1px solid #bbdefb; }
    .role-kho { background: #fff3e0; color: #ef6c00; border: 1px solid #ffe0b2; }
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
            <div class="card-header-custom bg-gradient-primary">
                <i class="fas fa-user-tie"></i> <?php echo $isEdit ? 'Cập Nhật Nhân Viên' : 'Thêm Nhân Viên Mới'; ?>
            </div>
            <div style="padding: 20px;">
                <form action="http://localhost/Baitaplon/Nhanvien/<?php echo $isEdit ? 'CapNhat' : 'Themmoi'; ?>" method="post">
                    
                    <div class="form-group">
                        <label class="form-label">Mã Nhân Viên (*):</label>
                        <input type="text" name="txtMaNV" class="form-control" required 
                               value="<?php echo $isEdit ? $row_edit['MaNV'] : ''; ?>"
                               placeholder="VD: NV01, ADMIN..."
                               <?php echo $isEdit ? 'readonly' : ''; ?>>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Họ và Tên:</label>
                        <input type="text" name="txtTen" class="form-control" required 
                               value="<?php echo $isEdit ? $row_edit['HoTen'] : ''; ?>">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Email:</label>
                        <input type="email" name="txtEmail" class="form-control" required 
                               value="<?php echo $isEdit ? $row_edit['Email'] : ''; ?>">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Chức Vụ:</label>
                        <select name="ddlChucVu" class="form-control">
                            <option value="Nhân viên" selected>Nhân viên</option>
                        </select>
                    </div>

                    <button type="submit" name="<?php echo $isEdit ? 'btnLuu' : 'btnThem'; ?>" 
                            class="btn-action <?php echo $isEdit ? 'btn-save' : 'btn-add'; ?>">
                        <i class="fas <?php echo $isEdit ? 'fa-save' : 'fa-check-circle'; ?>"></i> 
                        <?php echo $isEdit ? 'LƯU THAY ĐỔI' : 'THÊM NHÂN VIÊN'; ?>
                    </button>
                    
                    <?php if($isEdit) { ?>
                        <a href="http://localhost/Baitaplon/Nhanvien" class="btn-action" style="background:#6c757d; margin-top:10px; display:block; text-align:center; text-decoration:none;">Hủy bỏ</a>
                    <?php } ?>
                </form>
            </div>
        </div>
    </div>

    <div class="col-right" style="flex: 2;">
        <div class="card-custom">
            <div class="card-header-custom bg-blue">
                <i class="fas fa-users-cog"></i> Danh Sách Nhân Sự
            </div>
            <div style="padding: 20px;">
                
                <div class="search-container" style="justify-content: space-between; align-items: center;">
                    
                    <form action="http://localhost/Baitaplon/Nhanvien/Get_data" method="POST" style="display:flex; gap: 10px; flex: 1;">
                        <input type="text" name="txtTimKiem" class="form-control" 
                               placeholder="Nhập Mã NV hoặc Tên nhân viên..." 
                               value="<?php echo (isset($data['keyword']) && $data['keyword']!='') ? $data['keyword'] : '' ?>">
                        <button type="submit" name="btnTimKiem" class="btn-search"><i class="fas fa-search"></i> Tìm</button>
                        <a href="http://localhost/Baitaplon/Nhanvien" class="btn-search" style="background:#6c757d; display:flex; align-items:center; text-decoration:none;" title="Tải lại"><i class="fas fa-sync-alt"></i></a>
                    </form>

                    <div style="display:flex; gap: 10px;">
                        <a href="http://localhost/Baitaplon/Nhanvien/XuatExcel" class="btn-action btn-save" style="width:auto; padding: 0 15px; display:flex; align-items:center; text-decoration:none; background: #217346;">
                            <i class="fas fa-file-excel" style="margin-right:5px;"></i> Xuất
                        </a>
                        
                        <button onclick="document.getElementById('importModalNV').style.display='block'" class="btn-action btn-save" style="width:auto; padding: 0 15px; background: #1d6f42;">
                            <i class="fas fa-file-upload" style="margin-right:5px;"></i> Nhập
                        </button>
                    </div>
                </div>

                <table class="table-nv">
                    <thead>
                        <tr>
                            <th>Mã NV</th>
                            <th>Họ Tên</th>
                            <th>Email</th>
                            <th style="text-align:center">Chức Vụ</th>
                            <th style="text-align:center">Thao Tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if(isset($data['dulieu']) && mysqli_num_rows($data['dulieu']) > 0){
                            while($row = mysqli_fetch_array($data['dulieu'])){
                        ?>
                            <tr>
                                <td style="font-weight:bold; color:#1a237e"><?php echo $row['MaNV']; ?></td>
                                <td style="font-weight:600"><?php echo $row['HoTen']; ?></td>
                                <td><?php echo $row['Email']; ?></td>
                                
                                <td style="text-align:center">
                                    <?php 
                                        $roleClass = 'role-staff';
                                        if($row['ChucVu'] == 'Quản lý') $roleClass = 'role-admin';
                                        if($row['ChucVu'] == 'Kho') $roleClass = 'role-kho';
                                    ?>
                                    <span class="badge-role <?php echo $roleClass; ?>"><?php echo $row['ChucVu']; ?></span>
                                </td>
                                <td style="text-align:center">
                                    <a href="http://localhost/Baitaplon/Nhanvien/Sua/<?php echo $row['MaNV']; ?>" style="color:#ff9800; margin-right:10px; font-size:16px;" title="Sửa">
                                        <i class="fas fa-user-edit"></i>
                                    </a>
                                    <a href="http://localhost/Baitaplon/Nhanvien/Xoa/<?php echo $row['MaNV']; ?>" onclick="return confirm('Bạn có chắc muốn xóa nhân viên này?')" style="color:#dc3545; font-size:16px;" title="Xóa">
                                        <i class="fas fa-user-times"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php 
                            }
                        } else {
                            echo "<tr><td colspan='5' style='text-align:center; padding:30px; color:#999;'>Không tìm thấy nhân viên nào!</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div id="importModalNV" style="display:none; position: fixed; z-index: 999; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5);">
    <div style="background-color: #fefefe; margin: 10% auto; padding: 20px; border: 1px solid #888; width: 450px; border-radius: 8px; position: relative; box-shadow: 0 4px 8px rgba(0,0,0,0.2);">
        <span onclick="document.getElementById('importModalNV').style.display='none'" 
              style="color: #aaa; float: right; font-size: 28px; font-weight: bold; cursor: pointer;">&times;</span>
        
        <h3 style="color: #217346; margin-top: 0; border-bottom: 1px solid #eee; padding-bottom: 10px;">
            <i class="fas fa-file-excel"></i> Nhập Nhân Viên từ Excel
        </h3>
        
        <p style="font-size: 13px; color: #555; background: #e8f5e9; padding: 10px; border-radius: 4px;">
            <b><i class="fas fa-info-circle"></i> Hướng dẫn:</b><br>
            File Excel cần có 4 cột theo thứ tự:<br>
            <b>A: Mã NV | B: Họ Tên | C: Email | D: Chức Vụ</b>
        </p>
        
        <form action="http://localhost/Baitaplon/Nhanvien/NhapExcel" method="post" enctype="multipart/form-data">
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
        var modal = document.getElementById('importModalNV');
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
</script>