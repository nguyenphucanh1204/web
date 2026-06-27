<?php
class Nhanvien extends controller {
    public $nvModel;

    public function __construct() {
        $this->nvModel = $this->model("NhanvienModel");
    }

    public function Get_data() {
        $keyword = "";
        // Nhận từ khóa tìm kiếm nếu có
        if(isset($_POST['btnTimKiem'])) {
            $keyword = $_POST['txtTimKiem'];
        }

        $this->view("Master", [
            "page" => "Nhanvien_V",
            "dulieu" => $this->nvModel->DanhSach($keyword),
            "keyword" => $keyword // Truyền lại để hiện ở ô input
        ]);
    }

    public function Themmoi() {
        if(isset($_POST['btnThem'])) {
            $manv = $_POST['txtMaNV'];

            // Check trùng
            if($this->nvModel->CheckTrungMa($manv)) {
                echo "<script>alert('Lỗi: Mã nhân viên [$manv] đã tồn tại! Vui lòng chọn mã khác.'); window.history.back();</script>";
                return; 
            }

            $ten = $_POST['txtTen'];
            $email = $_POST['txtEmail'];
            // Đã bỏ mật khẩu
            $chucvu = $_POST['ddlChucVu'];
            
            // Gọi hàm Them trong Model (lưu ý Model cũng phải sửa bỏ tham số mật khẩu)
            $this->nvModel->Them($manv, $ten, $email, $chucvu);
            echo "<script>alert('Thêm nhân viên thành công!'); window.location.href='http://localhost/Baitaplon/Nhanvien';</script>";
        }
    }

    public function Xoa($id) {
        $this->nvModel->Xoa($id);
        header("Location: http://localhost/Baitaplon/Nhanvien");
    }

    public function Sua($id) {
        $this->view("Master", [
            "page" => "Nhanvien_V",
            "dulieu" => $this->nvModel->DanhSach(),
            "editData" => mysqli_fetch_array($this->nvModel->GetByID($id))
        ]);
    }

    public function CapNhat() {
        if(isset($_POST['btnLuu'])) {
            $manv = $_POST['txtMaNV'];
            $ten = $_POST['txtTen'];
            $email = $_POST['txtEmail'];
            // Đã bỏ mật khẩu
            $chucvu = $_POST['ddlChucVu'];
            
            // Gọi hàm Sua trong Model (lưu ý Model cũng phải sửa bỏ tham số mật khẩu)
            $this->nvModel->Sua($manv, $ten, $email, $chucvu);
            echo "<script>alert('Cập nhật thành công!'); window.location.href='http://localhost/Baitaplon/Nhanvien';</script>";
        }
    }

    // --- XUẤT EXCEL (Chuẩn .xlsx) - Đã bỏ cột Mật khẩu ---
    public function XuatExcel() {
        $objExcel = new PHPExcel();
        $objExcel->setActiveSheetIndex(0);
        $sheet = $objExcel->getActiveSheet()->setTitle('DS Nhan Vien');
        $rowCount = 1;

        // Tiêu đề cột (Chỉ còn A, B, C, D)
        $sheet->setCellValue('A' . $rowCount, 'Mã Nhân Viên');
        $sheet->setCellValue('B' . $rowCount, 'Họ Tên');
        $sheet->setCellValue('C' . $rowCount, 'Email');
        $sheet->setCellValue('D' . $rowCount, 'Chức Vụ');

        // Định dạng tiêu đề
        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        
        $sheet->getStyle('A1:D1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('00FF00');
        $sheet->getStyle('A1:D1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1:D1')->getFont()->setBold(true);

        // Đổ dữ liệu
        $data = $this->nvModel->DanhSach("");
        while ($row = mysqli_fetch_array($data)) {
            $rowCount++;
            $sheet->setCellValue('A' . $rowCount, $row['MaNV']);
            $sheet->setCellValue('B' . $rowCount, $row['HoTen']);
            $sheet->setCellValue('C' . $rowCount, $row['Email']);
            $sheet->setCellValue('D' . $rowCount, $row['ChucVu']);
        }

        // Kẻ khung
        $styleArray = array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN)));
        $sheet->getStyle('A1:' . 'D' . ($rowCount))->applyFromArray($styleArray);

        // Lưu và tải về
        $fileName = 'DanhSachNhanVien.xlsx';
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

    // --- NHẬP EXCEL - Đã bỏ cột Mật khẩu ---
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
                        $manv = $sheet->getCell('A' . $i)->getValue();
                        $ten = $sheet->getCell('B' . $i)->getValue();
                        $email = $sheet->getCell('C' . $i)->getValue();
                        // Cột D bây giờ là Chức vụ (trước là mật khẩu)
                        $chucvu = $sheet->getCell('D' . $i)->getValue(); 

                        // Nếu chưa có chức vụ thì mặc định là Nhân viên
                        if(empty($chucvu)) $chucvu = "Nhân viên";

                        // Kiểm tra: Có Mã + Có Tên + Mã chưa trùng
                        if (!empty($manv) && !empty($ten) && !$this->nvModel->CheckTrungMa($manv)) {
                            // Gọi hàm Them không có mật khẩu
                            $this->nvModel->Them($manv, $ten, $email, $chucvu);
                            $count++;
                        }
                    }
                    echo "<script>alert('Đã nhập thành công $count nhân viên!'); window.location.href='http://localhost/Baitaplon/Nhanvien';</script>";

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