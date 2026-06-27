<style>
    /* CSS Giữ nguyên như cũ */
    .form-group { margin-bottom: 15px; }
    .form-label { font-weight: bold; margin-bottom: 5px; display: block; color: #333; }
    .form-control { width: 100%; padding: 8px 12px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
    .form-control:focus { border-color: #4facfe; outline: none; box-shadow: 0 0 5px rgba(79, 172, 254, 0.3); }
    
    .search-box {
        background: #fff; padding: 15px; border-radius: 8px; 
        box-shadow: 0 2px 5px rgba(0,0,0,0.05); margin: 20px 0; 
        display: flex; gap: 10px; align-items: center; border: 1px solid #e0e0e0;
    }
    
    .btn-custom { padding: 8px 15px; border: none; border-radius: 4px; cursor: pointer; color: white; font-weight: 500; text-decoration: none; display: inline-block; }
    .btn-blue { background: #007bff; }
    .btn-green { background: #28a745; }
    .btn-orange { background: #ffc107; color: #333; }
    .btn-red { background: #dc3545; }
    .btn-gray { background: #6c757d; }
    .btn-custom:hover { opacity: 0.9; }
</style>

<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-box"></i> Quản Lý Sản Phẩm</h3>
    </div>
    
    <div class="card-body">
        
        <form action="http://localhost/Baitaplon/Sanpham/<?php echo isset($data['editData']) ? 'CapNhat' : 'Themmoi'; ?>" 
              method="post" enctype="multipart/form-data" 
              style="background: #f8f9fa; padding: 20px; border-radius: 8px; border: 1px solid #e9ecef;">

            <div class="row" style="display:flex; gap: 20px;">
                <div class="form-group" style="flex: 1;">
                    <label class="form-label">Mã Sản Phẩm:</label>
                    <input type="text" name="txtMaSP" class="form-control" required placeholder="VD: SP01"
                           value="<?php echo isset($data['editData']) ? $data['editData']['MaSP'] : ''; ?>"
                           <?php echo isset($data['editData']) ? 'readonly style="background:#e9ecef"' : ''; ?>> 
                </div>

                <div class="form-group" style="flex: 3;">
                    <label class="form-label">Tên Sản Phẩm:</label>
                    <input type="text" name="txtTenSP" class="form-control" required placeholder="Nhập tên sản phẩm..."
                           value="<?php echo isset($data['editData']) ? $data['editData']['TenSP'] : ''; ?>">
                </div>
            </div>

            <div class="row" style="display:flex; gap: 20px;">
                <div class="form-group" style="flex: 1;">
                    <label class="form-label">Danh Mục:</label>
                    <select name="ddlDanhmuc" class="form-control">
                        <option value="">-- Chọn danh mục --</option>
                        <?php
                        if(isset($data['danhmuc'])){
                            while($row = mysqli_fetch_array($data['danhmuc'])){
                                $selected = '';
                                if(isset($data['editData']) && $data['editData']['MaDM'] == $row['MaDM']) 
                                    $selected = 'selected';
                                echo '<option value="'.$row['MaDM'].'" '.$selected.'>'.$row['TenDM'].'</option>';
                            }
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group" style="flex: 1;">
                    <label class="form-label">Nhà Cung Cấp:</label>
                    <select name="ddlNCC" class="form-control">
                        <option value="">-- Chọn NCC --</option>
                        <?php
                        if(isset($data['nhacungcap'])){
                            while($row = mysqli_fetch_array($data['nhacungcap'])){
                                $selected = '';
                                if(isset($data['editData']) && $data['editData']['MaNCC'] == $row['MaNCC']) 
                                    $selected = 'selected';
                                echo '<option value="'.$row['MaNCC'].'" '.$selected.'>'.$row['TenNCC'].'</option>';
                            }
                        }
                        ?>
                    </select>
                </div>
            </div>

            <div class="row" style="display:flex; gap: 20px; align-items: flex-end;">
                <div class="form-group" style="flex: 1;">
                    <label class="form-label">Giá Bán:</label>
                    <input type="number" name="txtGia" class="form-control" placeholder="VNĐ"
                           value="<?php echo isset($data['editData']) ? $data['editData']['GiaBan'] : ''; ?>">
                </div>
                
                <div class="form-group" style="flex: 1;">
                    <label class="form-label">Số Lượng:</label>
                    <input type="number" name="txtSoLuong" class="form-control" 
                           value="<?php echo isset($data['editData']) ? $data['editData']['SoLuongTon'] : '0'; ?>"
                           <?php echo isset($data['editData']) ? 'readonly style="background:#e9ecef"' : ''; ?>>
                </div>

                <div class="form-group" style="flex: 1;">
                    <label class="form-label">Hạn Sử Dụng (Nếu có):</label>
                    <input type="date" name="txtHSD" class="form-control" 
                           value="<?php echo isset($data['editData']) ? $data['editData']['HanSuDung'] : ''; ?>">
                </div>

                <div class="form-group" style="flex: 2;">
                    <label class="form-label">Hình Ảnh:</label>
                    <div style="display: flex; gap: 10px; align-items: center;">
                        <input type="file" name="txtHinhAnh" class="form-control" style="padding: 5px;">
                        <?php if(isset($data['editData']) && $data['editData']['HinhAnh'] != ""){ ?>
                            <img src="http://localhost/Baitaplon/Public/Images/<?php echo $data['editData']['HinhAnh'] ?>" width="40px" style="border: 1px solid #ccc; border-radius: 4px;">
                        <?php } ?>
                    </div>
                </div>
            </div>

            <div style="text-align: center; margin-top: 10px;">
                <button type="submit" name="<?php echo isset($data['editData']) ? 'btnLuu' : 'btnThem'; ?>" 
                        class="btn-custom <?php echo isset($data['editData']) ? 'btn-orange' : 'btn-blue'; ?>" 
                        style="padding: 10px 40px;">
                    <i class="fas <?php echo isset($data['editData']) ? 'fa-save' : 'fa-plus-circle'; ?>"></i> 
                    <?php echo isset($data['editData']) ? 'LƯU THAY ĐỔI' : 'THÊM MỚI'; ?>
                </button>
                
                <?php if(isset($data['editData'])) { ?>
                    <a href="http://localhost/Baitaplon/Sanpham" class="btn-custom btn-gray" style="margin-left: 10px;">Hủy bỏ</a>
                <?php } ?>
            </div>
        </form>

        <div class="search-box" style="justify-content: space-between;">
            
            <form action="http://localhost/Baitaplon/Sanpham" method="POST" style="display:flex; gap: 10px; flex: 1;">
                <div style="flex: 1; position: relative;">
                    <i class="fas fa-search" style="position: absolute; left: 10px; top: 10px; color: #888;"></i>
                    <input type="text" name="txtTimKiem" class="form-control" 
                           placeholder="Nhập tên hoặc mã sản phẩm..." 
                           value="<?php echo (isset($data['keyword']) && $data['keyword'] != '0') ? $data['keyword'] : '' ?>"
                           style="padding-left: 35px;">
                </div>
                <button type="submit" name="btnTimKiem" class="btn-custom btn-blue">Tìm</button>
                <a href="http://localhost/Baitaplon/Sanpham" class="btn-custom btn-gray"><i class="fas fa-sync-alt"></i></a>
            </form>

            <div style="display:flex; gap: 10px;">
                <a href="http://localhost/Baitaplon/Sanpham/XuatExcel" class="btn-custom" style="background: #217346; color:white;">
                    <i class="fas fa-file-excel"></i> Xuất Excel
                </a>
                
                <button onclick="document.getElementById('importModal').style.display='block'" class="btn-custom" style="background: #1d6f42; color:white;">
                    <i class="fas fa-file-upload"></i> Nhập Excel
                </button>
            </div>
        </div>

        <table class="table-custom">
            <thead>
                <tr>
                    <th style="width: 60px;">Ảnh</th>
                    <th style="width: 80px;">Mã SP</th>
                    <th>Tên Sản Phẩm</th>
                    <th>Danh Mục</th>
                    <th>Nhà Cung Cấp</th> 
                    <th style="width: 100px;">Giá Bán</th>
                    <th style="width: 60px;">Tồn</th>
                    <th>Hạn Sử Dụng</th> <th style="width: 100px;">Thao Tác</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if(isset($data['dulieu']) && mysqli_num_rows($data['dulieu']) > 0){
                    while($row = mysqli_fetch_array($data['dulieu'])){
                ?>
                    <tr>
                        <td style="text-align: center;">
                            <?php if($row['HinhAnh'] != "" && $row['HinhAnh'] != "no-image.jpg"){ ?>
                                <img src="http://localhost/Baitaplon/Public/Images/<?php echo $row['HinhAnh'] ?>" width="40px" height="40px" style="object-fit: cover; border-radius: 4px; border: 1px solid #eee;">
                            <?php } else { ?>
                                <i class="fas fa-image" style="color: #ccc; font-size: 24px;"></i>
                            <?php } ?>
                        </td>
                        <td><span style="background: #eef; color: #337ab7; padding: 2px 5px; border-radius: 3px; font-size: 12px; font-weight: bold;"><?php echo $row['MaSP']; ?></span></td>
                        <td style="font-weight: 600; color: #333;"><?php echo $row['TenSP']; ?></td>
                        <td><?php echo $row['TenDM']; ?></td>
                        <td style="color: #555;"><?php echo $row['TenNCC']; ?></td> 
                        <td style="color: #dc3545; font-weight: bold;"><?php echo number_format($row['GiaBan']); ?></td>
                        <td style="text-align: center;">
                            <?php if($row['SoLuongTon'] <= 5) { ?>
                                <span style="color: red; font-weight: bold;"><?php echo $row['SoLuongTon']; ?></span>
                            <?php } else { ?>
                                <span style="color: green; font-weight: bold;"><?php echo $row['SoLuongTon']; ?></span>
                            <?php } ?>
                        </td>
                        <td>
                            <?php 
                                if($row['HanSuDung'] != NULL && $row['HanSuDung'] != '0000-00-00'){
                                    echo date('d/m/Y', strtotime($row['HanSuDung']));
                                } else {
                                    echo "<span style='color:#ccc;'>--</span>";
                                }
                            ?>
                        </td>
                        <td>
                            <a href="http://localhost/Baitaplon/Sanpham/Sua/<?php echo $row['MaSP']; ?>" class="btn-custom btn-orange" style="padding: 5px 10px; font-size: 12px;" title="Sửa">
                                <i class="fas fa-pen"></i>
                            </a>
                            <a href="http://localhost/Baitaplon/Sanpham/Xoa/<?php echo $row['MaSP']; ?>" onclick="return confirm('Bạn có chắc muốn xóa sản phẩm này không?')" class="btn-custom btn-red" style="padding: 5px 10px; font-size: 12px;" title="Xóa">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                <?php 
                    }
                } else {
                    echo "<tr><td colspan='9' style='text-align:center; padding: 20px; color: #777;'>Không tìm thấy sản phẩm nào!</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<div id="importModal" style="display:none; position: fixed; z-index: 999; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5);">
    <div style="background-color: #fefefe; margin: 10% auto; padding: 20px; border: 1px solid #888; width: 450px; border-radius: 8px; position: relative; box-shadow: 0 4px 8px rgba(0,0,0,0.2);">
        <span onclick="document.getElementById('importModal').style.display='none'" 
              style="color: #aaa; float: right; font-size: 28px; font-weight: bold; cursor: pointer;">&times;</span>
        
        <h3 style="color: #217346; margin-top: 0; border-bottom: 1px solid #eee; padding-bottom: 10px;">
            <i class="fas fa-file-excel"></i> Nhập Sản Phẩm từ Excel
        </h3>
        
        <p style="font-size: 13px; color: #555; background: #e8f5e9; padding: 10px; border-radius: 4px;">
            <b><i class="fas fa-info-circle"></i> Hướng dẫn:</b><br>
            Bạn có thể nhập trực tiếp file Excel <b>(.xlsx, .xls)</b><br>
            Thứ tự cột: <i>A: Mã | B: Tên | C: Mã DM | D: Mã NCC | E: Giá | F: Số lượng | G: Ảnh | <b>H: HSD</b></i>
        </p>
        
        <form action="http://localhost/Baitaplon/Sanpham/NhapExcel" method="post" enctype="multipart/form-data">
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
        var modal = document.getElementById('importModal');
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
</script>