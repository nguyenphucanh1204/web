<?php
// 1. Định nghĩa đường dẫn gốc (Quan trọng nhất)
// Hãy thay 'WinmartMVC' bằng tên thư mục thực tế trong htdocs của bạn
define("BASE_URL", "http://localhost/WinmartMVC/");

// 2. Load các file Core
require_once "./Core/app.php";
require_once "./Core/controller.php";
require_once "./Core/connectDB.php";
?>