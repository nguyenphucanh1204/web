<?php
class Khohang extends controller {
    public $khoModel;

    public function __construct() {
        $this->khoModel = $this->model("KhohangModel");
    }

    // =================================================================
    // PHẦN 1: QUẢN LÝ NHẬP KHO (Import)
    // =================================================================

    // 1. Hiển thị giao diện Nhập hàng
    public function Get_data() {
        // Khởi tạo giỏ nhập nếu chưa có (tránh lỗi undefined)
        if(!isset($_SESSION['gio_nhap'])) $_SESSION['gio_nhap'] = [];

        // Xử lý tìm kiếm lịch sử
        $keyword = "";
        if(isset($_POST['btnTimKiem'])) {
            $keyword = $_POST['txtTimKiem'];
        }

        // Gọi View và truyền dữ liệu cần thiết
        $this->view("Master", [
            "page" => "Khohang_V",
            "ncc" => $this->khoModel->GetAllNCC(),       // Lấy list Nhà cung cấp để đổ vào ô chọn
            "sp" => $this->khoModel->GetAllSP(),         // Lấy list Sản phẩm để chọn nhập
            "lichsu" => $this->khoModel->GetLichSuNhap($keyword), // Lấy bảng lịch sử nhập cũ
            "keyword" => $keyword
        ]);
    }

    // 2. Thêm sản phẩm vào phiếu tạm (Lưu vào Session)
    public function ThemTam() {
        if(isset($_POST['btnThem'])) {
            $id = $_POST['ddlSanPham'];
            $sl = $_POST['txtSoLuong'];
            $gia = $_POST['txtGiaNhap'];

            // Lấy tên sản phẩm từ DB để hiển thị cho đẹp
            $sp = mysqli_fetch_array($this->khoModel->GetSP($id));

            // Tạo mảng item chứa thông tin dòng nhập
            $item = [
                'id' => $id,
                'ten' => $sp['TenSP'],
                'soluong' => $sl,
                'gia' => $gia
            ];

            // Đẩy vào Session (Mảng 2 chiều)
            $_SESSION['gio_nhap'][] = $item;

            // Quay lại trang nhập hàng
            header("Location: http://localhost/Baitaplon/Khohang");
        }
    }

    // 3. Xóa một dòng trong phiếu tạm
    public function XoaTam($index) {
        if(isset($_SESSION['gio_nhap'][$index])) {
            unset($_SESSION['gio_nhap'][$index]); // Xóa phần tử tại vị trí $index
            $_SESSION['gio_nhap'] = array_values($_SESSION['gio_nhap']); // Sắp xếp lại chỉ số mảng (0,1,2...) tránh lỗi
        }
        header("Location: http://localhost/Baitaplon/Khohang");
    }

    // 4. Lưu phiếu nhập chính thức (Ghi vào Database)
    public function LuuPhieu() {
        // Kiểm tra xem có hàng để lưu không
        if(isset($_SESSION['gio_nhap']) && count($_SESSION['gio_nhap']) > 0) {
            
            $mancc = $_POST['ddlNCC'];
            $manv = 1; // Giả định mã nhân viên đang đăng nhập là 1 (hoặc lấy từ Session login)
            
            // Tính tổng tiền phiếu nhập
            $tongtien = 0;
            foreach($_SESSION['gio_nhap'] as $item) {
                $tongtien += $item['soluong'] * $item['gia'];
            }

            // Gọi Model để thực hiện Transaction (Lưu phiếu + Lưu chi tiết + Cộng kho)
            $kq = $this->khoModel->NhapHang($mancc, $manv, $tongtien, $_SESSION['gio_nhap']);

            if($kq) {
                unset($_SESSION['gio_nhap']); // Xóa session sau khi lưu thành công
                echo "<script>alert('Nhập kho thành công!'); window.location.href='http://localhost/Baitaplon/Khohang';</script>";
            } else {
                echo "<script>alert('Lỗi nhập kho! Vui lòng kiểm tra lại.'); window.location.href='http://localhost/Baitaplon/Khohang';</script>";
            }

        } else {
            echo "<script>alert('Chưa chọn sản phẩm nào!'); window.location.href='http://localhost/Baitaplon/Khohang';</script>";
        }
    }

    // --- 5. XUẤT EXCEL LỊCH SỬ NHẬP (Code đầy đủ) ---
    public function XuatExcelLichSu() {
        $objExcel = new PHPExcel();
        $objExcel->setActiveSheetIndex(0);
        $sheet = $objExcel->getActiveSheet()->setTitle('Lich Su Nhap Kho');
        $rowCount = 1;

        // Header
        $sheet->setCellValue('A1', 'Mã Phiếu');
        $sheet->setCellValue('B1', 'Nhà Cung Cấp');
        $sheet->setCellValue('C1', 'Chi Tiết Nhập (Sản phẩm - SL - Giá)');
        $sheet->setCellValue('D1', 'Ngày Nhập');
        $sheet->setCellValue('E1', 'Tổng Tiền');

        // Style Header
        $sheet->getColumnDimension('A')->setWidth(10);
        $sheet->getColumnDimension('B')->setWidth(25);
        $sheet->getColumnDimension('C')->setWidth(60);
        $sheet->getColumnDimension('D')->setWidth(20);
        $sheet->getColumnDimension('E')->setWidth(15);
        $sheet->getStyle('A1:E1')->getFont()->setBold(true);
        $sheet->getStyle('A1:E1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('00BFFF');

        // Data
        $data = $this->khoModel->GetLichSuNhap("");
        while ($row = mysqli_fetch_array($data)) {
            $rowCount++;
            
            // Xử lý cột chi tiết: Chuyển HTML thành text xuống dòng
            // HTML gốc: <div><b>Tên</b>...</div>
            // Chuyển thành: Tên... \n
            $chitiet = str_replace('</div>', "\n", $row['ChiTietNhap']); 
            $chitiet = strip_tags($chitiet); // Loại bỏ thẻ html còn lại
            $chitiet = trim($chitiet);

            $sheet->setCellValue('A' . $rowCount, '#' . $row['MaPN']);
            $sheet->setCellValue('B' . $rowCount, $row['TenNCC']);
            $sheet->setCellValue('C' . $rowCount, $chitiet);
            $sheet->setCellValue('D' . $rowCount, date('d/m/Y H:i', strtotime($row['NgayNhap'])));
            $sheet->setCellValue('E' . $rowCount, $row['TongTien']);
            
            // Bật tính năng xuống dòng cho ô Chi tiết
            $sheet->getStyle('C' . $rowCount)->getAlignment()->setWrapText(true);
            $sheet->getStyle('A' . $rowCount . ':E' . $rowCount)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
        }

        // Output file
        $fileName = 'LichSuNhapKho.xlsx';
        $objWriter = new PHPExcel_Writer_Excel2007($objExcel);
        $objWriter->save($fileName);
        if (ob_get_length()) ob_end_clean();
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        header('Content-Length: ' . filesize($fileName));
        readfile($fileName);
        unlink($fileName);
        exit;
    }

    // --- 6. NHẬP EXCEL LỊCH SỬ (Code đầy đủ - Logic nâng cao) ---
    public function NhapExcelLichSu() {
        if (isset($_POST["btnNhapExcel"])) {
            if (isset($_FILES["fileExcel"]["name"]) && $_FILES["fileExcel"]["error"] == 0) {
                $file = $_FILES["fileExcel"]["tmp_name"];
                try {
                    $inputFileType = PHPExcel_IOFactory::identify($file);
                    $objReader = PHPExcel_IOFactory::createReader($inputFileType);
                    $objReader->setReadDataOnly(true);
                    $objExcel = $objReader->load($file);
                    $sheet = $objExcel->getSheet(0);
                    $TotalRow = $sheet->getHighestRow();

                    $count = 0;
                    // Đọc từ dòng 2
                    for ($i = 2; $i <= $TotalRow; $i++) {
                        // Cấu trúc cột: A:Mã | B:NCC | C:Chi Tiết | D:Ngày | E:Tổng tiền
                        $tenNCC = $sheet->getCell('B' . $i)->getValue();
                        $strChiTiet = $sheet->getCell('C' . $i)->getValue();
                        $ngay = $sheet->getCell('D' . $i)->getValue();
                        $tongtien = $sheet->getCell('E' . $i)->getValue();

                        // Xử lý ngày tháng Excel
                        if(is_numeric($ngay)) $ngay = date('Y-m-d H:i:s', PHPExcel_Shared_Date::ExcelToPHP($ngay));

                        // 1. Tìm Mã NCC từ Tên (Cần thêm hàm GetMaNCCByTen trong Model)
                        $mancc = $this->khoModel->GetMaNCCByTen($tenNCC);
                        if(!$mancc) $mancc = 1; // Nếu không tìm thấy, gán tạm NCC mặc định là 1

                        // 2. Phân tích cột Chi tiết (Quan trọng!)
                        // Định dạng text: "Mì Hảo Hảo - SL: 10 - Giá: 3,500đ"
                        $arrChiTiet = [];
                        if(!empty($strChiTiet)) {
                            $lines = explode("\n", $strChiTiet);
                            foreach($lines as $line) {
                                // Sử dụng Regex để bắt: Tên, Số lượng, Giá
                                // (.*?) : Lấy tên (bất kỳ ký tự nào)
                                // - SL: (\d+) : Lấy số lượng (số)
                                // - Giá: ([\d,]+) : Lấy giá (số và dấu phẩy)
                                if(preg_match('/^(.*?) - SL: (\d+) - Giá: ([\d,]+)/', trim($line), $matches)) {
                                    $tenSP = trim($matches[1]);
                                    $sl = $matches[2];
                                    $gia = str_replace(',', '', $matches[3]); // Bỏ dấu phẩy trong giá

                                    // Tìm Mã SP từ Tên
                                    $masp = $this->khoModel->GetMaSPByTen($tenSP);
                                    if($masp) {
                                        $arrChiTiet[] = ['masp' => $masp, 'sl' => $sl, 'gia' => $gia];
                                    }
                                }
                            }
                        }

                        // 3. Nếu phân tích thành công ít nhất 1 sản phẩm -> Lưu vào DB
                        if (!empty($arrChiTiet)) {
                            // Gọi hàm ImportPhieuLichSuFull trong Model
                            $this->khoModel->ImportPhieuLichSuFull($mancc, 1, $ngay, $tongtien, $arrChiTiet);
                            $count++;
                        }
                    }
                    echo "<script>alert('Đã import thành công $count phiếu nhập!'); window.location.href='http://localhost/Baitaplon/Khohang';</script>";

                } catch (Exception $e) {
                    echo "<script>alert('Lỗi: " . $e->getMessage() . "'); window.history.back();</script>";
                }
            }
        }
    }

    // =================================================================
    // PHẦN 2: KIỂM KÊ / ĐIỀU CHỈNH KHO (Inventory Check)
    // =================================================================

    // 5. Hiển thị trang Kiểm Kê
    public function KiemKe() {
        if(!isset($_SESSION['gio_kiem'])) $_SESSION['gio_kiem'] = [];

        $keyword = "";
        if(isset($_POST['btnTimKiem'])) $keyword = $_POST['txtTimKiem'];

        $this->view("Master", [
            "page" => "Kiemkho_V",
            "sp" => $this->khoModel->GetAllSP(),
            "lichsu" => $this->khoModel->GetLichSuKiemKe($keyword),
            "keyword" => $keyword
        ]);
    }

    // 6. Thêm vào phiếu kiểm tạm
    public function ThemKiem() {
        if(isset($_POST['btnThem'])) {
            $id = $_POST['ddlSanPham'];
            $thucte = $_POST['txtThucTe'];
            $lydo = $_POST['txtLyDo'];
            
            // Lấy thông tin tồn máy hiện tại
            $row = mysqli_fetch_array($this->khoModel->GetSP($id));
            
            $item = [
                'id' => $id,
                'ten' => $row['TenSP'],
                'tonmay' => $row['SoLuongTon'], // Tồn hiện tại trong DB
                'tonthuc' => $thucte,           // Số thực tế đếm được
                'lydo' => $lydo
            ];

            $_SESSION['gio_kiem'][] = $item;
            header("Location: http://localhost/Baitaplon/Khohang/KiemKe");
        }
    }

    // 7. Xóa dòng kiểm
    public function XoaKiem($index) {
        if(isset($_SESSION['gio_kiem'][$index])) {
            unset($_SESSION['gio_kiem'][$index]);
            $_SESSION['gio_kiem'] = array_values($_SESSION['gio_kiem']);
        }
        header("Location: http://localhost/Baitaplon/Khohang/KiemKe");
    }

    // 8. Lưu phiếu kiểm (Cân bằng kho)
    public function LuuPhieuKiem() {
        if(isset($_SESSION['gio_kiem']) && count($_SESSION['gio_kiem']) > 0) {
            $ghichu = $_POST['txtGhiChu'];
            $manv = 1; 
            
            // Gọi Model để cập nhật kho theo số thực tế
            $this->khoModel->LuuKiemKho($manv, $ghichu, $_SESSION['gio_kiem']);
            
            unset($_SESSION['gio_kiem']);
            echo "<script>alert('Đã cân bằng kho thành công!'); window.location.href='http://localhost/Baitaplon/Khohang/KiemKe';</script>";
        } else {
            echo "<script>alert('Chưa có sản phẩm nào!'); window.location.href='http://localhost/Baitaplon/Khohang/KiemKe';</script>";
        }
    }

    // 5. Xuất Excel Kiểm Kê
    public function XuatExcelKiemKe() {
        $objExcel = new PHPExcel();
        $objExcel->setActiveSheetIndex(0);
        $sheet = $objExcel->getActiveSheet()->setTitle('Lich Su Kiem Ke');
        $rowCount = 1;

        $sheet->setCellValue('A1', 'Mã Phiếu');
        $sheet->setCellValue('B1', 'Ghi Chú');
        $sheet->setCellValue('C1', 'Chi Tiết Kiểm (Máy -> Thực)');
        $sheet->setCellValue('D1', 'Ngày Kiểm');

        $sheet->getColumnDimension('A')->setWidth(10);
        $sheet->getColumnDimension('B')->setWidth(25);
        $sheet->getColumnDimension('C')->setWidth(60);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getStyle('A1:D1')->getFont()->setBold(true);
        $sheet->getStyle('A1:D1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('FFA500'); // Màu cam

        $data = $this->khoModel->GetLichSuKiemKe("");
        while ($row = mysqli_fetch_array($data)) {
            $rowCount++;
            // Làm sạch HTML
            $chitiet = str_replace('</div>', "\n", $row['ChiTietKiem']);
            $chitiet = strip_tags($chitiet);
            $chitiet = trim($chitiet);

            $sheet->setCellValue('A' . $rowCount, '#PK' . str_pad($row['MaPK'], 3, '0', STR_PAD_LEFT));
            $sheet->setCellValue('B' . $rowCount, $row['GhiChu']);
            $sheet->setCellValue('C' . $rowCount, $chitiet);
            $sheet->setCellValue('D' . $rowCount, date('d/m/Y H:i', strtotime($row['NgayKiem'])));
            $sheet->getStyle('C' . $rowCount)->getAlignment()->setWrapText(true);
            $sheet->getStyle('A' . $rowCount . ':D' . $rowCount)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
        }

        $fileName = 'LichSuKiemKe.xlsx';
        $objWriter = new PHPExcel_Writer_Excel2007($objExcel);
        $objWriter->save($fileName);
        if (ob_get_length()) ob_end_clean();
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        header('Content-Length: ' . filesize($fileName));
        readfile($fileName);
        unlink($fileName);
        exit;
    }

    // 6. Nhập Excel Kiểm Kê
    public function NhapExcelKiemKe() {
        if (isset($_POST["btnNhapExcel"])) {
            if (isset($_FILES["fileExcel"]["name"]) && $_FILES["fileExcel"]["error"] == 0) {
                $file = $_FILES["fileExcel"]["tmp_name"];
                try {
                    $inputFileType = PHPExcel_IOFactory::identify($file);
                    $objReader = PHPExcel_IOFactory::createReader($inputFileType);
                    $objReader->setReadDataOnly(true);
                    $objExcel = $objReader->load($file);
                    $sheet = $objExcel->getSheet(0);
                    $TotalRow = $sheet->getHighestRow();

                    $count = 0;
                    for ($i = 2; $i <= $TotalRow; $i++) {
                        // Cấu trúc: A: Mã(Bỏ) | B: Ghi Chú | C: Chi Tiết | D: Ngày
                        $ghichu = $sheet->getCell('B' . $i)->getValue();
                        $strChiTiet = $sheet->getCell('C' . $i)->getValue();
                        $ngay = $sheet->getCell('D' . $i)->getValue();

                        if(is_numeric($ngay)) $ngay = date('Y-m-d H:i:s', PHPExcel_Shared_Date::ExcelToPHP($ngay));

                        // Phân tích chi tiết: "Tên SP | Máy: 10 -> Thực: 8 (Lý do)"
                        $arrChiTiet = [];
                        if(!empty($strChiTiet)) {
                            $lines = explode("\n", $strChiTiet);
                            foreach($lines as $line) {
                                // Regex: Tên SP | Máy: 10 -> Thực: 8 (Lý do)
                                if(preg_match('/^(.*?) \| Máy: (\d+) -> Thực: (\d+) \((.*?)\)/', trim($line), $matches)) {
                                    $tenSP = trim($matches[1]);
                                    $may = $matches[2];
                                    $thuc = $matches[3];
                                    $lydo = $matches[4];

                                    $masp = $this->khoModel->GetMaSPByTen($tenSP);
                                    if($masp) {
                                        $arrChiTiet[] = ['masp' => $masp, 'may' => $may, 'thuc' => $thuc, 'lydo' => $lydo];
                                    }
                                }
                            }
                        }

                        if (!empty($arrChiTiet)) {
                            $this->khoModel->ImportPhieuKiemFull(1, $ngay, $ghichu, $arrChiTiet);
                            $count++;
                        }
                    }
                    echo "<script>alert('Đã import $count phiếu kiểm kê!'); window.location.href='http://localhost/Baitaplon/Khohang/KiemKe';</script>";
                } catch (Exception $e) {
                    echo "<script>alert('Lỗi: " . $e->getMessage() . "'); window.history.back();</script>";
                }
            }
        }
    }
}
?>