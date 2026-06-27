<?php
class Danhmuc extends controller {
    public $dmModel;

    public function __construct() {
        $this->dmModel = $this->model("DanhmucModel");
    }

    public function Get_data() {
        $keyword = "";
        if(isset($_POST['btnTimKiem'])) $keyword = $_POST['txtTimKiem'];

        $this->view("Master", [
            "page" => "Danhmuc_V",
            "dulieu" => $this->dmModel->DanhSach($keyword),
            "keyword" => $keyword
        ]);
    }

    public function Luu() {
        if(isset($_POST['btnThem']) || isset($_POST['btnLuu'])) {
            $macode = $_POST['txtMaCode']; 
            $ten = $_POST['txtTen'];
            $mota = $_POST['txtMoTa'];
            
            if(isset($_POST['btnLuu'])) {
                // Sửa
                $id = $_POST['txtID'];
                $this->dmModel->Sua($id, $macode, $ten, $mota);
                echo "<script>alert('Đã cập nhật!'); window.location.href='http://localhost/Baitaplon/Danhmuc';</script>";
            } else {
                // Thêm mới
                if($this->dmModel->CheckTrungMa($macode)){
                    echo "<script>alert('Mã danh mục [$macode] đã tồn tại!'); window.history.back();</script>";
                    return;
                }
                $this->dmModel->Them($macode, $ten, $mota);
                echo "<script>alert('Thêm mới thành công!'); window.location.href='http://localhost/Baitaplon/Danhmuc';</script>";
            }
        }
    }
    
    public function Sua($id) {
        $this->view("Master", [
            "page" => "Danhmuc_V",
            "dulieu" => $this->dmModel->DanhSach(),
            "edit_data" => mysqli_fetch_array($this->dmModel->GetByID($id))
        ]);
    }

    public function Xoa($id) {
        $this->dmModel->Xoa($id);
        header("Location: http://localhost/Baitaplon/Danhmuc");
    }

    // --- 1. XUẤT EXCEL (Chuẩn .xlsx) ---
    public function XuatExcel() {
        $objExcel = new PHPExcel();
        $objExcel->setActiveSheetIndex(0);
        $sheet = $objExcel->getActiveSheet()->setTitle('DS Danh Muc');
        $rowCount = 1;

        // Tiêu đề cột
        $sheet->setCellValue('A' . $rowCount, 'Mã Danh Mục');
        $sheet->setCellValue('B' . $rowCount, 'Tên Danh Mục');
        $sheet->setCellValue('C' . $rowCount, 'Mô Tả');

        // Định dạng tiêu đề
        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getStyle('A1:C1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('00FF00'); // Xanh lá
        $sheet->getStyle('A1:C1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1:C1')->getFont()->setBold(true);

        // Đổ dữ liệu
        $data = $this->dmModel->DanhSach("");
        while ($row = mysqli_fetch_array($data)) {
            $rowCount++;
            // Ưu tiên lấy MaCode (Mã người dùng nhập), nếu không có thì lấy ID tự tăng
            $maHienThi = isset($row['MaCode']) && $row['MaCode']!='' ? $row['MaCode'] : $row['MaDM'];
            
            $sheet->setCellValue('A' . $rowCount, $maHienThi);
            $sheet->setCellValue('B' . $rowCount, $row['TenDM']);
            $sheet->setCellValue('C' . $rowCount, $row['MoTa']);
        }

        // Kẻ khung
        $styleArray = array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN)));
        $sheet->getStyle('A1:' . 'C' . ($rowCount))->applyFromArray($styleArray);

        // Lưu và tải về
        $fileName = 'DanhSachDanhmuc.xlsx';
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
                        $mota = $sheet->getCell('C' . $i)->getValue();

                        // Nếu có Mã và Tên, và Mã chưa trùng thì thêm
                        if (!empty($macode) && !empty($ten) && !$this->dmModel->CheckTrungMa($macode)) {
                            $this->dmModel->Them($macode, $ten, $mota);
                            $count++;
                        }
                    }
                    echo "<script>alert('Đã nhập thành công $count danh mục!'); window.location.href='http://localhost/Baitaplon/Danhmuc';</script>";

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