<style>
    /* ... Giữ nguyên phần CSS cũ ... */
    .card-custom { background: #fff; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); border: 1px solid #e0e0e0; overflow: hidden; margin-bottom: 20px; }
    .card-header-custom { padding: 15px 20px; color: white; font-weight: bold; font-size: 16px; display: flex; align-items: center; gap: 10px; }
    .bg-gradient-orange { background: linear-gradient(135deg, #f6d365 0%, #fda085 100%); }
    .bg-blue { background: linear-gradient(to right, #1a237e, #3949ab); }
    .form-group { margin-bottom: 15px; }
    .form-label { font-weight: 600; display: block; margin-bottom: 5px; color: #444; }
    .form-control { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; box-sizing: border-box; transition: 0.3s; }
    .form-control[readonly], .form-control[disabled] { background-color: #f2f2f2; color: #666; cursor: not-allowed; }
    .form-control:focus { border-color: #fda085; box-shadow: 0 0 5px rgba(253, 160, 133, 0.4); outline: none; }
    .btn-action { width: 100%; padding: 12px; border: none; border-radius: 6px; color: white; font-weight: bold; cursor: pointer; transition: 0.3s; }
    .btn-add { background: #fda085; color: #fff; text-shadow: 0 1px 2px rgba(0,0,0,0.1); } .btn-add:hover { background: #f6d365; }
    .btn-save { background: #28a745; } .btn-save:hover { background: #218838; }
    .search-container { display: flex; gap: 10px; margin-bottom: 15px; }
    .btn-search { background: #1a237e; color: white; border: none; padding: 0 20px; border-radius: 6px; cursor: pointer; }
    .table-cust { width: 100%; border-collapse: collapse; }
    .table-cust th { background: #f8f9fa; padding: 12px; text-align: left; color: #555; border-bottom: 2px solid #ddd; font-size: 13px; }
    .table-cust td { padding: 12px; border-bottom: 1px solid #eee; color: #333; font-size: 14px; vertical-align: middle; }
    .badge-point { background: #e8f5e9; color: #2e7d32; padding: 4px 10px; border-radius: 20px; font-weight: bold; font-size: 13px; border: 1px solid #c8e6c9; }
    .badge-id { background: #e3f2fd; color: #0d47a1; padding: 3px 8px; border-radius: 4px; font-weight: bold; font-size: 12px; border: 1px solid #90caf9; }
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
            <div class="card-header-custom bg-gradient-orange">
                <i class="fas fa-user-plus"></i> <?php echo $isEdit ? 'Cập Nhật Thông Tin' : 'Thêm Khách Hàng'; ?>
            </div>
            <div style="padding: 20px;">
                <form action="http://localhost/Baitaplon/Khachhang/<?php echo $isEdit ? 'CapNhat' : 'Themmoi'; ?>" method="post">
                    
                    <div class="form-group">
                        <label class="form-label">Mã Khách Hàng (*):</label>
                        <input type="text" name="txtMaKH" class="form-control" 
                               value="<?php echo $isEdit ? $row_edit['MaKH'] : ''; ?>" 
                               placeholder="VD: KH01, VIP_SON..."
                               <?php echo $isEdit ? 'readonly' : 'required'; ?>>
                        
                        <?php if(!$isEdit) { ?>
                        <small style="color:#666; font-size:12px; font-style:italic;">Nhập mã duy nhất (VD: Số điện thoại hoặc Tên viết tắt)</small>
                        <?php } ?>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Tên Khách Hàng:</label>
                        <input type="text" name="txtTen" class="form-control" required placeholder="Họ và tên..."
                               value="<?php echo $isEdit ? $row_edit['TenKH'] : ''; ?>">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Số Điện Thoại:</label>
                        <input type="number" name="txtSDT" class="form-control" required placeholder="09xxx..."
                               value="<?php echo $isEdit ? $row_edit['DienThoai'] : ''; ?>">
                    </div>

                    <?php if($isEdit) { ?>
                        <div class="form-group">
                            <label class="form-label" style="color:#d35400"><i class="fas fa-star"></i> Điểm Tích Lũy:</label>
                            <input type="text" class="form-control" 
                                   style="font-weight:bold; color:#d32f2f; background:#fbe9e7;"
                                   value="<?php echo number_format($row_edit['DiemTichLuy']); ?> điểm" 
                                   readonly>
                        </div>
                    <?php } ?>

                    <button type="submit" name="<?php echo $isEdit ? 'btnLuu' : 'btnThem'; ?>" 
                            class="btn-action <?php echo $isEdit ? 'btn-save' : 'btn-add'; ?>">
                        <i class="fas <?php echo $isEdit ? 'fa-save' : 'fa-check-circle'; ?>"></i> 
                        <?php echo $isEdit ? 'LƯU THAY ĐỔI' : 'THÊM MỚI'; ?>
                    </button>
                    
                    <?php if($isEdit) { ?>
                        <a href="http://localhost/Baitaplon/Khachhang" class="btn-action" style="background:#6c757d; margin-top:10px; display:block; text-align:center; text-decoration:none;">Hủy bỏ</a>
                    <?php } ?>
                </form>

                <div style="text-align:center; margin-top:20px; opacity:0.8">
                    <i class="fas fa-users" style="font-size: 50px; color: #ffcc80;"></i>
                    <p style="font-size:12px; margin-top:10px; color:#888">Quản lý khách hàng thân thiết hiệu quả</p>
                </div>
            </div>
        </div>
    </div>

    <div class="col-right" style="flex: 2;">
        <div class="card-custom">
            <div class="card-header-custom bg-blue">
                <i class="fas fa-list-alt"></i> Danh Sách Khách Hàng
            </div>
            <div style="padding: 20px;">
                
                <div class="search-container" style="justify-content: space-between; align-items: center;">
    
    <form action="http://localhost/Baitaplon/Khachhang/Get_data" method="POST" style="display:flex; gap: 10px; flex: 1;">
        <input type="text" name="txtTimKiem" class="form-control" 
               placeholder="Nhập mã, tên hoặc SĐT..." 
               value="<?php echo (isset($data['keyword']) && $data['keyword']!='') ? $data['keyword'] : '' ?>">
        <button type="submit" name="btnTimKiem" class="btn-search"><i class="fas fa-search"></i> Tìm</button>
        <a href="http://localhost/Baitaplon/Khachhang" class="btn-search" style="background:#6c757d; display:flex; align-items:center; text-decoration:none;" title="Tải lại"><i class="fas fa-sync-alt"></i></a>
    </form>

    <div style="display:flex; gap: 10px;">
        <a href="http://localhost/Baitaplon/Khachhang/XuatExcel" class="btn-action btn-save" style="width:auto; padding: 0 15px; display:flex; align-items:center; text-decoration:none; background: #217346;">
            <i class="fas fa-file-excel" style="margin-right:5px;"></i> Xuất Excel
        </a>
        
        <button onclick="document.getElementById('importModalKH').style.display='block'" class="btn-action btn-save" style="width:auto; padding: 0 15px; background: #1d6f42;">
            <i class="fas fa-file-upload" style="margin-right:5px;"></i> Nhập Excel
        </button>
    </div>
</div>


                <table class="table-cust">
                    <thead>
                        <tr>
                            <th style="width: 50px;">STT</th>
                            <th style="width: 100px; text-align: center;">Mã KH</th>
                            <th>Họ Tên</th>
                            <th>Điện Thoại</th>
                            <th style="text-align:center">Điểm Tích Lũy</th>
                            <th style="text-align:center">Thao Tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if(isset($data['dulieu']) && mysqli_num_rows($data['dulieu']) > 0){
                            $i = 1;
                            while($row = mysqli_fetch_array($data['dulieu'])){
                        ?>
                            <tr>
                                <td style="text-align:center; color:#888;"><?php echo $i++; ?></td>
                                <td style="text-align:center;">
                                    <span class="badge-id">#<?php echo $row['MaKH']; ?></span>
                                </td>
                                <td style="font-weight:600; color:#333;"><?php echo $row['TenKH']; ?></td>
                                <td><?php echo $row['DienThoai']; ?></td>
                                <td style="text-align:center;">
                                    <span class="badge-point">
                                        <i class="fas fa-coins" style="margin-right:4px; color:#fbc02d;"></i>
                                        <?php echo number_format($row['DiemTichLuy']); ?>
                                    </span>
                                </td>
                                <td style="text-align:center;">
                                    <a href="http://localhost/Baitaplon/Khachhang/Sua/<?php echo $row['MaKH']; ?>" style="color:#ff9800; margin-right:10px; font-size:16px;" title="Sửa">
                                        <i class="fas fa-user-edit"></i>
                                    </a>
                                    <a href="http://localhost/Baitaplon/Khachhang/Xoa/<?php echo $row['MaKH']; ?>" onclick="return confirm('Xóa khách hàng này?')" style="color:#dc3545; font-size:16px;" title="Xóa">
                                        <i class="fas fa-user-times"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php 
                            }
                        } else {
                            echo "<tr><td colspan='6' style='text-align:center; padding: 30px; color:#999;'>Không tìm thấy khách hàng nào!</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div id="importModalKH" style="display:none; position: fixed; z-index: 999; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5);">
    <div style="background-color: #fefefe; margin: 10% auto; padding: 20px; border: 1px solid #888; width: 450px; border-radius: 8px; position: relative; box-shadow: 0 4px 8px rgba(0,0,0,0.2);">
        <span onclick="document.getElementById('importModalKH').style.display='none'" 
              style="color: #aaa; float: right; font-size: 28px; font-weight: bold; cursor: pointer;">&times;</span>
        
        <h3 style="color: #217346; margin-top: 0; border-bottom: 1px solid #eee; padding-bottom: 10px;">
            <i class="fas fa-file-excel"></i> Nhập Khách Hàng từ Excel
        </h3>
        
        <p style="font-size: 13px; color: #555; background: #e8f5e9; padding: 10px; border-radius: 4px;">
            <b><i class="fas fa-info-circle"></i> Hướng dẫn:</b><br>
            File Excel cần có 3 cột theo thứ tự:<br>
            <b>A: Mã Khách Hàng | B: Họ Tên | C: Số Điện Thoại</b>
        </p>
        
        <form action="http://localhost/Baitaplon/Khachhang/NhapExcel" method="post" enctype="multipart/form-data">
            <label style="display:block; margin-bottom:5px; font-weight:bold;">Chọn file Excel (.xlsx, .xls):</label>
            <input type="file" name="fileExcel" class="form-control" required accept=".xlsx, .xls" style="margin-bottom: 15px;">
            
            <button type="submit" name="btnNhapExcel" class="btn-action btn-save" style="background: #217346; width: 100%;">
                <i class="fas fa-upload"></i> Tải lên hệ thống
            </button>
        </form>
    </div>
</div>

<script>
    // Click ra ngoài thì đóng modal
    window.onclick = function(event) {
        var modal = document.getElementById('importModalKH');
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
</script>