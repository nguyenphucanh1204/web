<style>
    .checkout-container { display: flex; gap: 30px; margin-top: 20px; }
    .col-left { flex: 6; background: white; padding: 20px; border-radius: 8px; }
    .col-right { flex: 4; background: white; padding: 20px; border-radius: 8px; height: fit-content; }
    
    .form-group { margin-bottom: 15px; }
    .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
    .form-group input { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box; }
    
    .order-item { display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #eee; }
    .order-total { display: flex; justify-content: space-between; font-size: 20px; font-weight: bold; color: #e31d2b; margin-top: 20px; border-top: 2px solid #eee; padding-top: 15px; }
    
    .btn-confirm { width: 100%; padding: 15px; background: #e31d2b; color: white; border: none; font-size: 16px; font-weight: bold; border-radius: 5px; cursor: pointer; margin-top: 20px; }
    .btn-confirm:hover { background: #c41925; }
</style>

<div class="container checkout-container">
    <div class="col-left">
        <h2 style="color: #e31d2b; border-bottom: 2px solid #eee; padding-bottom: 10px;">Thông tin giao hàng</h2>
        
        <form action="<?php echo BASE_URL ?>Checkout/Order" method="POST">
            <div class="form-group">
                <label>Họ và tên (*)</label>
                <input type="text" name="hoten" required placeholder="Nhập họ tên người nhận">
            </div>
            
            <div class="form-group">
                <label>Số điện thoại (*)</label>
                <input type="text" name="sdt" required placeholder="Nhập số điện thoại">
            </div>
            
            <div class="form-group">
                <label>Địa chỉ nhận hàng (*)</label>
                <input type="text" name="diachi" required placeholder="Số nhà, đường, phường/xã...">
            </div>

            <div class="form-group">
                <label>Ghi chú đơn hàng</label>
                <input type="text" name="ghichu" placeholder="Ví dụ: Giao giờ hành chính">
            </div>
            
            <button type="submit" name="btnDatHang" class="btn-confirm">XÁC NHẬN ĐẶT HÀNG</button>
        </form>
    </div>

    <div class="col-right">
        <h3>Đơn hàng của bạn</h3>
        <div class="order-list">
            <?php 
            $tongtien = 0;
            if(isset($data['Cart'])){
                foreach($data['Cart'] as $item){
                    $thanhtien = $item['price'] * $item['qty'];
                    $tongtien += $thanhtien;
                    // Hiển thị từng sản phẩm
                    echo "<div class='order-item'>
                            <div><b>{$item['name']}</b><br><small>x {$item['qty']}</small></div>
                            <div>".number_format($thanhtien)."đ</div>
                          </div>";
                } 
            }
            ?>
        </div>

        <div class="order-total" style="display: block; font-size: 16px;">
            <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                <span>Tạm tính:</span> <span><?php echo number_format($tongtien) ?>đ</span>
            </div>
            
            <div style="display: flex; justify-content: space-between; font-size: 22px; font-weight: bold; color: #e31d2b; border-top: 1px solid #ddd; padding-top: 10px; margin-top: 10px;">
                <span>Tổng cộng:</span> <span><?php echo number_format($tongtien) ?>đ</span>
            </div>
        </div>
    </div>
</div>