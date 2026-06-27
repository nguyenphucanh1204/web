<?php
class Nhacungcap extends controller {
    public $nccModel;

    public function __construct() {
        $this->nccModel = $this->model("NhacungcapModel");
    }

    // Hiển thị & Tìm kiếm
    public function Get_data() {
        $keyword = "";
        if(isset($_POST['btnTimKiem'])) $keyword = $_POST['txtTimKiem'];

        $this->view("Master", [
            "page" => "Nhacungcap_V",
            "dulieu" => $this->nccModel->DanhSach($keyword),
            "keyword" => $keyword
        ]);
    }

    // Lưu (Thêm & Sửa)
    public function Luu() {
        if(isset($_POST['btnThem']) || isset($_POST['btnLuu'])) {
            $macode = $_POST['txtMaCode'];
            $ten = $_POST['txtTen'];
            $sdt = $_POST['txtSDT'];
            $diachi = $_POST['txtDiaChi'];

            if(isset($_POST['btnLuu'])) {
                // --- SỬA ---
                $id = $_POST['txtID'];
                $this->nccModel->Sua($id, $macode, $ten, $sdt, $diachi);
                echo "<script>alert('Cập nhật thành công!'); window.location.href='http://localhost/Baitaplon/Nhacungcap';</script>";
            } else {
                // --- THÊM MỚI (Có Check Trùng) ---
                if($this->nccModel->CheckTrungMa($macode)) {
                    echo "<script>alert('Mã NCC [$macode] đã tồn tại! Vui lòng chọn mã khác.'); window.history.back();</script>";
                    return;
                }
                
                $this->nccModel->Them($macode, $ten, $sdt, $diachi);
                echo "<script>alert('Thêm mới thành công!'); window.location.href='http://localhost/Baitaplon/Nhacungcap';</script>";
            }
        }
    }

    // Các hàm khác
    public function Sua($id) {
        $this->view("Master", [
            "page" => "Nhacungcap_V",
            "dulieu" => $this->nccModel->DanhSach(),
            "edit_data" => mysqli_fetch_array($this->nccModel->GetByID($id))
        ]);
    }

    public function Xoa($id) {
        $this->nccModel->Xoa($id);
        header("Location: http://localhost/Baitaplon/Nhacungcap");
    }


    // --- 1. XUẤT EXCEL (Chuẩn .xlsx) ---
    public function XuatExcel() {
        $objExcel = new PHPExcel();
        $objExcel->setActiveSheetIndex(0);
        $sheet = $objExcel->getActiveSheet()->setTitle('DS Nha Cung Cap');
        $rowCount = 1;

        // Tiêu đề cột
        $sheet->setCellValue('A' . $rowCount, 'Mã NCC');
        $sheet->setCellValue('B' . $rowCount, 'Tên Nhà Cung Cấp');
        $sheet->setCellValue('C' . $rowCount, 'Điện Thoại');
        $sheet->setCellValue('D' . $rowCount, 'Địa Chỉ');

        // Định dạng tiêu đề
        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getStyle('A1:D1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('00FF00'); // Xanh lá
        $sheet->getStyle('A1:D1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1:D1')->getFont()->setBold(true);

        // Đổ dữ liệu
        $data = $this->nccModel->DanhSach("");
        while ($row = mysqli_fetch_array($data)) {
            $rowCount++;
            // Ưu tiên lấy MaCode (Mã người dùng nhập), nếu không có thì lấy ID tự tăng
            $maHienThi = isset($row['MaCode']) && $row['MaCode']!='' ? $row['MaCode'] : $row['MaNCC'];
            
            $sheet->setCellValue('A' . $rowCount, $maHienThi);
            $sheet->setCellValue('B' . $rowCount, $row['TenNCC']);
            $sheet->setCellValue('C' . $rowCount, $row['DienThoai']);
            $sheet->setCellValue('D' . $rowCount, $row['DiaChi']);
        }

        // Kẻ khung
        $styleArray = array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN)));
        $sheet->getStyle('A1:' . 'D' . ($rowCount))->applyFromArray($styleArray);

        // Lưu và tải về
        $fileName = 'DanhSachNhaCungCap.xlsx';
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
                        $macode = $sheet->getCell('A' . $i)->getValue();
                        $ten = $sheet->getCell('B' . $i)->getValue();
                        $sdt = $sheet->getCell('C' . $i)->getValue();
                        $diachi = $sheet->getCell('D' . $i)->getValue();

                        // Nếu có Mã và Tên, và Mã chưa trùng thì thêm
                        if (!empty($macode) && !empty($ten) && !$this->nccModel->CheckTrungMa($macode)) {
                            $this->nccModel->Them($macode, $ten, $sdt, $diachi);
                            $count++;
                        }
                    }
                    echo "<script>alert('Đã nhập thành công $count danh mục!'); window.location.href='http://localhost/Baitaplon/Nhacungcap';</script>";

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