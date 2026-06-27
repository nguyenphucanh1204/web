<style>
    /* CSS Card & Table */
    .card-custom {
        background: #fff; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        border: 1px solid #e0e0e0; overflow: hidden; margin-bottom: 20px;
    }
    .card-header-custom {
        padding: 15px 20px; color: white; font-weight: bold; font-size: 16px;
        display: flex; align-items: center; gap: 10px;
    }
    .bg-gradient-teal { background: linear-gradient(135deg, #20c997 0%, #087f5b 100%); } /* Màu xanh ngọc */
    .bg-blue { background: linear-gradient(to right, #1a237e, #3949ab); }

    .form-group { margin-bottom: 15px; }
    .form-label { font-weight: 600; display: block; margin-bottom: 5px; color: #444; }
    .form-control {
        width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;
        box-sizing: border-box; transition: 0.3s;
    }
    .form-control:focus { border-color: #20c997; box-shadow: 0 0 5px rgba(32, 201, 151, 0.2); outline: none; }

    /* Buttons */
    .btn-action {
        width: 100%; padding: 12px; border: none; border-radius: 6px; color: white; font-weight: bold;
        cursor: pointer; transition: 0.3s;
    }
    .btn-add { background: #20c997; } .btn-add:hover { background: #12b886; }
    .btn-save { background: #ffc107; color: #333; } .btn-save:hover { background: #e0a800; }
    
    /* Table */
    .table-pay { width: 100%; border-collapse: collapse; }
    .table-pay th { background: #f8f9fa; padding: 12px; text-align: left; color: #555; border-bottom: 2px solid #ddd; font-size: 13px; }
    .table-pay td { padding: 12px; border-bottom: 1px solid #eee; color: #333; font-size: 14px; vertical-align: middle; }
    
    .status-active { background: #e8f5e9; color: #2e7d32; padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: bold; border: 1px solid #c8e6c9; }
    .status-off { background: #ffebee; color: #c62828; padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: bold; border: 1px solid #ffcdd2; }

    .qr-thumb {
        width: 40px; height: 40px; object-fit: cover; border-radius: 4px;
        border: 1px solid #ddd; vertical-align: middle; margin-right: 10px;
    }
    .qr-placeholder {
        width: 40px; height: 40px; background: #f0f0f0; border-radius: 4px;
        display: inline-flex; align-items: center; justify-content: center;
        margin-right: 10px; vertical-align: middle; color: #ccc; font-size: 10px;
    }
</style>

<?php
    $isEdit = false;
    $row_edit = [];
    if(isset($data['edit_data'])) {
        $isEdit = true;
        $row_edit = $data['edit_data'];
    }
?>

<div class="row" style="display:flex; gap: 25px; align-items: flex-start;">
    
    <div class="col-left" style="flex: 1; position: sticky; top: 20px;">
        <div class="card-custom">
            <div class="card-header-custom bg-gradient-teal">
                <i class="fas fa-qrcode"></i> <?php echo $isEdit ? 'Cập Nhật QR Code' : 'Thêm Phương Thức'; ?>
            </div>
            <div style="padding: 20px;">
                
                <form action="http://localhost/Baitaplon/Phuongthuc/Luu" method="POST" enctype="multipart/form-data">
                    
                    <?php if($isEdit) { ?>
                        <input type="hidden" name="txtID" value="<?php echo $row_edit['MaPT']; ?>">
                    <?php } ?>

                    <div class="form-group">
                        <label class="form-label">Tên phương thức:</label>
                        <input type="text" name="txtTen" class="form-control" required placeholder="VD: Quét mã MoMo..."
                               value="<?php echo $isEdit ? $row_edit['TenPT'] : ''; ?>">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Ảnh QR Code (Nếu có):</label>
                        <input type="file" name="fileHinh" class="form-control" accept="image/*">
                        <?php if($isEdit && !empty($row_edit['HinhAnh'])) { ?>
                            <div style="margin-top:10px; font-size:12px; color:#666;">
                                <img src="http://localhost/Baitaplon/Public/Images/<?php echo $row_edit['HinhAnh']; ?>" style="height:60px; border:1px solid #eee;">
                                <br>Ảnh hiện tại (Chọn mới để thay thế)
                            </div>
                        <?php } ?>
                    </div>

                    <?php if($isEdit) { ?>
                    <div class="form-group">
                        <label class="form-label">Trạng thái:</label>
                        <select name="ddlTrangThai" class="form-control">
                            <option value="1" <?php echo ($row_edit['TrangThai']==1)?'selected':''; ?>>Đang hoạt động</option>
                            <option value="0" <?php echo ($row_edit['TrangThai']==0)?'selected':''; ?>>Tạm ngưng</option>
                        </select>
                    </div>
                    <?php } ?>

                    <button type="submit" name="<?php echo $isEdit ? 'btnLuu' : 'btnThem'; ?>" 
                            class="btn-action <?php echo $isEdit ? 'btn-save' : 'btn-add'; ?>">
                        <i class="fas <?php echo $isEdit ? 'fa-save' : 'fa-plus-circle'; ?>"></i> 
                        <?php echo $isEdit ? 'LƯU THAY ĐỔI' : 'THÊM MỚI'; ?>
                    </button>
                    
                    <?php if($isEdit) { ?>
                        <a href="http://localhost/Baitaplon/Phuongthuc" class="btn-action" style="background:#6c757d; margin-top:10px; display:block; text-align:center; text-decoration:none;">Hủy bỏ</a>
                    <?php } ?>
                </form>
            </div>
        </div>
    </div>

    <div class="col-right" style="flex: 2;">
        <div class="card-custom">
            <div class="card-header-custom bg-blue">
                <i class="fas fa-list-ul"></i> Danh Sách Thanh Toán
            </div>
            <div style="padding: 20px;">
                <table class="table-pay">
                    <thead>
                        <tr>
                            <th style="width: 50px; text-align:center">ID</th>
                            <th>Phương Thức & QR</th>
                            <th style="text-align:center">Trạng Thái</th>
                            <th style="width: 120px; text-align:center">Thao Tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if(isset($data['dulieu']) && mysqli_num_rows($data['dulieu']) > 0){
                            while($row = mysqli_fetch_array($data['dulieu'])){
                        ?>
                            <tr>
                                <td style="text-align:center; color:#888;">#<?php echo $row['MaPT']; ?></td>
                                <td style="font-weight: 600;">
                                    <?php if(!empty($row['HinhAnh'])) { ?>
                                        <img src="http://localhost/Baitaplon/Public/Images/<?php echo $row['HinhAnh']; ?>" class="qr-thumb">
                                    <?php } else { ?>
                                        <span class="qr-placeholder">NO QR</span>
                                    <?php } ?>
                                    
                                    <?php echo $row['TenPT']; ?>
                                </td>
                                <td style="text-align:center;">
                                    <?php if(isset($row['TrangThai']) && $row['TrangThai'] == 0) { ?>
                                        <span class="status-off">Tạm ngưng</span>
                                    <?php } else { ?>
                                        <span class="status-active">Hoạt động</span>
                                    <?php } ?>
                                </td>
                                <td style="text-align:center;">
                                    <a href="http://localhost/Baitaplon/Phuongthuc/Sua/<?php echo $row['MaPT']; ?>" style="color:#ffc107; margin-right:10px; font-size:16px;">
                                        <i class="fas fa-pen-square"></i>
                                    </a>
                                    <a href="http://localhost/Baitaplon/Phuongthuc/Xoa/<?php echo $row['MaPT']; ?>" onclick="return confirm('Xóa phương thức này?')" style="color:#dc3545; font-size:16px;">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php 
                            }
                        } else {
                            echo "<tr><td colspan='4' style='text-align:center; padding: 20px; color:#999;'>Chưa có phương thức thanh toán nào!</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>