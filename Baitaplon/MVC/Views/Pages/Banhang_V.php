<style>
    /* --- CSS CHO CỘT TRÁI (SẢN PHẨM) --- */
    /* 1. Thanh tìm kiếm dài tối đa */
    .search-box { 
        display: flex; 
        width: 100%; 
        margin-bottom: 15px; 
    }
    
    .input-search { 
        flex: 1; /* Tự động giãn hết khổ */
        padding: 10px 15px;
        border: 1px solid #1a237e; /* Viền màu xanh đồng bộ */
        border-right: none; /* Bỏ viền phải để nối liền nút */
        border-radius: 6px 0 0 6px;
        outline: none;
        font-size: 14px;
    }
    .input-search:focus { background: #f8f9fa; }

    .btn-search { 
        background: #1a237e; color: white; border: 1px solid #1a237e;
        padding: 0 25px; /* Nút to vừa phải */
        border-radius: 0 6px 6px 0; 
        cursor: pointer; font-weight: bold; white-space: nowrap;
    }
    .btn-search:hover { background: #303f9f; }
    
    .btn-clear {
        background: #dc3545; color: white; border: none;
        padding: 0 15px; border-radius: 4px; margin-left: 5px;
        cursor: pointer; display: flex; align-items: center;
    }

    .product-list { 
        display: grid; 
        grid-template-columns: repeat(auto-fill, minmax(130px, 1fr)); 
        gap: 15px; 
        max-height: 80vh; 
        overflow-y: auto; 
    }
    .product-item { 
        border: 1px solid #eee; border-radius: 8px; padding: 10px; 
        text-align: center; box-shadow: 0 2px 5px rgba(0,0,0,0.05); background: #fff;
        transition: 0.2s; display: flex; flex-direction: column; justify-content: space-between;
    }
    .product-item:hover { transform: translateY(-3px); box-shadow: 0 5px 15px rgba(0,0,0,0.1); border-color: #007bff; }
    .prod-img { width: 100%; height: 100px; object-fit: contain; border-radius: 4px; margin-bottom: 5px; }
    .prod-name { font-weight: bold; font-size: 13px; margin: 5px 0; line-height: 1.4; height: 36px; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; }
    .prod-price { color: #d32f2f; font-weight: bold; font-size: 14px; margin-bottom: 8px; }

    /* --- CSS CHO CỘT PHẢI (THANH TOÁN) --- */
    .payment-group { display: flex; gap: 8px; margin-bottom: 15px; flex-wrap: wrap; }
    .payment-option { display: none; } 
    .payment-label {
        flex: 1; min-width: 70px; text-align: center; padding: 8px 5px; 
        border: 1px solid #ddd; border-radius: 6px; cursor: pointer;
        font-weight: 600; color: #555; background: #fff; transition: 0.2s; font-size: 12px;
        display: flex; flex-direction: column; align-items: center; gap: 5px;
    }
    .payment-option:checked + .payment-label {
        background: #e3f2fd; color: #0d47a1; border-color: #0d47a1; 
        box-shadow: 0 0 0 2px rgba(13, 71, 161, 0.2);
    }
    .payment-label i { font-size: 16px; }

    /* QR Code Card */
    .qr-card {
        display: none; background: #212121; color: #fff; padding: 15px; border-radius: 10px;
        align-items: center; gap: 15px; margin-bottom: 15px; box-shadow: 0 4px 10px rgba(0,0,0,0.2);
    }
    .qr-img-box { width: 90px; height: 90px; background: #fff; padding: 5px; border-radius: 6px; display: flex; align-items: center; justify-content: center; cursor: zoom-in; }
    .qr-image { width: 100%; height: 100%; object-fit: contain; }
    .qr-info { flex: 1; overflow: hidden; } 
    .qr-bank-name { font-weight: bold; font-size: 15px; color: #4fc3f7; margin-bottom: 3px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .qr-note { font-size: 12px; color: #bbb; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

    /* Vùng tiền mặt */
    .money-area { display: block; background: #f8f9fa; padding: 10px; border-radius: 8px; }
    .money-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 5px; margin-top: 10px; }
    .btn-money { background: #37474f; color: white; border: none; padding: 8px 0; border-radius: 4px; cursor: pointer; font-size: 12px; transition: 0.2s; }
    .btn-money:hover { background: #546e7a; }
    .input-money { font-size: 18px; font-weight: bold; color: #2e7d32; text-align: right; }
    .return-money { font-size: 18px; font-weight: bold; color: #c62828; text-align: right; background: #ffebee; }
    
    .form-sm { font-size: 13px; padding: 8px; }
    label { font-size: 13px; font-weight: 600; margin-bottom: 3px; display: block;}

    /* Modal phóng to QR */
    .qr-modal {
        display: none; position: fixed; z-index: 9999; left: 0; top: 0; width: 100%; height: 100%; 
        background-color: rgba(0,0,0,0.85); justify-content: center; align-items: center; animation: fadeIn 0.3s;
    }
    .qr-modal-content { max-width: 90%; max-height: 90%; border-radius: 10px; box-shadow: 0 0 20px rgba(255,255,255,0.2); }
    .close-qr { position: absolute; top: 20px; right: 35px; color: #f1f1f1; font-size: 40px; font-weight: bold; cursor: pointer; }
    @keyframes fadeIn { from {opacity: 0} to {opacity: 1} }
</style>

<div class="row" style="display:flex; gap:20px; height: calc(100vh - 80px);">
    
    <div class="col-left" style="flex: 1; display: flex; flex-direction: column;">
        <div class="card" style="height: 100%; display: flex; flex-direction: column;">
            <div class="card-header">
                <h3><i class="fas fa-boxes"></i> Danh Sách Sản Phẩm</h3>
            </div>
            <div class="card-body" style="flex: 1; overflow: hidden; display: flex; flex-direction: column;">
                
                <form action="http://localhost/Baitaplon/Banhang/Get_data" method="POST" class="search-box">
                    <input type="text" name="txtTimKiem" class="input-search" 
                           placeholder="Nhập tên hoặc mã sản phẩm (F4)..." 
                           value="<?php echo isset($data['keyword']) ? $data['keyword'] : ''; ?>">
                    <button type="submit" name="btnTimKiem" class="btn-search">
                        <i class="fas fa-search"></i> TÌM
                    </button>
                    <?php if(isset($data['keyword']) && $data['keyword'] != '') { ?>
                        <a href="http://localhost/Baitaplon/Banhang" class="btn-clear" title="Xóa tìm kiếm">X</a>
                    <?php } ?>
                </form>

                <div class="product-list">
                    <?php
                    if(isset($data['sp'])){
                        while($row = mysqli_fetch_array($data['sp'])){
                    ?>
                        <div class="product-item">
                            <img src="http://localhost/Baitaplon/Public/Images/<?php echo $row['HinhAnh'] ?>" class="prod-img">
                            <div>
                                <div class="prod-name" title="<?php echo $row['TenSP']; ?>"><?php echo $row['TenSP']; ?></div>
                                <div class="prod-price"><?php echo number_format($row['GiaBan']); ?> đ</div>
                            </div>
                            <a href="http://localhost/Baitaplon/Banhang/ThemGioHang/<?php echo $row['MaSP']; ?>" 
                               class="btn-primary" style="display:block; font-size:12px; padding:6px;">
                               + Thêm
                            </a>
                        </div>
                    <?php
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-right" style="flex: 1; min-width: 350px;">
        <div class="card" style="height:100%; display: flex; flex-direction: column;">
            <div class="card-header" style="background:#e3f2fd; color:#1a237e; padding: 10px 15px;">
                <h3 style="font-size: 16px; margin:0;"><i class="fas fa-shopping-cart"></i> Hóa Đơn</h3>
            </div>
            <div class="card-body" style="flex: 1; overflow-y: auto; padding: 15px;">
                <form action="http://localhost/Baitaplon/Banhang/ThanhToan" method="post" style="height: 100%; display: flex; flex-direction: column;">
                    
                    <div style="display: flex; gap: 10px; margin-bottom: 10px;">
                        <div style="flex: 1;">
                            <label>Khách hàng:</label>
                            <select name="ddlKhachHang" class="form-control form-sm" required>
                                <?php while($row_kh = mysqli_fetch_array($data['kh'])){ ?>
                                    <option value="<?php echo $row_kh['MaKH'] ?>"><?php echo $row_kh['TenKH'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div style="flex: 1;">
                            <label>Nhân viên:</label>
                            <select name="ddlNhanVien" class="form-control form-sm">
                                <?php while($row_nv = mysqli_fetch_array($data['nv'])){ ?>
                                    <option value="<?php echo $row_nv['MaNV'] ?>"><?php echo $row_nv['HoTen'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                
                    <div style="flex: 1; min-height: 120px; max-height: 250px; overflow-y:auto; border:1px solid #eee; margin-bottom:10px; border-radius: 4px;">
                        <table style="width:100%; font-size:12px; border-collapse: collapse;">
                            <tr style="background:#f5f5f5; font-weight:bold; position: sticky; top: 0;">
                                <td style="padding:6px;">SP</td><td style="padding:6px; text-align:center;">SL</td><td style="padding:6px; text-align:right;">Tiền</td><td style="width:20px;"></td>
                            </tr>
                            <?php
                            $tongtien = 0;
                            if(isset($_SESSION['giohang'])){
                                foreach($_SESSION['giohang'] as $item){
                                    $thanhtien = $item['gia'] * $item['soluong']; $tongtien += $thanhtien;
                                    echo "<tr style='border-bottom:1px solid #eee;'>
                                        <td style='padding:6px;'>{$item['ten']}</td>
                                        <td style='padding:6px; text-align:center; font-weight:bold;'>{$item['soluong']}</td>
                                        <td style='padding:6px; text-align:right;'>".number_format($thanhtien)."</td>
                                        <td style='padding:6px;'><a href='http://localhost/Baitaplon/Banhang/XoaGioHang/{$item['id']}' style='color:red;'>×</a></td>
                                    </tr>";
                                }
                            } 
                            ?>
                        </table>
                    </div>

                    <?php
                        $giamgia = isset($_SESSION['giamgia']) ? $_SESSION['giamgia']['SoTien'] : 0;
                        $thanhtoan = max(0, $tongtien - $giamgia);
                    ?>
                    <div style="background:#fff3cd; padding:5px; margin-bottom:10px; display:flex; gap:5px; border-radius: 4px;">
                        <select name="txtMaGiamGia" class="form-control form-sm" style="font-size: 13px;">
    <option value="">-- Chọn mã khuyến mãi --</option>
    <?php 
    if(isset($data['dskm'])){
        while($km = mysqli_fetch_array($data['dskm'])){
            // Hiển thị dạng: TÊN MÃ (-SỐ TIỀN)
            // Ví dụ: TET2025 (-50,000đ)
            echo '<option value="'.$km['TenMa'].'">';
            echo $km['TenMa'] . ' (-' . number_format($km['SoTienGiam']) . 'đ)';
            echo '</option>';
        }
    }
    ?>
</select>
                        <button type="submit" name="btnApDung" formaction="http://localhost/Baitaplon/Banhang/ApDungMa" class="btn-primary" style="background:#ffc107; border:none; color:#333; font-size: 12px; padding: 0 10px; white-space:nowrap;">Áp dụng</button>
                    </div>

                    <div style="text-align:right; border-top:1px solid #eee; padding-top:5px; margin-bottom: 10px;">
                        <p style="margin:2px 0; font-size: 12px; color: #666;">Tổng: <b><?php echo number_format($tongtien); ?></b> - Giảm: <b style="color:green"><?php echo number_format($giamgia); ?></b></p>
                        <h3 style="color:#d32f2f; font-size:18px; margin: 5px 0;">KHÁCH TRẢ: <span id="spanCanTra"><?php echo number_format($thanhtoan); ?></span></h3>
                        <input type="hidden" id="txtCanTra" value="<?php echo $thanhtoan; ?>">
                    </div>

                    <label>Phương thức:</label>
                    <div class="payment-group">
                        <?php 
                        if(isset($data['pt'])){
                            $first = true;
                            while($pt = mysqli_fetch_array($data['pt'])){ 
                                $checked = $first ? 'checked' : ''; $first = false;
                                $icon = 'fa-money-bill-wave';
                                $name_lower = mb_strtolower($pt['TenPT'], 'UTF-8');
                                if(strpos($name_lower, 'khoản')!==false) $icon = 'fa-university';
                                if(strpos($name_lower, 'momo')!==false) $icon = 'fa-qrcode';
                                $qrLink = !empty($pt['HinhAnh']) ? "http://localhost/Baitaplon/Public/Images/".$pt['HinhAnh'] : "";
                        ?>
                            <input type="radio" name="radPhuongThuc" id="pt_<?php echo $pt['MaPT']; ?>" 
                                   class="payment-option" value="<?php echo $pt['MaPT']; ?>" <?php echo $checked; ?>
                                   data-qr="<?php echo $qrLink; ?>"
                                   data-name="<?php echo $name_lower; ?>"
                                   onchange="checkPaymentMethod()">
                            <label for="pt_<?php echo $pt['MaPT']; ?>" class="payment-label">
                                <i class="fas <?php echo $icon; ?>"></i> <?php echo $pt['TenPT']; ?>
                            </label>
                        <?php }} ?>
                    </div>

                    <div id="areaQR" class="qr-card">
                        <div class="qr-img-box" title="Bấm để phóng to" onclick="zoomQR(document.getElementById('imgQR').src)">
                            <img id="imgQR" src="" class="qr-image" onerror="this.src='https://img.icons8.com/ios/100/ffffff/qr-code--v1.png'">
                        </div>
                        <div class="qr-info">
                            <div class="qr-bank-name" id="lblBankName">NGÂN HÀNG</div>
                            <div class="qr-note">Nội dung: <span style="color:#fff;">DH<?php echo date('dmHis'); ?></span></div>
                            <div style="margin-top: 5px; font-size: 11px; color: #aaa;"><i>*Bấm vào QR để phóng to</i></div>
                        </div>
                    </div>

                    <div id="areaTienMat" class="money-area">
                        <div class="row" style="display:flex; gap:5px;">
                            <div style="flex:1">
                                <label>Khách đưa:</label>
                                <input type="text" id="txtKhachDua" class="form-control form-sm input-money" value="0" onkeyup="tinhTienThua()" onclick="this.select()">
                            </div>
                            <div style="flex:1">
                                <label>Tiền thừa:</label>
                                <input type="text" id="txtTienThua" class="form-control form-sm return-money" readonly value="0">
                            </div>
                        </div>
                        <div class="money-grid">
                            <button type="button" class="btn-money" onclick="addMoney(1000)">1k</button>
                            <button type="button" class="btn-money" onclick="addMoney(2000)">2k</button>
                            <button type="button" class="btn-money" onclick="addMoney(5000)">5k</button>
                            <button type="button" class="btn-money" onclick="addMoney(10000)">10k</button>
                            <button type="button" class="btn-money" onclick="addMoney(20000)">20k</button>
                            <button type="button" class="btn-money" onclick="addMoney(50000)">50k</button>
                            <button type="button" class="btn-money" onclick="addMoney(100000)">100k</button>
                            <button type="button" class="btn-money" onclick="addMoney(200000)">200k</button>
                            <button type="button" class="btn-money" onclick="addMoney(500000)">500k</button>
                        </div>
                        <button type="button" class="btn-money" style="width:100%; margin-top:5px; background:#ef5350" onclick="resetMoney()">Xóa tiền</button>
                    </div>

                    <button type="submit" name="btnThanhToan" class="btn-primary" style="width:100%; padding:12px; font-size:15px; margin-top:auto; border-radius:30px; font-weight:bold; box-shadow:0 3px 8px rgba(0,0,0,0.2);">
                        THANH TOÁN
                    </button>
                    <a href="http://localhost/Baitaplon/Banhang/HuyDon" style="display:block; text-align:center; margin-top:10px; color:#888; font-size:12px;">Hủy đơn</a>
                </form>
            </div>
        </div>
    </div>
</div>

<div id="qrModal" class="qr-modal" onclick="closeQR()">
    <span class="close-qr" onclick="closeQR()">&times;</span>
    <img class="qr-modal-content" id="imgBigQR">
</div>

<script>
    function formatNumber(num) { return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,'); }
    function addMoney(amount) {
        var current = parseInt(document.getElementById("txtKhachDua").value.replace(/,/g, '')) || 0;
        document.getElementById("txtKhachDua").value = formatNumber(current + amount);
        tinhTienThua();
    }
    function resetMoney() { document.getElementById("txtKhachDua").value = 0; tinhTienThua(); }
    
    function tinhTienThua() {
        var khachDua = parseInt(document.getElementById("txtKhachDua").value.replace(/,/g, '')) || 0;
        var canTra = parseInt(document.getElementById("txtCanTra").value) || 0;
        var thua = khachDua - canTra;
        document.getElementById("txtTienThua").value = formatNumber(thua);
        if(thua >= 0) { document.getElementById("txtTienThua").style.color = "#2e7d32"; document.getElementById("txtTienThua").style.background = "#e8f5e9"; } 
        else { document.getElementById("txtTienThua").style.color = "#c62828"; document.getElementById("txtTienThua").style.background = "#ffebee"; }
    }

    function checkPaymentMethod() {
        var radios = document.getElementsByName('radPhuongThuc');
        var selectedQR = "", selectedName = "", selectedLabelText = "";
        
        for (var i = 0; i < radios.length; i++) {
            if (radios[i].checked) {
                selectedQR = radios[i].getAttribute('data-qr');
                selectedName = radios[i].getAttribute('data-name');
                var label = document.querySelector('label[for="' + radios[i].id + '"]');
                selectedLabelText = label.innerText.trim();
                break;
            }
        }

        var isOnlinePayment = selectedName.includes("khoản") || selectedName.includes("momo") || selectedName.includes("qr") || selectedName.includes("ví") || selectedName.includes("thẻ");

        if (isOnlinePayment) {
            document.getElementById("areaQR").style.display = "flex";
            document.getElementById("areaTienMat").style.display = "none";
            document.getElementById("lblBankName").innerText = selectedLabelText.toUpperCase();
            if(selectedQR && selectedQR !== "") { document.getElementById("imgQR").src = selectedQR; } 
            else { document.getElementById("imgQR").src = "https://img.icons8.com/ios/100/ffffff/qr-code--v1.png"; }

            var canTra = document.getElementById("txtCanTra").value;
            document.getElementById("txtKhachDua").value = formatNumber(canTra);
        } else {
            document.getElementById("areaQR").style.display = "none";
            document.getElementById("areaTienMat").style.display = "block";
            document.getElementById("txtKhachDua").value = "0";
            tinhTienThua();
        }
    }
    checkPaymentMethod();

    function zoomQR(src) {
        var modal = document.getElementById("qrModal");
        var modalImg = document.getElementById("imgBigQR");
        modal.style.display = "flex";
        modalImg.src = src;
    }

    function closeQR() {
        document.getElementById("qrModal").style.display = "none";
    }
</script>