<style>
    /* =========================================
       1. CSS CHO GIAO DIỆN MÀN HÌNH (SCREEN)
       ========================================= */
    body {
        background-color: #f5f7fa; /* Màu nền web xám nhẹ cho nổi bật hóa đơn */
    }

    .invoice-container {
        max-width: 850px;
        margin: 30px auto;
        background: #fff;
        padding: 40px;
        border-radius: 8px;
        box-shadow: 0 5px 25px rgba(0,0,0,0.1); /* Đổ bóng tạo hiệu ứng tờ giấy nổi */
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        color: #333;
        position: relative;
    }

    /* Header: Logo và Tên siêu thị */
    .invoice-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        border-bottom: 2px solid #eee;
        padding-bottom: 20px;
        margin-bottom: 30px;
    }

    .brand-section h1 {
        margin: 0;
        color: #1a237e; /* Màu xanh thương hiệu */
        font-size: 28px;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .brand-section p {
        margin: 5px 0 0;
        font-size: 14px;
        color: #666;
    }

    .invoice-meta {
        text-align: right;
    }

    .invoice-meta h2 {
        margin: 0;
        font-size: 24px;
        color: #333;
        text-transform: uppercase;
    }

    .invoice-meta .status {
        display: inline-block;
        margin-top: 10px;
        padding: 5px 15px;
        background: #e8f5e9;
        color: #2e7d32;
        border-radius: 20px;
        font-size: 13px;
        font-weight: bold;
        border: 1px solid #c8e6c9;
    }

    /* Info: Thông tin 2 bên */
    .invoice-info {
        display: flex;
        justify-content: space-between;
        margin-bottom: 30px;
    }

    .info-col {
        width: 48%;
    }

    .info-title {
        font-size: 13px;
        color: #888;
        text-transform: uppercase;
        font-weight: bold;
        margin-bottom: 10px;
        border-bottom: 1px solid #eee;
        padding-bottom: 5px;
    }

    .info-content p {
        margin: 5px 0;
        font-size: 15px;
    }
    .info-content strong {
        color: #000;
    }

    /* Table: Danh sách hàng */
    .table-invoice {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 30px;
    }

    .table-invoice th {
        background-color: #f8f9fa;
        color: #444;
        font-weight: bold;
        padding: 12px;
        text-align: left;
        border-bottom: 2px solid #ddd;
        text-transform: uppercase;
        font-size: 13px;
    }

    .table-invoice td {
        padding: 12px;
        border-bottom: 1px solid #eee;
        font-size: 14px;
    }

    .text-center { text-align: center; }
    .text-right { text-align: right; }

    /* Totals: Tổng tiền */
    .invoice-total {
        width: 100%;
        display: flex;
        justify-content: flex-end;
    }

    .total-box {
        width: 300px;
    }

    .total-row {
        display: flex;
        justify-content: space-between;
        padding: 8px 0;
        font-size: 15px;
    }

    .final-total {
        font-size: 20px;
        font-weight: bold;
        color: #d32f2f; /* Màu đỏ cho tổng tiền */
        border-top: 2px solid #333;
        padding-top: 10px;
        margin-top: 10px;
    }

    /* Footer: Chữ ký & Cảm ơn */
    .invoice-footer {
        margin-top: 50px;
        text-align: center;
        color: #777;
        font-size: 14px;
    }

    .signature-section {
        display: flex;
        justify-content: space-between;
        margin-top: 40px;
        margin-bottom: 60px;
        page-break-inside: avoid; /* Tránh bị ngắt trang khi in */
    }
    
    .sig-box {
        text-align: center;
        width: 200px;
    }
    .sig-title { font-weight: bold; margin-bottom: 60px; }

    /* Nút bấm */
    .action-buttons {
        text-align: center;
        margin-top: 30px;
    }
    .btn-print {
        background: #1a237e;
        color: white;
        padding: 12px 30px;
        border: none;
        border-radius: 5px;
        font-size: 16px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: 0.3s;
    }
    .btn-print:hover { background: #0d1b6e; box-shadow: 0 4px 10px rgba(0,0,0,0.2); }
    .btn-back {
        background: #fff;
        color: #555;
        border: 1px solid #ddd;
        padding: 11px 25px;
        border-radius: 5px;
        text-decoration: none;
        margin-right: 15px;
        transition: 0.3s;
    }
    .btn-back:hover { background: #f1f1f1; }

    /* =========================================
       2. CSS QUAN TRỌNG: CẤU HÌNH KHI IN
       ========================================= */
    @media print {
        body * { visibility: hidden; } /* Ẩn tất cả */
        
        .invoice-container, .invoice-container * {
            visibility: visible; /* Hiện hóa đơn */
        }

        .invoice-container {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            margin: 0;
            padding: 0;
            box-shadow: none;
            border: none;
            max-width: 100%;
        }

        .action-buttons { display: none !important; } /* Ẩn nút khi in */
        
        /* Đảm bảo màu sắc khi in */
        .brand-section h1 { color: #000 !important; }
        .invoice-meta .status { border: 1px solid #000; color: #000; background: none; }
        .table-invoice th { border-bottom: 1px solid #000 !important; color: #000 !important; }
        .final-total { color: #000 !important; }
    }
</style>

<div class="invoice-container">
    
    <div class="invoice-header">
        <div class="brand-section">
            <h1><i class="fas fa-shopping-basket"></i>WIN MART</h1>
            <p>54 Triều Khúc, Thanh Xuân, Hà Nội</p>
            <p>Hotline: 1900 888 888 | Email: contact@anlacmart.com</p>
        </div>
        <div class="invoice-meta">
            <h2>HÓA ĐƠN BÁN HÀNG</h2>
            <p>Số: <strong>#<?php echo $data['donhang']['MaHD']; ?></strong></p>
            <p>Ngày: <?php echo date('d/m/Y', strtotime($data['donhang']['NgayLap'])); ?></p>
            <span class="status">ĐÃ THANH TOÁN</span>
        </div>
    </div>

<div class="invoice-info">
        <div class="info-col">
            <div class="info-title">KHÁCH HÀNG</div>
            <div class="info-content">
                <p><strong>Tên:</strong> <?php echo $data['donhang']['TenKH']; ?></p>
                <p><strong>SĐT:</strong> <?php echo $data['donhang']['DienThoai']; ?></p>
                
                <p><strong>Địa chỉ:</strong> 
                    <?php 
                        $dc = $data['donhang']['DiaChi'];
                        echo ($dc != "") ? $dc : "Tại cửa hàng"; 
                    ?>
                </p>
            </div>
        </div>
        <div class="info-col text-right">
            <div class="info-title">THÔNG TIN ĐƠN HÀNG</div>
            <div class="info-content">
                <p><strong>Nhân viên bán:</strong> <?php echo $data['donhang']['HoTen']; ?></p>
                <p><strong>Ngày lập:</strong> <?php echo date('d/m/Y H:i', strtotime($data['donhang']['NgayLap'])); ?></p>
                <p><strong>Phương thức TT:</strong> Tiền mặt / Chuyển khoản</p>
            </div>
        </div>
    </div>

    <table class="table-invoice">
        <thead>
            <tr>
                <th style="width: 50px;">STT</th>
                <th>Sản Phẩm</th>
                <th class="text-right">Đơn Giá</th>
                <th class="text-center">SL</th>
                <th class="text-right">Thành Tiền</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $i = 1;
            $tongsoluong = 0;
            if(isset($data['chitiet'])){
                while($row = mysqli_fetch_array($data['chitiet'])){
                    $thanhtien = $row['DonGia'] * $row['SoLuong'];
                    $tongsoluong += $row['SoLuong'];
            ?>
                <tr>
                    <td class="text-center"><?php echo $i++; ?></td>
                    <td>
                        <span style="font-weight: 500;"><?php echo $row['TenSP']; ?></span>
                    </td>
                    <td class="text-right"><?php echo number_format($row['DonGia']); ?></td>
                    <td class="text-center"><?php echo $row['SoLuong']; ?></td>
                    <td class="text-right" style="font-weight: bold;"><?php echo number_format($thanhtien); ?></td>
                </tr>
            <?php 
                }
            }
            ?>
        </tbody>
    </table>

    <div class="invoice-total">
        <div class="total-box">
            <div class="total-row">
                <span>Tổng số lượng:</span>
                <span><?php echo $tongsoluong; ?></span>
            </div>
            <div class="total-row">
                <span>Tạm tính:</span>
                <span><?php echo number_format($data['donhang']['TongTien']); ?></span>
            </div>
            <div class="total-row">
                <span>Giảm giá:</span>
                <span>0</span>
            </div>
            <div class="total-row final-total">
                <span>TỔNG CỘNG:</span>
                <span><?php echo number_format($data['donhang']['TongTien']); ?> đ</span>
            </div>
            <div style="text-align: right; font-style: italic; font-size: 12px; margin-top: 5px;">
                (Đã bao gồm VAT)
            </div>
        </div>
    </div>

    <div class="signature-section">
        <div class="sig-box">
            <div class="sig-title">Người mua hàng</div>
            <div style="margin-top: 50px;">(Ký, họ tên)</div>
        </div>
        <div class="sig-box">
            <div class="sig-title">Người lập phiếu</div>
            <div style="margin-top: 50px;"><?php echo $data['donhang']['HoTen']; ?></div>
        </div>
    </div>

    <div class="invoice-footer">
        <p>Cảm ơn quý khách đã mua sắm tại An Lạc Mart!</p>
        <p>Hàng hóa đã mua vui lòng miễn đổi trả sau 24h.</p>
    </div>

    <div class="action-buttons">
        <a href="http://localhost/Baitaplon/Donhang" class="btn-back">
            <i class="fas fa-arrow-left"></i> Quay lại
        </a>
        <button onclick="window.print()" class="btn-print">
            <i class="fas fa-print"></i> In Hóa Đơn Ngay
        </button>
    </div>

</div>