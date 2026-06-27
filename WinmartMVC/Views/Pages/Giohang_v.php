<style>
    .cart-container { background: white; padding: 20px; border-radius: 8px; margin-top: 20px; }
    .cart-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
    .cart-table th { background: #f8f8f8; padding: 10px; text-align: left; border-bottom: 2px solid #ddd; }
    .cart-table td { padding: 15px 10px; border-bottom: 1px solid #eee; vertical-align: middle; }
    .cart-img { width: 80px; height: 80px; object-fit: cover; border: 1px solid #eee; }
    
    .qty-input { width: 50px; padding: 5px; text-align: center; border: 1px solid #ccc; border-radius: 4px; }
    
    .btn-del { color: #999; cursor: pointer; font-size: 18px; transition: 0.3s; }
    .btn-del:hover { color: #e31d2b; }

    .cart-summary { text-align: right; font-size: 18px; }
    .total-price { color: #e31d2b; font-weight: bold; font-size: 24px; }
    .btn-checkout { background: #e31d2b; color: white; padding: 10px 30px; border: none; border-radius: 5px; font-size: 16px; margin-top: 10px; cursor: pointer; }
    .btn-continue { background: white; border: 1px solid #e31d2b; color: #e31d2b; padding: 10px 20px; border-radius: 5px; text-decoration: none; margin-right: 10px;}
</style>

<div class="container cart-container">
    <h2 style="margin-top: 0;">Giỏ hàng của bạn</h2>

    <?php if(!empty($data['Cart'])) { ?>
        <table class="cart-table">
            <thead>
                <tr>
                    <th>Sản phẩm</th>
                    <th>Đơn giá</th>
                    <th>Số lượng</th>
                    <th>Thành tiền</th>
                    <th>Xóa</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $tongtien = 0;
                foreach($data['Cart'] as $item) { 
                    $thanhtien = $item['price'] * $item['qty'];
                    $tongtien += $thanhtien;
                ?>
                <tr>
                    <td style="display: flex; align-items: center; gap: 15px;">
                        <img src="<?php echo BASE_URL ?>public/images/<?php echo $item['image'] ?>" class="cart-img">
                        <b><?php echo $item['name'] ?></b>
                    </td>
                    <td><?php echo number_format($item['price']) ?>đ</td>
                    <td>
                        <input type="number" class="qty-input" value="<?php echo $item['qty'] ?>" 
                               min="1" onchange="updateCart(<?php echo $item['id'] ?>, this.value)">
                    </td>
                    <td style="color: #e31d2b; font-weight: bold;">
                        <?php echo number_format($thanhtien) ?>đ
                    </td>
                    <td>
                        <a href="<?php echo BASE_URL ?>Cart/Delete/<?php echo $item['id'] ?>" class="btn-del">
                            <i class="fas fa-trash-alt"></i>
                        </a>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>

        <div class="cart-summary">
            <span>Tổng cộng:</span> <span class="total-price"><?php echo number_format($tongtien) ?>đ</span>
            <br><br>
            <a href="<?php echo BASE_URL ?>Home" class="btn-continue">Tiếp tục mua hàng</a>
<a href="<?php echo BASE_URL ?>Checkout">
    <button class="btn-checkout">Tiến hành đặt hàng</button>
</a>        </div>
    <?php } else { ?>
        <div style="text-align: center; padding: 50px;">
            <img src="https://deo.shopeemobile.com/shopee/shopee-pcmall-live-sg/cart/9bdd8040b334d31946f4.png" width="100">
            <p>Giỏ hàng của bạn còn trống</p>
            <a href="<?php echo BASE_URL ?>Home" class="btn-continue">Mua ngay</a>
        </div>
    <?php } ?>
</div>

<script>
function updateCart(id, qty){
    // Lấy BASE_URL từ PHP truyền vào biến JS
    var baseUrl = "<?php echo BASE_URL ?>";
    
    // Ghép chuỗi để tạo đường dẫn chính xác
    window.location.href = baseUrl + "Cart/Update/" + id + "/" + qty;
}
</script>