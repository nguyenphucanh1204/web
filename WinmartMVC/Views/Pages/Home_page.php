
<style>
    /* --- TỔNG QUAN --- */
    body {
        background-color: #f5f5f5; Nền xám nhạt làm nổi bật sản phẩm trắng
    }
    
    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 15px;
    }

    /* --- FLASH SALE CONTAINER --- */
    .flash-sale {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        margin-bottom: 30px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    }

    /* --- HEADER (TIÊU ĐỀ) --- */
/* --- HEADER (TIÊU ĐỀ) --- */
    .fs-header {
        /* SỬA LẠI: Dùng màu đỏ chuẩn WinMart thay vì gradient cam */
        background-color: #e31d2b; 
        
        /* Hoặc nếu thích gradient nhưng vẫn muốn tiệp màu, hãy dùng đỏ sang đỏ đậm */
        /* background: linear-gradient(90deg, #e31d2b, #c41925); */
        
        padding: 15px 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        
        /* Bo góc trên để khớp với khung */
        border-top-left-radius: 12px;
        border-top-right-radius: 12px;
    }

    /* Bổ sung: Bo góc cho toàn bộ khối Flash Sale để mềm mại hơn */
    .flash-sale {
        background: white;
        border-radius: 12px; /* Bo tròn 4 góc */
        overflow: hidden;    /* Đảm bảo nội dung con không bị lòi ra khỏi góc bo */
        margin-bottom: 30px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        border: 1px solid #e31d2b; /* (Tùy chọn) Viền mỏng màu đỏ bao quanh */
    }

    .fs-title {
        font-size: 22px;
        font-weight: 800;
        color: white;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: flex;
        align-items: center;
    }
    
    .fs-title::before {
        content: "\f0e7"; /* Icon sét (FontAwesome) */
        font-family: "Font Awesome 5 Free";
        margin-right: 10px;
        font-weight: 900;
        color: #ffeb3b; /* Màu vàng tia sét */
        font-size: 24px;
    }

    /* --- LƯỚI SẢN PHẨM --- */
    .product-grid {
        display: flex;
        flex-wrap: wrap;
        padding: 20px 10px;
        gap: 15px; /* Khoảng cách giữa các ô */
    }

    /* --- THẺ SẢN PHẨM (CARD) --- */
    .product-card {
        background: #fff;
        border: 1px solid #f0f0f0;
        border-radius: 8px;
        /* Tính toán width: 4 sản phẩm/hàng trừ đi khoảng cách gap */
        width: calc(25% - 15px); 
        padding: 15px;
        box-sizing: border-box;
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        position: relative;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    /* Hiệu ứng khi di chuột vào sản phẩm */
    .product-card:hover {
        transform: translateY(-5px); /* Nổi lên trên */
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        border-color: #e31d2b;
    }

    /* Ảnh sản phẩm */
    .product-card img {
        width: 100%;
        height: 180px;
        object-fit: contain; /* Giữ nguyên tỉ lệ ảnh */
        margin-bottom: 10px;
        transition: transform 0.3s;
    }

    .product-card:hover img {
        transform: scale(1.05); /* Phóng to ảnh nhẹ khi hover */
    }

    /* Tên sản phẩm */
    .p-name {
        font-size: 14px;
        color: #333;
        line-height: 1.4;
        margin-bottom: 8px;
        height: 40px; /* Cố định chiều cao 2 dòng */
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 2; /* Cắt chữ sau 2 dòng */
        -webkit-box-orient: vertical;
    }
    
    .product-card:hover .p-name {
        color: #e31d2b;
    }

    /* Giá sản phẩm */
    .p-price {
        color: #d0021b;
        font-weight: 700;
        font-size: 18px;
        margin-bottom: 15px;
    }

    /* Nút Chọn mua */
    .btn-add {
        display: block;
        width: 100%;
        text-align: center;
        background: white;
        color: #e31d2b;
        border: 1px solid #e31d2b;
        padding: 8px 0;
        border-radius: 20px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s;
    }

    .btn-add:hover {
        background: #e31d2b;
        color: white;
        box-shadow: 0 4px 10px rgba(227, 29, 43, 0.3);
    }
    
    /* Responsive cho màn hình nhỏ hơn */
    @media (max-width: 992px) {
        .product-card { width: calc(33.33% - 15px); } /* 3 cột */
    }
    @media (max-width: 768px) {
        .product-card { width: calc(50% - 15px); } /* 2 cột */
    }
</style>

<div class="main-banner" style="width: 100%; background: transparent; margin-top: 20px; margin-bottom: 20px;">
    <div class="container">
        <img src="https://s3-hcmc02.higiocloud.vn/images/2025/12/homebanner_867x400px_wct-20251220092852.jpg" 
             alt="Banner Winmart" 
             style="width: 100%; border-radius: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); display: block;">
    </div>
</div>
   ...
<div class="flash-sale">
<div class="fs-header">
        <span class="fs-title">
            <?php 
                // Nếu Controller có truyền Tiêu đề thì hiện, không thì hiện mặc định
                echo isset($data['TieuDe']) ? $data['TieuDe'] : "Duy nhất hôm nay"; 
            ?>
        </span>
    
    </div>
    
    <div class="product-grid">
        <?php
        // BẮT ĐẦU VÒNG LẶP TẠI ĐÂY
        // Kiểm tra xem có dữ liệu Sanpham truyền qua không
        if(isset($data["Sanpham"])){
            while($row = mysqli_fetch_array($data["Sanpham"])){
        ?>
            <div class="product-card">
                
                <img src="<?php echo BASE_URL ?>public/images/<?php echo $row['HinhAnh'] ?>" alt="<?php echo $row['TenSP'] ?>">                
                <div class="p-name"><?php echo $row['TenSP'] ?></div>
                <div class="p-price"><?php echo number_format($row['GiaBan']) ?>đ</div>
                
        <a href="<?php echo BASE_URL ?>Cart/Add/<?php echo $row['MaSP'] ?>" class="btn-add" style="display:inline-block; text-decoration:none;">
            Chọn mua
        </a>
            </div>
        <?php
            } // Đóng vòng lặp while
        } // Đóng if
        ?>
    </div>
</div>