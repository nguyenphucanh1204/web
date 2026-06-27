<style>
    /* CSS giữ nguyên như cũ */
    .form-group { margin-bottom: 15px; }
    .form-label { font-weight: bold; margin-bottom: 5px; display: block; color: #333; }
    .form-control { width: 100%; padding: 8px 12px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
    .form-control:focus { border-color: #4facfe; outline: none; box-shadow: 0 0 5px rgba(79, 172, 254, 0.3); }
    .search-box { background: #fff; padding: 15px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); margin: 20px 0; display: flex; gap: 10px; align-items: center; border: 1px solid #e0e0e0; }
    .btn-custom { padding: 8px 15px; border: none; border-radius: 4px; cursor: pointer; color: white; font-weight: 500; text-decoration: none; display: inline-block; }
    .btn-blue { background: #007bff; } .btn-orange { background: #ffc107; color: #333; } .btn-red { background: #dc3545; } .btn-gray { background: #6c757d; } .btn-custom:hover { opacity: 0.9; }
</style>

<?php
    $isEdit = false;
    $row_edit = [];
    if(isset($data['edit_data'])) {
        $isEdit = true;
        $row_edit = $data['edit_data'];
    }
?>

<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-list"></i> Quản Lý Danh Mục Sản Phẩm</h3>
    </div>
    
    <div class="card-body">
        
        <form action="http://localhost/Baitaplon/Danhmuc/Luu" method="post" 
              style="background: #f8f9fa; padding: 20px; border-radius: 8px; border: 1px solid #e9ecef;">
            
            <?php if($isEdit) { ?>
                <input type="hidden" name="txtID" value="<?php echo $row_edit['MaDM']; ?>">
            <?php } ?>

            <div class="row" style="display:flex; gap: 20px;">
                
                <div class="form-group" style="flex: 1;">
                    <label class="form-label">Mã Danh Mục:</label>
                    <input type="text" name="txtMaCode" class="form-control" required placeholder="VD: DM01"
                           value="<?php echo $isEdit ? $row_edit['MaCode'] : ''; ?>"
                           <?php echo $isEdit ? 'readonly style="background:#e9ecef"' : ''; ?>>
                </div>
                <div class="form-group" style="flex: 2;">
                    <label class="form-label">Tên Danh Mục:</label>
                    <input type="text" name="txtTen" class="form-control" required placeholder="VD: Đồ điện tử, Gia dụng..."
                           value="<?php echo $isEdit ? $row_edit['TenDM'] : ''; ?>">
                </div>

                <div class="form-group" style="flex: 3;">
                    <label class="form-label">Mô Tả:</label>
                    <input type="text" name="txtMoTa" class="form-control" placeholder="Mô tả ngắn về danh mục..."
                           value="<?php echo ($isEdit && isset($row_edit['MoTa'])) ? $row_edit['MoTa'] : ''; ?>">
                </div>
            </div>

            <div style="text-align: center; margin-top: 10px;">
                <button type="submit" name="<?php echo $isEdit ? 'btnLuu' : 'btnThem'; ?>" 
                        class="btn-custom <?php echo $isEdit ? 'btn-orange' : 'btn-blue'; ?>" 
                        style="padding: 10px 40px;">
                    <i class="fas <?php echo $isEdit ? 'fa-save' : 'fa-plus-circle'; ?>"></i> 
                    <?php echo $isEdit ? 'LƯU THAY ĐỔI' : 'THÊM MỚI'; ?>
                </button>
                
                <?php if($isEdit) { ?>
                    <a href="http://localhost/Baitaplon/Danhmuc" class="btn-custom btn-gray" style="margin-left: 10px;">Hủy bỏ</a>
                <?php } ?>
            </div>
        </form>



        <div class="search-box" style="justify-content: space-between;">
            
            <form action="http://localhost/Baitaplon/Danhmuc/Get_data" method="POST" style="display:flex; gap: 10px; flex: 1;">
                <div style="flex: 1; position: relative;">
                    <i class="fas fa-search" style="position: absolute; left: 10px; top: 10px; color: #888;"></i>
                    <input type="text" name="txtTimKiem" class="form-control" 
                           placeholder="Nhập mã hoặc tên danh mục cần tìm..." 
                           value="<?php echo (isset($data['keyword']) && $data['keyword']!='0') ? $data['keyword'] : '' ?>" 
                           style="padding-left: 35px;">
                </div>
                <button type="submit" name="btnTimKiem" class="btn-custom btn-blue">Tìm kiếm</button>
                <a href="http://localhost/Baitaplon/Danhmuc" class="btn-custom btn-gray"><i class="fas fa-sync-alt"></i> Tất cả</a>
            </form>

            <div style="display:flex; gap: 10px;">
                <a href="http://localhost/Baitaplon/Danhmuc/XuatExcel" class="btn-custom" style="background: #217346; color:white;">
                    <i class="fas fa-file-excel"></i> Xuất Excel
                </a>
                
                <button onclick="document.getElementById('importModalDM').style.display='block'" class="btn-custom" style="background: #1d6f42; color:white;">
                    <i class="fas fa-file-upload"></i> Nhập Excel
                </button>
            </div>
        </div>




        <table class="table-custom">
            <thead>
                <tr>
                    <th style="width: 100px; text-align: center;">Mã DM</th>
                    <th>Tên Danh Mục</th>
                    <th>Mô Tả</th>
                    <th style="width: 120px;">Hành Động</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if(isset($data['dulieu']) && mysqli_num_rows($data['dulieu']) > 0){
                    while($row = mysqli_fetch_array($data['dulieu'])){
                ?>
                    <tr>
                        <td style="text-align: center;">
                            <span style="background: #e3f2fd; color: #1565c0; padding: 4px 8px; border-radius: 4px; font-weight: bold; font-size:13px;">
                                <?php echo isset($row['MaCode']) && $row['MaCode']!='' ? $row['MaCode'] : $row['MaDM']; ?>
                            </span>
                        </td>
                        
                        <td style="font-weight: 600; color: #333; font-size: 15px;"><?php echo $row['TenDM']; ?></td>
                        <td style="color: #666;"><?php echo isset($row['MoTa']) ? $row['MoTa'] : ''; ?></td>
                        <td>
                            <a href="http://localhost/Baitaplon/Danhmuc/Sua/<?php echo $row['MaDM']; ?>" class="btn-custom btn-orange" style="padding: 5px 10px; font-size: 12px;">
                                <i class="fas fa-pen"></i>
                            </a>
                            <a href="http://localhost/Baitaplon/Danhmuc/Xoa/<?php echo $row['MaDM']; ?>" onclick="return confirm('Xóa danh mục này sẽ ảnh hưởng đến các sản phẩm thuộc về nó. Bạn chắc chắn chứ?')" class="btn-custom btn-red" style="padding: 5px 10px; font-size: 12px;">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                <?php 
                    }
                } else {
                    echo "<tr><td colspan='4' style='text-align:center; padding: 20px; color: #777;'>Không tìm thấy danh mục nào!</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<div id="importModalDM" style="display:none; position: fixed; z-index: 999; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5);">
    <div style="background-color: #fefefe; margin: 10% auto; padding: 20px; border: 1px solid #888; width: 450px; border-radius: 8px; position: relative; box-shadow: 0 4px 8px rgba(0,0,0,0.2);">
        <span onclick="document.getElementById('importModalDM').style.display='none'" 
              style="color: #aaa; float: right; font-size: 28px; font-weight: bold; cursor: pointer;">&times;</span>
        
        <h3 style="color: #217346; margin-top: 0; border-bottom: 1px solid #eee; padding-bottom: 10px;">
            <i class="fas fa-file-excel"></i> Nhập Danh Mục từ Excel
        </h3>
        
        <p style="font-size: 13px; color: #555; background: #e8f5e9; padding: 10px; border-radius: 4px;">
            <b><i class="fas fa-info-circle"></i> Hướng dẫn:</b><br>
            File Excel cần có 3 cột theo thứ tự:<br>
            <b>A: Mã Danh Mục | B: Tên Danh Mục | C: Mô Tả</b>
        </p>
        
        <form action="http://localhost/Baitaplon/Danhmuc/NhapExcel" method="post" enctype="multipart/form-data">
            <label style="display:block; margin-bottom:5px; font-weight:bold;">Chọn file Excel:</label>
            <input type="file" name="fileExcel" class="form-control" required accept=".xlsx, .xls" style="margin-bottom: 15px;">
            
            <button type="submit" name="btnNhapExcel" class="btn-custom" style="background: #217346; width: 100%; padding: 12px; font-size: 14px;">
                <i class="fas fa-upload"></i> Tải lên hệ thống
            </button>
        </form>
    </div>
</div>

<script>
    // Click ra ngoài thì đóng modal
    window.onclick = function(event) {
        var modal = document.getElementById('importModalDM');
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
</script>