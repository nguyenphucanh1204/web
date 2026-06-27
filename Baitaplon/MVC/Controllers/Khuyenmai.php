<?php
class Khuyenmai extends controller {
    public $kmModel;

    public function __construct() {
        $this->kmModel = $this->model("KhuyenmaiModel");
    }

    // Hiển thị & Tìm kiếm
    public function Get_data() {
        $keyword = "";
        if(isset($_POST['btnTimKiem'])) $keyword = $_POST['txtTimKiem'];

        $this->view("Master", [
            "page" => "Khuyenmai_V",
            "dulieu" => $this->kmModel->DanhSach($keyword),
            "keyword" => $keyword
        ]);
    }

    // Xử lý Lưu (Thêm mới hoặc Cập nhật)
    public function Luu() {
        if(isset($_POST['btnThem']) || isset($_POST['btnLuu'])) {
            $ten = strtoupper($_POST['txtTen']); // Viết hoa mã
            $tien = $_POST['txtTien'];
            $soluong = $_POST['txtSoLuong'];

            if(isset($_POST['btnLuu'])) {
                // --- TRƯỜNG HỢP SỬA ---
                $id = $_POST['txtID'];
                $this->kmModel->Sua($id, $ten, $tien, $soluong);
                echo "<script>alert('Cập nhật thành công!'); window.location.href='http://localhost/Baitaplon/Khuyenmai';</script>";
            } else {
                // --- TRƯỜNG HỢP THÊM MỚI ---
                // Check trùng trước
                if($this->kmModel->CheckTrung($ten)){
                    echo "<script>alert('Mã [$ten] đã tồn tại!'); window.history.back();</script>";
                    return;
                }
                $this->kmModel->ThemMoi($ten, $tien, $soluong);
                echo "<script>alert('Phát hành mã thành công!'); window.location.href='http://localhost/Baitaplon/Khuyenmai';</script>";
            }
        }
    }

    // Gọi giao diện Sửa
    public function Sua($id) {
        $this->view("Master", [
            "page" => "Khuyenmai_V",
            "dulieu" => $this->kmModel->DanhSach(), // Vẫn load danh sách bên phải
            "edit_data" => mysqli_fetch_array($this->kmModel->GetByID($id)) // Dữ liệu cần sửa
        ]);
    }

    // Xóa
    public function Xoa($id) {
        $this->kmModel->XoaMa($id);
        header("Location: http://localhost/Baitaplon/Khuyenmai");
    }


    // --- 1. XUẤT EXCEL (Chuẩn .xlsx) ---
    public function XuatExcel() {
        $objExcel = new PHPExcel();
        $objExcel->setActiveSheetIndex(0);
        $sheet = $objExcel->getActiveSheet()->setTitle('DS Voucher');
        $rowCount = 1;

        // Tiêu đề cột
        $sheet->setCellValue('A' . $rowCount, 'Mã Code');
        $sheet->setCellValue('B' . $rowCount, 'Số Tiền Giảm');
        $sheet->setCellValue('C' . $rowCount, 'Số Lượng');
        $sheet->setCellValue('D' . $rowCount, 'Trạng Thái');

        // Định dạng tiêu đề
        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        
        $sheet->getStyle('A1:D1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('00FF00');
        $sheet->getStyle('A1:D1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1:D1')->getFont()->setBold(true);

        // Đổ dữ liệu
        $data = $this->kmModel->DanhSach("");
        while ($row = mysqli_fetch_array($data)) {
            $rowCount++;
            $trangthai = ($row['SoLuong'] > 0) ? "Đang chạy" : "Hết lượt";

            $sheet->setCellValue('A' . $rowCount, $row['TenMa']);
            $sheet->setCellValue('B' . $rowCount, $row['SoTienGiam']);
            $sheet->setCellValue('C' . $rowCount, $row['SoLuong']);
            $sheet->setCellValue('D' . $rowCount, $trangthai);
        }

        // Kẻ khung
        $styleArray = array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN)));
        $sheet->getStyle('A1:' . 'D' . ($rowCount))->applyFromArray($styleArray);

        // Lưu và tải về
        $fileName = 'DanhSachKhuyenMai.xlsx';
        $objWriter = new PHPExcel_Writer_Excel2007($objExcel);
        $objWriter->save($fileName);

        if (ob_get_length()) ob_end_clean();
        
        header('Content-Description: File Transfer');
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($fileName));
        readfile($fileName);
        unlink($fileName);
        exit;
    }

    // --- 2. NHẬP EXCEL ---
    public function NhapExcel() {
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
                        $ten = strtoupper($sheet->getCell('A' . $i)->getValue()); // Mã code (Viết hoa)
                        $tien = $sheet->getCell('B' . $i)->getValue();
                        $soluong = $sheet->getCell('C' . $i)->getValue();

                        // Kiểm tra: Có Tên Mã + Mã chưa trùng
                        if (!empty($ten) && !$this->kmModel->CheckTrung($ten)) {
                            // Nếu tiền hoặc số lượng trống thì cho mặc định
                            if(empty($tien)) $tien = 0;
                            if(empty($soluong)) $soluong = 100;

                            $this->kmModel->ThemMoi($ten, $tien, $soluong);
                            $count++;
                        }
                    }
                    echo "<script>alert('Đã nhập thành công $count mã khuyến mãi!'); window.location.href='http://localhost/Baitaplon/Khuyenmai';</script>";

                } catch (Exception $e) {
                    echo "<script>alert('Lỗi: " . $e->getMessage() . "'); window.history.back();</script>";
                }
            } else {
                echo "<script>alert('Vui lòng chọn file Excel!'); window.history.back();</script>";
            }
        }
    }
}
?>