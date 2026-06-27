<?php
    // Lấy đường dẫn gốc của dự án (Thư mục cha của thư mục MVC)
    // Ví dụ: D:/Xampp/htdocs/Baitaplon
    $baseDir = dirname(__DIR__); 

    // Nạp các file Core (Dùng đường dẫn tuyệt đối cho chắc chắn)
    include_once $baseDir . '/MVC/Core/app.php';
    include_once $baseDir . '/MVC/Core/controller.php';
    include_once $baseDir . '/MVC/Core/connectDB.php';
    
    // Nạp thư viện Excel
    // Hệ thống sẽ tìm chính xác: D:/.../Baitaplon/Public/Classes/PHPExcel.php
    include_once $baseDir . '/Public/Classes/PHPExcel.php';
    include_once $baseDir . '/Public/Classes/PHPExcel/IOFactory.php';
?>