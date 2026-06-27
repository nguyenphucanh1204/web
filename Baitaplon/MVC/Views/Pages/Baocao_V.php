<style>
    /* Card chung */
    .card-report { background: #fff; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); padding: 20px; margin-bottom: 20px; border: 1px solid #e0e0e0; height: 100%; }
    .card-title { font-weight: bold; font-size: 16px; margin-bottom: 15px; padding-bottom: 10px; border-bottom: 2px solid #f0f0f0; display: flex; justify-content: space-between; align-items: center; }
    
    /* Màu sắc tiêu đề */
    .text-top { color: #d32f2f; }      /* Đỏ cho Top bán chạy */
    .text-expire { color: #e65100; }   /* Cam cho Hết hạn */
    .text-stock { color: #1a237e; }    /* Xanh cho Tồn kho */

    /* Bảng dữ liệu */
    .table-rp { width: 100%; border-collapse: collapse; font-size: 13px; }
    .table-rp th { background: #f8f9fa; padding: 10px; text-align: left; color: #555; }
    .table-rp td { padding: 10px; border-bottom: 1px solid #eee; color: #333; }
    
    /* Badge trạng thái */
    .badge-danger { background: #ffebee; color: #c62828; padding: 3px 8px; border-radius: 4px; font-weight: bold; font-size: 11px; }
    .badge-warn { background: #fff3e0; color: #ef6c00; padding: 3px 8px; border-radius: 4px; font-weight: bold; font-size: 11px; }

    /* Thanh lọc ngày */
    .filter-bar { background: #fff; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #ddd; display: flex; align-items: center; gap: 15px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); }
    .btn-view { background: #1a237e; color: white; border: none; padding: 8px 20px; border-radius: 4px; cursor: pointer; }
    .btn-export { background: #217346; color: white; border: none; padding: 8px 15px; border-radius: 4px; cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; gap: 5px; }
</style>

<div class="filter-bar">
    <div style="font-weight: bold; color: #555;"><i class="fas fa-filter"></i> Lọc thời gian (Cho mục Bán chạy):</div>
    
    <form action="http://localhost/Baitaplon/Baocao/Get_data" method="POST" style="display:flex; gap:10px; align-items:center;">
        <input type="date" name="txtTuNgay" value="<?php echo $data['tungay']; ?>" class="form-control" style="padding: 5px 10px; border:1px solid #ccc; border-radius:4px;">
        <span>đến</span>
        <input type="date" name="txtDenNgay" value="<?php echo $data['denngay']; ?>" class="form-control" style="padding: 5px 10px; border:1px solid #ccc; border-radius:4px;">
        <button type="submit" class="btn-view">Xem</button>
    </form>

    <form action="http://localhost/Baitaplon/Baocao/XuatExcel" method="POST" style="margin-left: auto;">
        <input type="hidden" name="txtTuNgayEx" value="<?php echo $data['tungay']; ?>">
        <input type="hidden" name="txtDenNgayEx" value="<?php echo $data['denngay']; ?>">
        <button type="submit" class="btn-export"><i class="fas fa-file-excel"></i> Xuất Excel Bán Chạy</button>
    </form>
</div>

<div class="row" style="display:flex; gap: 20px;">
    
    <div style="flex: 1.5;">
        <div class="card-report">
            <div class="card-title text-top">
                <span><i class="fas fa-crown"></i> Top 10 Sản Phẩm Bán Chạy</span>
            </div>
            <div style="overflow-y: auto; max-height: 450px;">
                <table class="table-rp">
                    <thead>
                        <tr>
                            <th>Tên Sản Phẩm</th>
                            <th style="text-align: center;">Đã Bán</th>
                            <th style="text-align: right;">Doanh Số</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        if(isset($data['top_sp']) && mysqli_num_rows($data['top_sp']) > 0) {
                            $stt = 0;
                            while($top = mysqli_fetch_array($data['top_sp'])){ 
                                $stt++;
                                // Top 1,2,3 có màu đặc biệt
                                $color = ($stt == 1) ? '#ffd700' : (($stt == 2) ? '#c0c0c0' : (($stt == 3) ? '#cd7f32' : '#333'));
                        ?>
                        <tr>
                            <td style="font-weight: 500;">
                                <i class="fas fa-trophy" style="color: <?php echo $color; ?>; margin-right: 5px;"></i>
                                <?php echo $top['TenSP']; ?>
                            </td>
                            <td style="text-align: center; font-weight: bold;"><?php echo $top['SoLuongBan']; ?></td>
                            <td style="text-align: right; color: #d32f2f;"><?php echo number_format($top['DoanhSo']); ?> đ</td>
                        </tr>
                        <?php } } else { echo "<tr><td colspan='3' style='text-align:center; padding:20px; color:#999;'>Chưa có dữ liệu bán hàng trong khoảng này.</td></tr>"; } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div style="flex: 1; display: flex; flex-direction: column; gap: 20px;">
        
        <div class="card-report">
            <div class="card-title text-expire">
                <span><i class="fas fa-exclamation-triangle"></i> Sắp Hết Hạn (30 ngày)</span>
            </div>
            <div style="overflow-y: auto; max-height: 200px;">
                <table class="table-rp">
                    <thead><tr><th>Sản Phẩm</th><th>HSD</th><th>Còn lại</th></tr></thead>
                    <tbody>
                        <?php 
                        if(isset($data['sap_hethan']) && mysqli_num_rows($data['sap_hethan']) > 0) {
                            while($sp = mysqli_fetch_array($data['sap_hethan'])){ 
                                $ngay_hsd = strtotime($sp['HanSuDung']);
                                $ngay_ht = time();
                                $con_lai = ceil(($ngay_hsd - $ngay_ht) / (60 * 60 * 24));
                        ?>
                        <tr>
                            <td><?php echo $sp['TenSP']; ?></td>
                            <td><?php echo date('d/m/Y', $ngay_hsd); ?></td>
                            <td><span class="badge-danger"><?php echo $con_lai; ?> ngày</span></td>
                        </tr>
                        <?php } } else { echo "<tr><td colspan='3' style='text-align:center; color:#999;'>Không có sản phẩm nào sắp hết hạn.</td></tr>"; } ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-report" style="flex:1;">
            <div class="card-title text-stock">
                <span><i class="fas fa-box-open"></i> Cảnh Báo Tồn Kho (<= 10)</span>
            </div>
            <div style="overflow-y: auto; max-height: 200px;">
                <table class="table-rp">
                    <thead><tr><th>Sản Phẩm</th><th style="text-align:center;">Tồn</th><th>Trạng thái</th></tr></thead>
                    <tbody>
                        <?php 
                        if(isset($data['tonkho_thap']) && mysqli_num_rows($data['tonkho_thap']) > 0) {
                            while($kho = mysqli_fetch_array($data['tonkho_thap'])){ 
                        ?>
                        <tr>
                            <td><?php echo $kho['TenSP']; ?></td>
                            <td style="text-align: center; font-weight: bold; color: red;"><?php echo $kho['SoLuongTon']; ?></td>
                            <td><span class="badge-warn">Cần nhập</span></td>
                        </tr>
                        <?php } } else { echo "<tr><td colspan='3' style='text-align:center; color:#999;'>Kho hàng ổn định.</td></tr>"; } ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>