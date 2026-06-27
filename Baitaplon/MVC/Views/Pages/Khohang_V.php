<style>
    /* CSS Chung */
    .card-custom { background: #fff; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); border: 1px solid #e0e0e0; overflow: hidden; margin-bottom: 20px; }
    .card-header-custom { padding: 15px 20px; font-weight: bold; font-size: 16px; color: white; display: flex; align-items: center; gap: 10px; }
    .card-body-custom { padding: 20px; }
    
    /* Màu sắc */
    .bg-green { background: #28a745; }
    .bg-blue { background: #007bff; }
    .bg-purple { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }

    /* Form & Input */
    .form-group { margin-bottom: 20px; }
    .form-label { font-weight: 600; display: block; margin-bottom: 8px; color: #444; }
    .form-control { width: 100%; padding: 10px 15px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; box-sizing: border-box; transition: 0.3s; }
    .form-control:focus { border-color: #007bff; box-shadow: 0 0 5px rgba(0,123,255,0.2); outline: none; }

    /* Button */
    .btn-action { width: 100%; padding: 10px; border: none; border-radius: 6px; font-weight: bold; cursor: pointer; font-size: 14px; display: flex; align-items: center; justify-content: center; gap: 8px; color: white; text-decoration: none;}
    .btn-add { background: #28a745; } .btn-add:hover { background: #218838; }
    .btn-save { background: #007bff; } .btn-save:hover { background: #0069d9; }
    .btn-excel { background: #217346; width: auto; padding: 8px 15px; }
    .btn-import { background: #1d6f42; width: auto; padding: 8px 15px; }
    .btn-search { background: #1a237e; width: auto; padding: 8px 15px; }

    /* Table */
    .table-bill { width: 100%; border-collapse: collapse; margin-top: 10px; }
    .table-bill th { background: #f8f9fa; padding: 12px; text-align: left; font-size: 13px; color: #555; border-bottom: 2px solid #ddd; }
    .table-bill td { padding: 12px; border-bottom: 1px solid #eee; color: #333; font-size: 14px; }
    .total-row { background: #e3f2fd; font-weight: bold; color: #0d47a1; }
</style>



<div class="row" style="display:flex; gap: 25px; margin-bottom: 30px;">
    
    <div class="col-left" style="flex: 1;">
        <div class="card-custom">
            <div class="card-header-custom bg-green">
                <i class="fas fa-arrow-down"></i> Nhập Hàng Vào Kho
            </div>
            <div class="card-body-custom">
                <form action="http://localhost/Baitaplon/Khohang/ThemTam" method="POST">
                    <div class="form-group">
                        <label class="form-label">Chọn Sản Phẩm:</label>
                        <select name="ddlSanPham" class="form-control" required>
                            <?php while($row = mysqli_fetch_array($data['sp'])){ ?>
                                <option value="<?php echo $row['MaSP'] ?>">
                                    <?php echo $row['TenSP'] ?> (Tồn: <?php echo $row['SoLuongTon'] ?>)
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <div style="display: flex; gap: 15px;">
                            <div style="flex: 1;">
                                <label class="form-label">Số Lượng:</label>
                                <input type="number" name="txtSoLuong" class="form-control" required min="1" value="10">
                            </div>
                            <div style="flex: 2;">
                                <label class="form-label">Giá Nhập (Vốn):</label>
                                <input type="number" name="txtGiaNhap" class="form-control" required placeholder="VNĐ">
                            </div>
                        </div>
                        <small style="color: #dc3545; display: block; margin-top: 5px; font-style: italic;">
                            <i class="fas fa-exclamation-circle"></i> Lưu ý: Nhập giá gốc nhập hàng.
                        </small>
                    </div>
                    <button type="submit" name="btnThem" class="btn-action btn-add">
                        <i class="fas fa-cart-plus"></i> Thêm Vào Phiếu
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-right" style="flex: 1.5;">
        <div class="card-custom">
            <div class="card-header-custom bg-blue">
                <i class="fas fa-file-invoice"></i> Phiếu Nhập Kho (Tạm tính)
            </div>
            <div class="card-body-custom">
                <form action="http://localhost/Baitaplon/Khohang/LuuPhieu" method="POST">
                    <div class="form-group" style="display: flex; align-items: center; gap: 10px; background: #f1f8ff; padding: 15px; border-radius: 6px;">
                        <label class="form-label" style="margin: 0; white-space: nowrap;">Nhà Cung Cấp:</label>
                        <select name="ddlNCC" class="form-control" style="background: white;">
                            <?php if(isset($data['ncc'])){ while($ncc = mysqli_fetch_array($data['ncc'])){ echo "<option value='".$ncc['MaNCC']."'>".$ncc['TenNCC']."</option>"; } } ?>
                        </select>
                    </div>

                    <table class="table-bill">
                        <thead>
                            <tr>
                                <th>Sản Phẩm</th>
                                <th style="text-align: center;">SL</th>
                                <th style="text-align: right;">Đơn Giá</th>
                                <th style="text-align: right;">Thành Tiền</th>
                                <th style="text-align: center;">Xóa</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $tongtien = 0;
                            if(isset($_SESSION['gio_nhap']) && count($_SESSION['gio_nhap']) > 0){
                                foreach($_SESSION['gio_nhap'] as $key => $item){
                                    $thanhtien = $item['soluong'] * $item['gia'];
                                    $tongtien += $thanhtien;
                            ?>
                                <tr>
                                    <td><?php echo $item['ten']; ?></td>
                                    <td style="text-align: center;"><b><?php echo $item['soluong']; ?></b></td>
                                    <td style="text-align: right;"><?php echo number_format($item['gia']); ?></td>
                                    <td style="text-align: right; font-weight: bold;"><?php echo number_format($thanhtien); ?></td>
                                    <td style="text-align: center;">
                                        <a href="http://localhost/Baitaplon/Khohang/XoaTam/<?php echo $key; ?>" style="color: #ff4d4f;"><i class="fas fa-trash-alt"></i></a>
                                    </td>
                                </tr>
                            <?php } } else { echo "<tr><td colspan='5' style='text-align:center; padding: 20px; color: #999;'>Chưa có sản phẩm nào</td></tr>"; } ?>
                            <tr class="total-row">
                                <td colspan="3" style="text-align: right; padding-right: 20px;">TỔNG CỘNG:</td>
                                <td colspan="2" style="text-align: right; font-size: 18px; color: #d32f2f;"><?php echo number_format($tongtien); ?> đ</td>
                            </tr>
                        </tbody>
                    </table>

                    <div style="margin-top: 25px;">
                        <button type="submit" class="btn-action btn-save">
                            <i class="fas fa-save"></i> LƯU KHO & CẬP NHẬT TỒN
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12" style="width: 100%;">
        <div class="card-custom">
            <div class="card-header-custom bg-purple">
                <i class="fas fa-history"></i> Lịch Sử Nhập Hàng & Tra Cứu
            </div>
            <div class="card-body-custom">
                
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                    <form action="http://localhost/Baitaplon/Khohang/Get_data" method="POST" style="display:flex; gap: 10px; flex: 1;">
                        <input type="text" name="txtTimKiem" class="form-control" placeholder="Tìm theo Mã phiếu hoặc Nhà cung cấp..." 
                               value="<?php echo isset($data['keyword']) ? $data['keyword'] : '' ?>" style="max-width: 300px;">
                        <button type="submit" name="btnTimKiem" class="btn-action btn-search"><i class="fas fa-search"></i> Tìm</button>
                        <a href="http://localhost/Baitaplon/Khohang" class="btn-action" style="background:#777; width: auto; padding: 8px 15px;"><i class="fas fa-sync"></i></a>
                    </form>

                    <div style="display: flex; gap: 10px;">
                        <a href="http://localhost/Baitaplon/Khohang/XuatExcelLichSu" class="btn-action btn-excel"><i class="fas fa-file-excel"></i> Xuất Excel</a>
                        <button onclick="document.getElementById('modalImport').style.display='block'" class="btn-action btn-import"><i class="fas fa-file-upload"></i> Nhập Excel</button>
                    </div>
                </div>

                <table class="table-bill">
                    <thead>
                        <tr>
                            <th style="width: 80px;">Mã Phiếu</th>
                            <th style="width: 150px;">Nhà Cung Cấp</th>
                            <th>Chi Tiết Nhập (Sản phẩm - SL - Giá)</th> <th style="width: 130px;">Ngày Nhập</th>
                            <th style="width: 120px; text-align: right;">Tổng Tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if(isset($data['lichsu']) && mysqli_num_rows($data['lichsu']) > 0){
                            while($row = mysqli_fetch_array($data['lichsu'])){
                        ?>
                            <tr style="border-bottom: 1px solid #e0e0e0;">
                                <td style="font-weight: bold; color: #1a237e; vertical-align: top;">
                                    #<?php echo $row['MaPN']; ?>
                                </td>
                                
                                <td style="vertical-align: top; font-weight: 500;">
    <?php echo $row['TenNCC']; ?>
</td>
                                
                                <td style="vertical-align: top; font-size: 13px; color: #333;">
                                    <?php 
                                        // Hiển thị chuỗi HTML đã tạo từ Model
                                        echo $row['ChiTietNhap']; 
                                    ?>
                                </td>
                                
                                <td style="vertical-align: top; color: #666; font-size: 13px;">
                                    <?php echo date('d/m/Y H:i', strtotime($row['NgayNhap'])); ?>
                                </td>
                                
                                <td style="vertical-align: top; font-weight: bold; color: #d32f2f; text-align: right;">
                                    <?php echo number_format($row['TongTien']); ?> đ
                                </td>
                            </tr>
                        <?php 
                            }
                        } else {
                            echo "<tr><td colspan='5' style='text-align:center; padding:30px; color:#999;'>
                                    Không tìm thấy phiếu nhập nào!
                                  </td></tr>";
                        }
                        ?>
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</div>

<div id="modalImport" style="display:none; position: fixed; z-index: 999; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5);">
    <div style="background-color: #fefefe; margin: 10% auto; padding: 20px; border: 1px solid #888; width: 450px; border-radius: 8px; position: relative;">
        <span onclick="document.getElementById('modalImport').style.display='none'" style="float: right; font-size: 28px; cursor: pointer;">&times;</span>
        <h3 style="color: #217346; margin-top: 0;">Nhập Lịch Sử Từ Excel</h3>
        <p style="font-size: 13px; color: #666;">File Excel cần 4 cột: <b>A: Mã (Bỏ qua) | B: Tên NCC | C: Chi tiết SP | D: Ngày | E: Tổng tiền</b></p>
        <form action="http://localhost/Baitaplon/Khohang/NhapExcelLichSu" method="post" enctype="multipart/form-data">
            <input type="file" name="fileExcel" required accept=".xlsx, .xls" style="margin-bottom: 15px; width: 100%; padding: 10px; border: 1px solid #ccc;">
            <button type="submit" name="btnNhapExcel" class="btn-action btn-import" style="width: 100%;">Tải lên hệ thống</button>
        </form>
    </div>
</div>

<script>
    // Đóng modal khi click ra ngoài
    window.onclick = function(event) {
        if (event.target == document.getElementById('modalImport')) {
            document.getElementById('modalImport').style.display = "none";
        }
    }
</script>