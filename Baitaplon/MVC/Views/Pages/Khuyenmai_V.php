<style>
    /* CSS Card & Form hiện đại */
    .card-custom {
        background: #fff; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        border: 1px solid #e0e0e0; overflow: hidden; margin-bottom: 20px;
    }
    .card-header-custom {
        padding: 15px 20px; color: white; font-weight: bold; font-size: 16px;
        display: flex; align-items: center; gap: 10px;
    }
    .bg-purple { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); } /* Màu tím */
    .bg-blue { background: linear-gradient(to right, #1a237e, #3949ab); } /* Màu xanh */

    .form-group { margin-bottom: 15px; }
    .form-label { font-weight: 600; display: block; margin-bottom: 5px; color: #444; }
    .form-control {
        width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;
        box-sizing: border-box; transition: 0.3s;
    }
    .form-control:focus { border-color: #764ba2; box-shadow: 0 0 5px rgba(118, 75, 162, 0.2); outline: none; }

    /* Buttons */
    .btn-action {
        width: 100%; padding: 12px; border: none; border-radius: 6px; color: white; font-weight: bold;
        cursor: pointer; transition: 0.3s; font-size: 14px;
    }
    .btn-add { background: #764ba2; } .btn-add:hover { background: #5e3c85; }
    .btn-save { background: #28a745; } .btn-save:hover { background: #218838; }
    
    /* Table & Badges */
    .table-voucher { width: 100%; border-collapse: collapse; }
    .table-voucher th { background: #f8f9fa; padding: 12px; text-align: left; color: #555; border-bottom: 2px solid #ddd; font-size: 13px; }
    .table-voucher td { padding: 12px; border-bottom: 1px solid #eee; color: #333; font-size: 14px; vertical-align: middle; }
    
    .badge-code { 
        background: #f3e5f5; color: #7b1fa2; border: 1px dashed #7b1fa2; 
        padding: 4px 10px; border-radius: 4px; font-weight: bold; font-family: monospace; font-size: 14px;
    }
    .status-active { background: #e8f5e9; color: #2e7d32; padding: 4px 10px; border-radius: 12px; font-size: 11px; font-weight: bold; border: 1px solid #c8e6c9; }
    .status-empty { background: #ffebee; color: #c62828; padding: 4px 10px; border-radius: 12px; font-size: 11px; font-weight: bold; border: 1px solid #ffcdd2; }

    /* Search Box */
    .search-container { display: flex; gap: 10px; margin-bottom: 15px; }
    .btn-search { background: #1a237e; color: white; border: none; padding: 0 20px; border-radius: 6px; cursor: pointer; }
</style>

<?php
    $isEdit = false;
    $row_edit = [];
    if(isset($data['edit_data'])) {
        $isEdit = true;
        $row_edit = $data['edit_data'];
    }
?>

<div class="row" style="display:flex; gap: 20px; align-items: flex-start;">
    
    <div class="col-left" style="flex: 1; position: sticky; top: 20px;">
        <div class="card-custom">
            <div class="card-header-custom bg-purple">
                <i class="fas fa-magic"></i> <?php echo $isEdit ? 'Cập Nhật Mã Giảm Giá' : 'Tạo Mã Khuyến Mãi'; ?>
            </div>
            <div style="padding: 20px;">
                <form action="http://localhost/Baitaplon/Khuyenmai/Luu" method="POST">
                    
                    <?php if($isEdit) { ?>
                        <input type="hidden" name="txtID" value="<?php echo $row_edit['MaKM']; ?>">
                    <?php } ?>

                    <div class="form-group">
                        <label class="form-label">Mã Code (Ví dụ: TET2025):</label>
                        <input type="text" name="txtTen" class="form-control" required placeholder="Nhập mã code..." 
                               style="text-transform: uppercase; letter-spacing: 1px; font-weight: bold; color: #764ba2;"
                               value="<?php echo $isEdit ? $row_edit['TenMa'] : ''; ?>">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Số tiền giảm (VNĐ):</label>
                        <div style="position:relative">
                            <input type="number" name="txtTien" class="form-control" required min="0" placeholder="VD: 50000"
                                   value="<?php echo $isEdit ? $row_edit['SoTienGiam'] : ''; ?>" style="padding-right: 40px;">
                            <span style="position:absolute; right:10px; top:10px; color:#999; font-size:12px;">đ</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Số lượng phát hành:</label>
                        <input type="number" name="txtSoLuong" class="form-control" required min="1" placeholder="VD: 100"
                               value="<?php echo $isEdit ? $row_edit['SoLuong'] : '100'; ?>">
                    </div>

                    <button type="submit" name="<?php echo $isEdit ? 'btnLuu' : 'btnThem'; ?>" 
                            class="btn-action <?php echo $isEdit ? 'btn-save' : 'btn-add'; ?>">
                        <i class="fas <?php echo $isEdit ? 'fa-save' : 'fa-plus-circle'; ?>"></i> 
                        <?php echo $isEdit ? 'LƯU THAY ĐỔI' : 'PHÁT HÀNH NGAY'; ?>
                    </button>
                    
                    <?php if($isEdit) { ?>
                        <a href="http://localhost/Baitaplon/Khuyenmai" class="btn-action" style="background:#6c757d; margin-top:10px; display:block; text-align:center; text-decoration:none;">Hủy bỏ</a>
                    <?php } ?>
                </form>

                <div style="text-align:center; margin-top: 25px; opacity: 0.8;">
                    <i class="fas fa-gifts" style="font-size: 50px; color: #e1bee7;"></i>
                    <p style="font-size: 13px; color: #999; margin-top: 10px;">Tạo ưu đãi hấp dẫn để thu hút khách hàng!</p>
                </div>
            </div>
        </div>
    </div>

    <div class="col-right" style="flex: 2;">
        <div class="card-custom">
            <div class="card-header-custom bg-blue">
                <i class="fas fa-tags"></i> Danh Sách Voucher Hiện Có
            </div>
            <div style="padding: 20px;">
                
                <div class="search-container" style="justify-content: space-between; align-items: center;">
                    
                    <form action="http://localhost/Baitaplon/Khuyenmai/Get_data" method="POST" style="display:flex; gap: 10px; flex: 1;">
                        <input type="text" name="txtTimKiem" class="form-control" 
                               placeholder="Nhập mã code cần tìm..." 
                               value="<?php echo (isset($data['keyword']) && $data['keyword']!='') ? $data['keyword'] : '' ?>">
                        <button type="submit" name="btnTimKiem" class="btn-search"><i class="fas fa-search"></i> Tìm</button>
                        <a href="http://localhost/Baitaplon/Khuyenmai" class="btn-search" style="background:#6c757d; display:flex; align-items:center; text-decoration:none;" title="Tải lại"><i class="fas fa-sync-alt"></i></a>
                    </form>

                    <div style="display:flex; gap: 10px;">
                        <a href="http://localhost/Baitaplon/Khuyenmai/XuatExcel" class="btn-action btn-save" style="width:auto; padding: 0 15px; display:flex; align-items:center; text-decoration:none; background: #217346;">
                            <i class="fas fa-file-excel" style="margin-right:5px;"></i> Xuất
                        </a>
                        
                        <button onclick="document.getElementById('importModalKM').style.display='block'" class="btn-action btn-save" style="width:auto; padding: 0 15px; background: #1d6f42;">
                            <i class="fas fa-file-upload" style="margin-right:5px;"></i> Nhập
                        </button>
                    </div>
                </div>


                <table class="table-voucher">
                    <thead>
                        <tr>
                            <th>Mã Code</th>
                            <th>Giảm Giá</th>
                            <th style="text-align:center">Số Lượng</th>
                            <th style="text-align:center">Trạng Thái</th>
                            <th style="text-align:center">Thao Tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if(isset($data['dulieu']) && mysqli_num_rows($data['dulieu']) > 0){
                            while($row = mysqli_fetch_array($data['dulieu'])){
                        ?>
                            <tr>
                                <td><span class="badge-code"><?php echo $row['TenMa']; ?></span></td>
                                <td style="color: #d32f2f; font-weight: bold;">
                                    - <?php echo number_format($row['SoTienGiam']); ?> đ
                                </td>
                                <td style="text-align:center; font-weight:bold;"><?php echo $row['SoLuong']; ?></td>
                                <td style="text-align:center;">
                                    <?php if($row['SoLuong'] > 0) { ?>
                                        <span class="status-active">Đang chạy</span>
                                    <?php } else { ?>
                                        <span class="status-empty">Hết lượt</span>
                                    <?php } ?>
                                </td>
                                <td style="text-align:center;">
                                    <a href="http://localhost/Baitaplon/Khuyenmai/Sua/<?php echo $row['MaKM']; ?>" style="color:#ff9800; margin-right:15px; font-size:16px;" title="Sửa">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="http://localhost/Baitaplon/Khuyenmai/Xoa/<?php echo $row['MaKM']; ?>" onclick="return confirm('Bạn chắc chắn muốn xóa mã này?')" style="color:#dc3545; font-size:16px;" title="Xóa">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php 
                            }
                        } else {
                            echo "<tr><td colspan='5' style='text-align:center; padding: 30px; color:#999;'>
                                <i class='fas fa-search' style='font-size:30px; margin-bottom:10px; display:block; color:#eee;'></i>    
                                Không tìm thấy mã giảm giá nào!
                            </td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div id="importModalKM" style="display:none; position: fixed; z-index: 999; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5);">
    <div style="background-color: #fefefe; margin: 10% auto; padding: 20px; border: 1px solid #888; width: 450px; border-radius: 8px; position: relative; box-shadow: 0 4px 8px rgba(0,0,0,0.2);">
        <span onclick="document.getElementById('importModalKM').style.display='none'" 
              style="color: #aaa; float: right; font-size: 28px; font-weight: bold; cursor: pointer;">&times;</span>
        
        <h3 style="color: #217346; margin-top: 0; border-bottom: 1px solid #eee; padding-bottom: 10px;">
            <i class="fas fa-file-excel"></i> Nhập Mã Khuyến Mãi
        </h3>
        
        <p style="font-size: 13px; color: #555; background: #e8f5e9; padding: 10px; border-radius: 4px;">
            <b><i class="fas fa-info-circle"></i> Hướng dẫn:</b><br>
            File Excel cần có 3 cột theo thứ tự:<br>
            <b>A: Mã Code | B: Số Tiền Giảm | C: Số Lượng</b>
        </p>
        
        <form action="http://localhost/Baitaplon/Khuyenmai/NhapExcel" method="post" enctype="multipart/form-data">
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
        var modal = document.getElementById('importModalKM');
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
</script>