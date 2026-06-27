<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WinMart Clone</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body { font-family: Arial, sans-serif; margin: 0; background-color: #f5f5f5; }
        
        /* HEADER - Màu đỏ WinMart */
        header { background-color: #e31d2b; padding: 15px 0; color: white; }
        .container { width: 1200px; margin: 0 auto; display: flex; align-items: center; justify-content: space-between; }
        
        .logo { font-size: 30px; font-weight: bold; display: flex; align-items: center; }
        .logo img { height: 40px; margin-right: 10px; } /* Nếu có logo */
        
        .search-box { flex: 1; margin: 0 30px; position: relative; }
        .search-box input { width: 100%; padding: 10px 40px 10px 15px; border-radius: 20px; border: none; outline: none; }
        .search-box button { position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: #888; }
        
        .header-actions { display: flex; gap: 20px; font-size: 14px; }
        .action-item { display: flex; align-items: center; gap: 5px; cursor: pointer; }
        
        /* BANNER VOUCHER */
        .voucher-section { display: flex; gap: 10px; padding: 20px 0; justify-content: center; background: white; margin-bottom: 20px; }
        .voucher-card { border: 1px dashed #e31d2b; padding: 10px; border-radius: 5px; width: 250px; position: relative; }
        .btn-layngay { background: #e31d2b; color: white; border: none; padding: 5px 15px; border-radius: 5px; float: right; cursor: pointer;}
        
        /* FLASH SALE */
        .flash-sale { background: white; width: 1200px; margin: 0 auto; border-radius: 10px; overflow: hidden; }
        .fs-header { background: linear-gradient(90deg, #ff512f, #dd2476); padding: 15px; color: white; display: flex; justify-content: space-between; align-items: center; }
        .fs-title { font-size: 20px; font-weight: bold; text-transform: uppercase; }
        
        .product-grid { display: flex; padding: 20px; gap: 15px; }
        .product-card { border: 1px solid #eee; width: 25%; padding: 10px; text-align: center; position: relative; border-radius: 8px; transition: 0.3s; }
        .product-card:hover { box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
        .product-card img { width: 100%; height: 180px; object-fit: contain; }
        .p-name { font-size: 14px; margin: 10px 0; height: 40px; overflow: hidden; color: #333; }
        .p-price { color: #d0021b; font-weight: bold; font-size: 18px; }
        .btn-add { background: white; border: 1px solid #e31d2b; color: #e31d2b; padding: 5px 10px; border-radius: 20px; margin-top: 10px; cursor: pointer; }
        .btn-add:hover { background: #e31d2b; color: white; }
    </style>
</head>
<body>
<header>
    <div class="container">
        <a href="<?php echo BASE_URL ?>Home" class="logo" style="text-decoration: none; color: white;">
            WinMart
        </a>

        <div class="menu-danhmuc" style="margin-left: 20px; cursor: pointer; position: relative; display: flex; align-items: center; gap: 5px;">
            <i class="fas fa-bars"></i> Danh mục
            <div class="dropdown-content">
                <a href="<?php echo BASE_URL ?>Home">Tất cả</a>
                <a href="<?php echo BASE_URL ?>Home/Loc/1">Gia vị</a>
                <a href="<?php echo BASE_URL ?>Home/Loc/2">Đồ uống</a>
                <a href="<?php echo BASE_URL ?>Home/Loc/3">Thực phẩm tươi</a>
            </div>
        </div>

        <div class="search-box">
            <form action="<?php echo BASE_URL ?>Home/TimKiem" method="POST" style="display: flex; width: 100%;">
                <input type="text" name="keyword" placeholder="Tìm kiếm sản phẩm..." required>
                <button type="submit"><i class="fas fa-search"></i></button>
            </form>
        </div>

        <div class="header-actions">
            
            <a href="<?php echo BASE_URL ?>Cart" style="text-decoration: none; color: white;">
                <div class="action-item">
                    <i class="fas fa-shopping-cart"></i> 
                    Giỏ hàng (<?php echo isset($_SESSION['cart']) ? array_sum(array_column($_SESSION['cart'], 'qty')) : 0; ?>)
                </div>
            </a>
            
            <div class="action-item">
                <i class="fas fa-user-circle"></i> 
                <?php if(isset($_SESSION['user_login'])) { ?>
                    <span style="position: relative; display: inline-block;">
                        Chào, <b><?php echo $_SESSION['user_login']['hoten'] ?></b>
                        <br>
                        <a href="<?php echo BASE_URL ?>MVC/" style="color: #ffcccc; font-size: 12px; text-decoration: none;">[Admin]</a>
                        <a href="<?php echo BASE_URL ?>Login/Logout" style="color: #fff; font-size: 12px; text-decoration: underline; margin-left: 5px;">Thoát</a>
                    </span>
                <?php } else { ?>
                    <a href="<?php echo BASE_URL ?>Login" style="text-decoration: none; color: white;">Đăng nhập</a>
                <?php } ?>
            </div>
        </div>
    </div>
</header>

<style>
    /* Style cho nút Danh mục cha */
    .menu-danhmuc {
        margin-left: 20px; 
        cursor: pointer; 
        position: relative; /* Để neo menu con theo nút này */
        display: flex; 
        align-items: center; 
        gap: 5px;
        height: 100%; /* Full chiều cao header để dễ hover hơn */
    }

    /* Menu con thả xuống */
    .dropdown-content {
        display: none; /* Mặc định ẩn */
        position: absolute;
        top: 100%; /* Nằm ngay dưới đáy của nút cha */
        left: 0;
        background-color: white;
        min-width: 200px;
        box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
        z-index: 100; /* Đảm bảo nổi lên trên banner */
        border-radius: 4px;
        border-top: 3px solid #e31d2b; /* Trang trí viền đỏ trên cùng */
    }

    /* --- [QUAN TRỌNG] TẠO CẦU NỐI VÔ HÌNH --- */
    /* Lớp giả này sẽ lấp đầy khoảng trống giữa nút và menu */
    .dropdown-content::before {
        content: "";
        position: absolute;
        top: -20px; /* Đẩy lên trên để trùm lên khoảng hở */
        left: 0;
        width: 100%;
        height: 20px; /* Chiều cao của cầu nối */
        background: transparent; /* Trong suốt */
    }

    /* Style cho các link bên trong */
    .dropdown-content a {
        color: #333;
        padding: 12px 16px;
        text-decoration: none;
        display: block;
        font-size: 14px;
        border-bottom: 1px solid #f1f1f1;
        transition: 0.3s;
    }

    /* Hiệu ứng khi di chuột vào link con */
    .dropdown-content a:hover {
        background-color: #ffe6e6; /* Màu nền hồng nhạt khi hover */
        color: #e31d2b; /* Chữ đỏ */
        padding-left: 20px; /* Hiệu ứng đẩy chữ sang phải */
    }

    /* Hiển thị menu khi hover vào nút cha */
    .menu-danhmuc:hover .dropdown-content {
        display: block;
    }
</style>

    <div style="padding-bottom: 50px;">
        <?php 
            // Load nội dung trang con (Pages)
            if(file_exists("./Views/Pages/".$data["Page"].".php")){
                require_once "./Views/Pages/".$data["Page"].".php";
            }
        ?>
    </div>

</body>
</html>