<style>
    /* CSS Chung */
    .card-custom { background: #fff; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); border: 1px solid #e0e0e0; overflow: hidden; margin-bottom: 20px; }
    .card-header-custom { padding: 15px 20px; font-weight: bold; font-size: 16px; color: white; display: flex; align-items: center; gap: 10px; }
    .card-body-custom { padding: 20px; }
    
    /* Màu sắc riêng cho Kiểm Kê */
    .bg-orange { background: #fd7e14; }
    .bg-red { background: #dc3545; }
    .bg-dark { background: #343a40; }

    /* Form & Input */
    .form-group { margin-bottom: 20px; }
    .form-label { font-weight: 600; display: block; margin-bottom: 8px; color: #444; }
    .form-control { width: 100%; padding: 10px 15px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; box-sizing: border-box; }

    /* Button */
    .btn-action { width: 100%; padding: 10px; border: none; border-radius: 6px; font-weight: bold; cursor: pointer; color: white; text-decoration: none; display: inline-flex; justify-content: center; align-items: center; gap: 5px;}
    .btn-add { background: #fd7e14; } 
    .btn-save { background: #dc3545; }
    .btn-excel { background: #217346; width: auto; padding: 8px 15px; }
    .btn-import { background: #1d6f42; width: auto; padding: 8px 15px; }
    .btn-search { background: #343a40; width: auto; padding: 8px 15px; }

    /* Table */
    .table-bill { width: 100%; border-collapse: collapse; margin-top: 10px; }
    .table-bill th { background: #f8f9fa; padding: 12px; text-align: left; font-size: 13px; color: #555; border-bottom: 2px solid #ddd; }
    .table-bill td { padding: 12px; border-bottom: 1px solid #eee; color: #333; font-size: 14px; }
</style>

<div style="margin-bottom: 20px;">
    <a href="http://localhost/Baitaplon/Sanpham" class="btn-action" style="background: #6c757d; width: auto; padding: 10px 20px;">
        <i class="fas fa-arrow-left"></i> Quay lại Danh Sách Sản Phẩm
    </a>
</div>

<div class="row" style="display:flex; gap: 25px; margin-bottom: 30px;">
    
    <div class="col-left" style="flex: 1;">
        <div class="card-custom">
            <div class="card-header-custom bg-orange">
                <i class="fas fa-search"></i> Chọn Sản Phẩm Kiểm Tra
            </div>
            <div class="card-body-custom">
                <form action="http://localhost/Baitaplon/Khohang/ThemKiem" method="POST">
                    <div class="form-group">
                        <label class="form-label">Sản Phẩm:</label>
                        <select name="ddlSanPham" class="form-control" required>
                            <?php while($row = mysqli_fetch_array($data['sp'])){ ?>
                                <option value="<?php echo $row['MaSP'] ?>">
                                    <?php echo $row['TenSP'] ?> (Máy đang báo: <?php echo $row['SoLuongTon'] ?>)
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Số Lượng Thực Tế (Đếm được):</label>
                        <input type="number" name="txtThucTe" class="form-control" required min="0" placeholder="Nhập số lượng thực...">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Lý Do Chênh Lệch:</label>
                        <input type="text" name="txtLyDo" class="form-control" placeholder="VD: Hư hỏng, mất mát, nhập sai...">
                    </div>
                    <button type="submit" name="btnThem" class="btn-action btn-add">
                        <i class="fas fa-plus-circle"></i> Thêm Vào Phiếu Kiểm
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-right" style="flex: 1.5;">
        <div class="card-custom">
            <div class="card-header-custom bg-red">
                <i class="fas fa-clipboard-check"></i> Phiếu Kiểm Kê (Chưa lưu)
            </div>
            <div class="card-body-custom">
                <form action="http://localhost/Baitaplon/Khohang/LuuPhieuKiem" method="POST">
                    <div class="form-group">
                        <label class="form-label">Ghi chú cho đợt kiểm kê này:</label>
                        <input type="text" name="txtGhiChu" class="form-control" placeholder="VD: Kiểm kho cuối tháng 12..." required>
                    </div>

                    <table class="table-bill">
                        <thead>
                            <tr>
                                <th>Sản Phẩm</th>
                                <th style="text-align: center;">Tồn Máy</th>
                                <th style="text-align: center;">Thực Tế</th>
                                <th>Lệch</th>
                                <th>Lý Do</th>
                                <th style="text-align: center;">Xóa</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if(isset($_SESSION['gio_kiem']) && count($_SESSION['gio_kiem']) > 0){
                                foreach($_SESSION['gio_kiem'] as $key => $item){
                                    $lech = $item['tonthuc'] - $item['tonmay'];
                                    $color = ($lech < 0) ? 'red' : (($lech > 0) ? 'green' : 'gray');
                            ?>
                                <tr>
                                    <td><?php echo $item['ten']; ?></td>
                                    <td style="text-align: center;"><?php echo $item['tonmay']; ?></td>
                                    <td style="text-align: center; font-weight:bold;"><?php echo $item['tonthuc']; ?></td>
                                    <td style="color: <?php echo $color; ?>; font-weight:bold;">
                                        <?php echo ($lech > 0) ? '+'.$lech : $lech; ?>
                                    </td>
                                    <td><?php echo $item['lydo']; ?></td>
                                    <td style="text-align: center;">
                                        <a href="http://localhost/Baitaplon/Khohang/XoaKiem/<?php echo $key; ?>" style="color: #ff4d4f;"><i class="fas fa-trash-alt"></i></a>
                                    </td>
                                </tr>
                            <?php } } else { echo "<tr><td colspan='6' style='text-align:center; padding: 20px; color: #999;'>Chưa có sản phẩm nào</td></tr>"; } ?>
                        </tbody>
                    </table>

                    <div style="margin-top: 25px;">
                        <button type="submit" class="btn-action btn-save">
                            <i class="fas fa-save"></i> CÂN BẰNG KHO & LƯU PHIẾU
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
            <div class="card-header-custom bg-dark">
                <i class="fas fa-history"></i> Lịch Sử Kiểm Kê
            </div>
            <div class="card-body-custom">
                
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                    <form action="http://localhost/Baitaplon/Khohang/KiemKe" method="POST" style="display:flex; gap: 10px; flex: 1;">
                        <input type="text" name="txtTimKiem" class="form-control" placeholder="Tìm theo Mã phiếu hoặc Ghi chú..." 
                               value="<?php echo isset($data['keyword']) ? $data['keyword'] : '' ?>" style="max-width: 300px;">
                        <button type="submit" name="btnTimKiem" class="btn-action btn-search"><i class="fas fa-search"></i> Tìm</button>
                        <a href="http://localhost/Baitaplon/Khohang/KiemKe" class="btn-action" style="background:#777; width: auto; padding: 8px 15px;"><i class="fas fa-sync"></i></a>
                    </form>

                    <div style="display: flex; gap: 10px;">
                        <a href="http://localhost/Baitaplon/Khohang/XuatExcelKiemKe" class="btn-action btn-excel"><i class="fas fa-file-excel"></i> Xuất Excel</a>
                        <button onclick="document.getElementById('modalImportKiem').style.display='block'" class="btn-action btn-import"><i class="fas fa-file-upload"></i> Nhập Excel</button>
                    </div>
                </div>

                <table class="table-bill">
                    <thead>
                        <tr>
                            <th style="width: 80px;">Mã Phiếu</th>
                            <th style="width: 200px;">Ghi Chú</th>
                            <th>Chi Tiết Kiểm (Máy -> Thực)</th>
                            <th style="width: 150px;">Ngày Kiểm</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if(isset($data['lichsu']) && mysqli_num_rows($data['lichsu']) > 0){
                            while($row = mysqli_fetch_array($data['lichsu'])){
                        ?>
                            <tr style="border-bottom: 1px solid #e0e0e0;">
                                <td style="font-weight: bold; color: #dc3545; vertical-align: top;">
                                    #PK<?php echo str_pad($row['MaPK'], 3, '0', STR_PAD_LEFT); ?>
                                </td>
                                <td style="vertical-align: top; font-weight: 500;">
                                    <?php echo $row['GhiChu']; ?>
                                    <br><small style="color:#777;">(NV: <?php echo $row['HoTen']; ?>)</small>
                                </td>
                                <td style="vertical-align: top; font-size: 13px; color: #333;">
                                    <?php echo $row['ChiTietKiem']; ?>
                                </td>
                                <td style="vertical-align: top; color: #666; font-size: 13px;">
                                    <?php echo date('d/m/Y H:i', strtotime($row['NgayKiem'])); ?>
                                </td>
                            </tr>
                        <?php 
                            }
                        } else {
                            echo "<tr><td colspan='4' style='text-align:center; padding:30px; color:#999;'>Không tìm thấy phiếu kiểm kê nào!</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div id="modalImportKiem" style="display:none; position: fixed; z-index: 999; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5);">
    <div style="background-color: #fefefe; margin: 10% auto; padding: 20px; border: 1px solid #888; width: 450px; border-radius: 8px; position: relative;">
        <span onclick="document.getElementById('modalImportKiem').style.display='none'" style="float: right; font-size: 28px; cursor: pointer;">&times;</span>
        <h3 style="color: #217346; margin-top: 0;">Nhập Lịch Sử Kiểm Kê</h3>
        <p style="font-size: 13px; color: #666;">File Excel cần 4 cột: <b>MaPK (Bỏ) | Ghi Chú | Chi Tiết | Ngày</b></p>
        <form action="http://localhost/Baitaplon/Khohang/NhapExcelKiemKe" method="post" enctype="multipart/form-data">
            <input type="file" name="fileExcel" required accept=".xlsx, .xls" style="margin-bottom: 15px; width: 100%; padding: 10px; border: 1px solid #ccc;">
            <button type="submit" name="btnNhapExcel" class="btn-action btn-import" style="width: 100%;">Tải lên hệ thống</button>
        </form>
    </div>
</div>

<script>
    window.onclick = function(event) {
        if (event.target == document.getElementById('modalImportKiem')) {
            document.getElementById('modalImportKiem').style.display = "none";
        }
    }
</script>