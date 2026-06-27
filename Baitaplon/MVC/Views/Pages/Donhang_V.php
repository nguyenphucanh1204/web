<style>
    /* CSS Card & Table hiện đại */
    .card-custom {
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 0 15px rgba(0,0,0,0.05);
        border: 1px solid #e0e0e0;
        overflow: hidden;
    }
    .card-header-custom {
        padding: 15px 20px;
        background: linear-gradient(to right, #1a237e, #3949ab); /* Xanh đậm sang trọng */
        color: white;
        font-weight: bold;
        font-size: 16px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    /* --- THANH TÌM KIẾM (Đã sửa lỗi dính nhau) --- */
    .search-box {
        background: #f8f9fa;
        padding: 20px;
        border-bottom: 1px solid #eee;
    }
    .search-form {
        display: flex; 
        align-items: center; /* Căn giữa dọc */
        gap: 15px; /* Khoảng cách giữa các phần tử */
    }
    .input-wrapper {
        flex: 1; 
        position: relative;
    }
    .search-icon {
        position: absolute; 
        left: 15px; 
        top: 50%; 
        transform: translateY(-50%); /* Căn giữa icon */
        color: #999;
    }
    .form-control-search {
        width: 100%;
        height: 42px;
        padding: 0 15px 0 45px; /* Padding trái để chừa chỗ cho icon */
        border: 1px solid #ddd;
        border-radius: 6px;
        outline: none;
        transition: 0.3s;
        box-sizing: border-box;
    }
    .form-control-search:focus {
        border-color: #1a237e;
        box-shadow: 0 0 5px rgba(26, 35, 126, 0.2);
    }
    .btn-search {
        height: 42px;
        padding: 0 25px;
        background: #1a237e;
        color: white;
        border: none;
        border-radius: 6px;
        font-weight: bold;
        cursor: pointer;
        white-space: nowrap;
    }
    .btn-search:hover { opacity: 0.9; }
    
    .btn-reset {
        height: 42px;
        padding: 0 20px;
        color: #666;
        background: white;
        border: 1px solid #ddd;
        border-radius: 6px;
        display: flex;
        align-items: center;
        text-decoration: none;
        white-space: nowrap;
        font-weight: 500;
        box-sizing: border-box;
    }
    .btn-reset:hover { background: #f1f1f1; }
    
    /* Table styles */
    .table-order { width: 100%; border-collapse: collapse; }
    .table-order th { background: #f1f1f1; padding: 15px; text-align: left; color: #555; font-size: 13px; text-transform: uppercase; border-bottom: 2px solid #ddd;}
    .table-order td { padding: 15px; border-bottom: 1px solid #eee; color: #333; font-size: 14px; }
    .table-order tr:hover { background: #f0f4ff; }
    
    .badge-id { background: #e8eaf6; color: #1a237e; padding: 5px 10px; border-radius: 4px; font-weight: bold; font-size: 13px; }
    .price-tag { color: #d32f2f; font-weight: bold; font-size: 15px; }
    
    .btn-view {
        padding: 6px 12px;
        background: #0288d1;
        color: white;
        border-radius: 4px;
        text-decoration: none;
        font-size: 12px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        transition: 0.2s;
    }
    .btn-view:hover { background: #0277bd; transform: translateY(-2px); box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
</style>

<div class="card-custom">
    <div class="card-header-custom">
        <i class="fas fa-history"></i> Lịch Sử Đơn Hàng
    </div>
    
    <div class="search-box">
        <form action="http://localhost/Baitaplon/Donhang" method="POST" class="search-form">
            
            <div class="input-wrapper">
                <i class="fas fa-search search-icon"></i>
                <input type="text" name="txtTimKiem" class="form-control-search" 
                       placeholder="Nhập mã hóa đơn hoặc tên khách hàng..." 
                       value="<?php echo (isset($data['keyword']) && $data['keyword']!='0') ? $data['keyword'] : '' ?>">
            </div>
            
            <button type="submit" name="btnTimKiem" class="btn-search">
                Tìm kiếm
            </button>
            
            <a href="http://localhost/Baitaplon/Donhang" class="btn-reset" title="Tải lại danh sách">
                <i class="fas fa-sync-alt" style="margin-right: 5px;"></i> Tất cả
            </a>
        </form>
    </div>

    <div style="padding: 0; overflow-x: auto;">
        <table class="table-order">
            <thead>
                <tr>
                    <th style="padding-left: 20px;">Mã HĐ</th>
                    <th>Ngày Lập</th>
                    <th>Khách Hàng</th>
                    <th>Người Bán</th>
                    <th>Tổng Tiền</th>
                    <th style="text-align: center;">Hành Động</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if(isset($data['dulieu']) && mysqli_num_rows($data['dulieu']) > 0){
                    while($row = mysqli_fetch_array($data['dulieu'])){
                ?>
                    <tr>
                        <td style="padding-left: 20px;">
                            <span class="badge-id">#<?php echo $row['MaHD']; ?></span>
                        </td>
                        <td style="color: #555;">
                            <i class="far fa-clock" style="margin-right:5px"></i> 
                            <?php echo date('d/m/Y H:i', strtotime($row['NgayLap'])); ?>
                        </td>
                        <td style="font-weight: 600; color: #333;">
                            <?php echo $row['TenKH']; ?>
                        </td>
                        <td style="color: #666;">
                            <?php echo $row['HoTen']; ?>
                        </td>
                        <td>
                            <span class="price-tag"><?php echo number_format($row['TongTien']); ?> đ</span>
                        </td>
                        <td style="text-align: center;">
                            <a href="http://localhost/Baitaplon/Donhang/Chitiet/<?php echo $row['MaHD']; ?>" class="btn-view" title="Xem chi tiết hóa đơn">
                                <i class="fas fa-eye"></i> Chi tiết
                            </a>
                        </td>
                    </tr>
                <?php 
                    }
                } else {
                    echo "<tr><td colspan='6' style='text-align:center; padding: 50px; color: #999;'>
                        <i class='fas fa-search' style='font-size:40px; margin-bottom:15px; display:block; color:#eee;'></i>
                        Không tìm thấy đơn hàng nào phù hợp!
                    </td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>