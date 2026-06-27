<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hệ Thống Quản Lý Siêu Thị</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link rel="stylesheet" href="http://localhost/Baitaplon/Public/Css/giaodien.css">
</head>
<body>

    <div class="sidebar">
        <div class="brand">
            <i class="fas fa-shopping-basket" style="margin-right:10px; color: #4fc3f7"></i> 
            Win Mart
        </div>
        
        <ul class="menu">
            <li><a href="http://localhost/Baitaplon/Home" class="active"><i class="fas fa-home"></i> Tổng quan</a></li>

            <div class="menu-title">HOẠT ĐỘNG BÁN HÀNG</div>
            <li><a href="http://localhost/Baitaplon/Banhang"><i class="fas fa-desktop"></i> Bán hàng </a></li>
            <li><a href="http://localhost/Baitaplon/Donhang"><i class="fas fa-file-invoice-dollar"></i> Đơn hàng</a></li>

            <div class="menu-title">QUẢN LÝ HÀNG HÓA</div>
            <li><a href="http://localhost/Baitaplon/Sanpham"><i class="fas fa-box"></i> Sản phẩm</a></li>
            <li><a href="http://localhost/Baitaplon/Danhmuc"><i class="fas fa-list"></i> Danh mục</a></li>
            <li><a href="http://localhost/Baitaplon/Khohang"><i class="fas fa-warehouse"></i> Phiếu Nhập Kho</a></li>
            <li><a href="http://localhost/Baitaplon/Khohang/KiemKe"><i class="fas fa-tasks"></i> Kiểm kê / Điều chỉnh</a></li>
            <li><a href="http://localhost/Baitaplon/Nhacungcap"><i class="fas fa-truck"></i> Nhà cung cấp</a></li>

            <div class="menu-title">CON NGƯỜI</div>
            <li><a href="http://localhost/Baitaplon/Khachhang"><i class="fas fa-user-friends"></i> Khách hàng</a></li>
            <li><a href="http://localhost/Baitaplon/Nhanvien"><i class="fas fa-id-card-alt"></i> Nhân viên</a></li>

            <div class="menu-title">HỆ THỐNG</div>
            <li><a href="http://localhost/Baitaplon/Khuyenmai"><i class="fas fa-tags"></i> Khuyến mãi</a></li>
            <li><a href="http://localhost/Baitaplon/Phuongthuc"><i class="fas fa-wallet"></i> P.Thức thanh toán</a></li>
            <li><a href="http://localhost/Baitaplon/Baocao"><i class="fas fa-wallet"></i> Báo cáo</a></li>
            
            <li><a href="http://localhost/Baitaplon/Taikhoan"><i class="fas fa-user-cog"></i> Quản lý Tài khoản</a></li>
        <li><a href="#"><i class="fas fa-cog"></i> Cài đặt</a></li>
            
            <li style="border-top: 1px solid rgba(255,255,255,0.1); margin-top: 10px;">
                <a href="http://localhost/Baitaplon/Login/Logout" style="color: #ff6b6b;">
                    <i class="fas fa-sign-out-alt"></i> Đăng xuất
                </a>
            </li>
        </ul>
    </div>
        </ul>
    </div>

    <div class="main-content">
        <header>
            <div class="page-title">
                <h3>Dashboard</h3> </div>
<div class="user-info">
                <span>Xin chào, <b><?php echo isset($_SESSION['user_login']['hoten']) ? $_SESSION['user_login']['hoten'] : 'Admin' ?></b></span>
                
                <a href="http://localhost/Baitaplon/Login/Logout" title="Đăng xuất" style="margin-left: 15px; color: #333; font-size: 18px;">
                    <i class="fas fa-power-off" style="color: #e31d2b;"></i>
                </a>

                <div class="user-avatar" style="margin-left: 10px;">
                    <i class="fas fa-user"></i>
                </div>
            </div>
        </header>

        <div class="page-wrapper">
            <?php 
                // Đây là nơi phép màu xảy ra: Nội dung các trang con sẽ được nhúng vào đây
                if(isset($data['page'])){
                    // Kiểm tra file có tồn tại không trước khi require để tránh lỗi
                    if(file_exists("./MVC/Views/Pages/".$data['page'].".php")){
                        require_once "./MVC/Views/Pages/".$data['page'].".php";
                    } else {
                        echo "<h3 style='color:red'>Không tìm thấy file giao diện: ".$data['page']."</h3>";
                    }
                }
            ?>
        </div>
    </div>

</body>
</html>