<?php
class Khachhang extends controller {
    public $khModel;

    public function __construct() {
        $this->khModel = $this->model("KhachhangModel");
    }

    public function Get_data() {
        $keyword = "";
        if(isset($_POST['btnTimKiem'])) $keyword = $_POST['txtTimKiem'];

        $this->view("Master", [
            "page" => "Khachhang_V",
            "dulieu" => $this->khModel->DanhSach($keyword),
            "keyword" => $keyword
        ]);
    }

    // --- SỬA HÀM THÊM MỚI ---
    public function Themmoi() {
        if(isset($_POST['btnThem'])) {
            $id = $_POST['txtMaKH']; // Lấy mã khách tự nhập
            $ten = $_POST['txtTen'];
            $sdt = $_POST['txtSDT'];
            
            // 1. Kiểm tra trùng mã
            if($this->khModel->CheckTrung($id)) {
                echo "<script>alert('Mã khách hàng [$id] đã tồn tại! Vui lòng chọn mã khác.'); window.history.back();</script>";
                return;
            }

            // 2. Thêm mới
            $this->khModel->Them($id, $ten, $sdt);
            echo "<script>alert('Thêm khách hàng thành công!'); window.location.href='http://localhost/Baitaplon/Khachhang';</script>";
        }
    }

    public function Xoa($id) {
        $this->khModel->Xoa($id);
        header("Location: http://localhost/Baitaplon/Khachhang");
    }

    public function Sua($id) {
        $this->view("Master", [
            "page" => "Khachhang_V",
            "dulieu" => $this->khModel->DanhSach(),
            "editData" => mysqli_fetch_array($this->khModel->GetByID($id))
        ]);
    }

    public function CapNhat() {
        if(isset($_POST['btnLuu'])) {
            $id = $_POST['txtMaKH']; // Lấy ID từ ô readonly (name là txtMaKH thay vì txtID hidden)
            $ten = $_POST['txtTen'];
            $sdt = $_POST['txtSDT'];
            
            $this->khModel->Sua($id, $ten, $sdt);
            
            echo "<script>alert('Cập nhật thông tin thành công!'); window.location.href='http://localhost/Baitaplon/Khachhang';</script>";
        }
    }


    // --- 1. XUẤT EXCEL (Chuẩn .xlsx) ---
    public function XuatExcel() {
        $objExcel = new PHPExcel();
        $objExcel->setActiveSheetIndex(0);
        $sheet = $objExcel->getActiveSheet()->setTitle('DS Khach Hang');
        $rowCount = 1;

        // Tiêu đề cột
        $sheet->setCellValue('A' . $rowCount, 'Mã Khách Hàng');
        $sheet->setCellValue('B' . $rowCount, 'Tên Khách hàng');
        $sheet->setCellValue('C' . $rowCount, 'Điện Thoại');
        $sheet->setCellValue('D' . $rowCount, 'Điểm Tích Lũy');

        // Định dạng tiêu đề
        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getStyle('A1:D1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('00FF00'); // Xanh lá
        $sheet->getStyle('A1:D1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1:D1')->getFont()->setBold(true);

        // Đổ dữ liệu
        $data = $this->khModel->DanhSach("");
        while ($row = mysqli_fetch_array($data)) {
            $rowCount++;
            // Ưu tiên lấy MaCode (Mã người dùng nhập), nếu không có thì lấy ID tự tăng
            $maHienThi = isset($row['MaCode']) && $row['MaCode']!='' ? $row['MaCode'] : $row['MaKH'];
            
            $sheet->setCellValue('A' . $rowCount, $maHienThi);
            $sheet->setCellValue('B' . $rowCount, $row['TenKH']);
            $sheet->setCellValue('C' . $rowCount, $row['DienThoai']);
            $sheet->setCellValue('D' . $rowCount, $row['DiemTichLuy']);
        }

        // Kẻ khung
        $styleArray = array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN)));
        $sheet->getStyle('A1:' . 'D' . ($rowCount))->applyFromArray($styleArray);

        // Lưu và tải về
        $fileName = 'DanhSachKhachHang.xlsx';
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
                        $diem = $sheet->getCell('D' . $i)->getValue();

                        // Nếu có Mã và Tên, và Mã chưa trùng thì thêm
                        if (!empty($macode) && !empty($ten) && !$this->khModel->CheckTrung($macode)) {
                            $this->khModel->Them($macode, $ten, $sdt, $diem);
                            $count++;
                        }
                    }
                    echo "<script>alert('Đã nhập thành công $count danh mục!'); window.location.href='http://localhost/Baitaplon/Khachhang';</script>";

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