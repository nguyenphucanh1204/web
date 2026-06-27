<style>
    .success-container { max-width: 800px; margin: 30px auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
    .success-header { text-align: center; border-bottom: 2px solid #eee; padding-bottom: 20px; margin-bottom: 20px; }
    .success-header i { font-size: 60px; color: #28a745; margin-bottom: 10px; }
    .success-header h2 { color: #28a745; margin: 0; }
    
    .order-info-box { display: flex; justify-content: space-between; margin-bottom: 30px; }
    .info-col h4 { border-bottom: 1px solid #ddd; padding-bottom: 5px; margin-bottom: 10px; color: #333; }
    .info-col p { margin: 5px 0; color: #555; }

    .table-order { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
    .table-order th { background: #f8f9fa; padding: 10px; text-align: left; border-bottom: 2px solid #ddd; }
    .table-order td { padding: 10px; border-bottom: 1px solid #eee; }
    .table-order tr:last-child td { border-bottom: none; }
    
    .total-box { text-align: right; }
    .total-row { display: flex; justify-content: flex-end; margin-bottom: 5px; }
    .total-row span:first-child { width: 150px; color: #666; }
    .total-row span:last-child { width: 120px; font-weight: bold; color: #333; }
    .final-total { font-size: 20px; color: #e31d2b !important; margin-top: 10px; border-top: 1px solid #eee; padding-top: 10px; }
    
    .btn-home { display: inline-block; background: #e31d2b; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; font-weight: bold; margin-top: 20px; }
    .btn-home:hover { background: #c41925; }
</style>

<div class="success-container">
    <div class="success-header">
        <i class="fas fa-check-circle"></i>
        <h2>ĐẶT HÀNG THÀNH CÔNG!</h2>
        <p>Cảm ơn bạn đã mua hàng. Đơn hàng của bạn đã được tiếp nhận.</p>
    </div>

    <?php 
    // Kiểm tra xem có dữ liệu đơn hàng truyền sang không
    if(isset($data['OrderInfo'])) { 
        $info = $data['OrderInfo'];
    ?>
    
    <div class="order-info-box">
        <div class="info-col" style="width: 48%;">
            <h4>Thông tin người nhận</h4>
            <p><b>Họ tên:</b> <?php echo $info['khachhang']['ten'] ?></p>
            <p><b>Số điện thoại:</b> <?php echo $info['khachhang']['sdt'] ?></p>
            <p><b>Địa chỉ:</b> <?php echo $info['khachhang']['diachi'] ?></p>
            <p><b>Ghi chú:</b> <?php echo $info['khachhang']['ghichu'] ?></p>
        </div>
        <div class="info-col" style="width: 48%; text-align: right;">
            <h4>Thời gian đặt hàng</h4>
            <p><?php echo date("d/m/Y H:i:s"); ?></p>
            <p>Phương thức: Thanh toán khi nhận hàng (COD)</p>
        </div>
    </div>

    <h4>Chi tiết đơn hàng</h4>
    <table class="table-order">
        <thead>
            <tr>
                <th>Sản phẩm</th>
                <th style="text-align: center;">SL</th>
                <th style="text-align: right;">Đơn giá</th>
                <th style="text-align: right;">Thành tiền</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($info['products'] as $item): ?>
            <tr>
                <td><?php echo $item['name'] ?></td>
                <td style="text-align: center;"><?php echo $item['qty'] ?></td>
                <td style="text-align: right;"><?php echo number_format($item['price']) ?>đ</td>
                <td style="text-align: right;"><?php echo number_format($item['price'] * $item['qty']) ?>đ</td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="total-box">
        <div class="total-row">
            <span>Tạm tính:</span>
            <span><?php echo number_format($info['bill']['tam_tinh']) ?>đ</span>
        </div>
        
        <?php if($info['bill']['giam_gia'] > 0): ?>
        <div class="total-row">
            <span>Voucher (<?php echo $info['bill']['ten_voucher'] ?>):</span>
            <span style="color: green;">-<?php echo number_format($info['bill']['giam_gia']) ?>đ</span>
        </div>
        <?php endif; ?>
        
        <div class="total-row">
            <span style="font-weight: bold;">Tổng thanh toán:</span>
            <span class="final-total"><?php echo number_format($info['bill']['tong_tien']) ?>đ</span>
        </div>
    </div>

    <?php } // End if ?>

    <div style="text-align: center;">
        <a href="<?php echo BASE_URL ?>Home" class="btn-home">
            <i class="fas fa-shopping-cart"></i> TIẾP TỤC MUA SẮM
        </a>
    </div>
</div>