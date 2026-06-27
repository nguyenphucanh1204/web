<?php
class Sanpham extends controller {
    public $spModel;
    public $dmModel;
    public $nccModel;

    public function __construct() {
        $this->spModel = $this->model("SanphamModel");
        $this->dmModel = $this->model("DanhmucModel");
        $this->nccModel = $this->model("NhacungcapModel");
    }

    // 1. Hiển thị (Giữ nguyên)
    public function Get_data() {
        $keyword = "";
        if(isset($_POST['btnTimKiem'])) {
            $keyword = $_POST['txtTimKiem'];
        }

        $this->view("Master", [
            "page" => "Sanpham_V",
            "dulieu" => $this->spModel->GetSanpham($keyword),
            "danhmuc" => $this->dmModel->DanhSach(),
            "nhacungcap" => $this->nccModel->DanhSach(),
            "keyword" => $keyword
        ]);
    }

    // 2. THÊM MỚI (Cập nhật lấy txtHSD)
    public function Themmoi() {
        if(isset($_POST['btnThem'])) {
            $masp = $_POST['txtMaSP'];
            if($this->spModel->CheckTrungMa($masp)) {
                echo "<script>alert('Lỗi: Mã sản phẩm [$masp] đã tồn tại!'); window.history.back();</script>";
                return;
            }

            $tensp = $_POST['txtTenSP'];
            $madm = $_POST['ddlDanhmuc'];
            $mancc = $_POST['ddlNCC'];
            $gia = $_POST['txtGia'];
            $soluong = $_POST['txtSoLuong'];
            $hsd = $_POST['txtHSD']; // Lấy hạn sử dụng
            
            $hinhanh = "";
            if(isset($_FILES['txtHinhAnh']['name']) && $_FILES['txtHinhAnh']['name'] != ""){
                $hinhanh = basename($_FILES['txtHinhAnh']['name']);
                move_uploaded_file($_FILES['txtHinhAnh']['tmp_name'], "./Public/Images/" . $hinhanh);
            }

            $this->spModel->InsertSanpham($masp, $tensp, $madm, $mancc, $gia, $soluong, $hinhanh, $hsd);
            echo "<script>alert('Thêm thành công!'); window.location.href='http://localhost/Baitaplon/Sanpham';</script>";
        }
    }

    // 3. Xóa (Giữ nguyên)
    public function Xoa($masp) {
        $this->spModel->DeleteSanpham($masp);
        header("Location: http://localhost/Baitaplon/Sanpham");
    }

    // 4. Sửa (Giữ nguyên)
    public function Sua($masp) {
        $this->view("Master", [
            "page" => "Sanpham_V",
            "dulieu" => $this->spModel->GetSanpham(),
            "danhmuc" => $this->dmModel->DanhSach(),
            "nhacungcap" => $this->nccModel->DanhSach(),
            "editData" => mysqli_fetch_array($this->spModel->GetSanphamByID($masp))
        ]);
    }

    // 5. CẬP NHẬT (Cập nhật lấy txtHSD)
    public function CapNhat() {
        if(isset($_POST['btnLuu'])) {
            $masp = $_POST['txtMaSP'];
            $tensp = $_POST['txtTenSP'];
            $madm = $_POST['ddlDanhmuc'];
            $mancc = $_POST['ddlNCC'];
            $gia = $_POST['txtGia'];
            $soluong = $_POST['txtSoLuong'];
            $hsd = $_POST['txtHSD']; // Lấy hạn sử dụng

            $hinhanh = "";
            if(isset($_FILES['txtHinhAnh']['name']) && $_FILES['txtHinhAnh']['name'] != ""){
                $hinhanh = basename($_FILES['txtHinhAnh']['name']);
                move_uploaded_file($_FILES['txtHinhAnh']['tmp_name'], "./Public/Images/" . $hinhanh);
            }

            $this->spModel->UpdateSanpham($masp, $tensp, $madm, $mancc, $gia, $soluong, $hinhanh, $hsd);
            echo "<script>alert('Cập nhật thành công!'); window.location.href='http://localhost/Baitaplon/Sanpham';</script>";
        }
    }

    // --- 6. XUẤT EXCEL (Thêm cột HSD) ---
    public function XuatExcel() {
        $objExcel = new PHPExcel();
        $objExcel->setActiveSheetIndex(0);
        $sheet = $objExcel->getActiveSheet()->setTitle('DS San Pham');
        $rowCount = 1;

        // Tiêu đề cột
        $sheet->setCellValue('A1', 'Mã SP');
        $sheet->setCellValue('B1', 'Tên Sản Phẩm');
        $sheet->setCellValue('C1', 'Mã DM');
        $sheet->setCellValue('D1', 'Mã NCC');
        $sheet->setCellValue('E1', 'Giá Bán');
        $sheet->setCellValue('F1', 'Số Lượng');
        $sheet->setCellValue('G1', 'Hình Ảnh');
        $sheet->setCellValue('H1', 'Hạn Sử Dụng'); // Cột mới

        // Định dạng tiêu đề
        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('H')->setAutoSize(true);
        $sheet->getStyle('A1:H1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('00FF00');
        $sheet->getStyle('A1:H1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1:H1')->getFont()->setBold(true);

        // Đổ dữ liệu
        $data = $this->spModel->GetSanpham("");
        while ($row = mysqli_fetch_array($data)) {
            $rowCount++;
            $sheet->setCellValue('A' . $rowCount, $row['MaSP']);
            $sheet->setCellValue('B' . $rowCount, $row['TenSP']);
            $sheet->setCellValue('C' . $rowCount, $row['MaDM']);
            $sheet->setCellValue('D' . $rowCount, $row['MaNCC']);
            $sheet->setCellValue('E' . $rowCount, $row['GiaBan']);
            $sheet->setCellValue('F' . $rowCount, $row['SoLuongTon']);
            $sheet->setCellValue('G' . $rowCount, $row['HinhAnh']);
            $sheet->setCellValue('H' . $rowCount, $row['HanSuDung']); // Dữ liệu HSD
        }

        // Kẻ khung
        $styleArray = array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN)));
        $sheet->getStyle('A1:' . 'H' . ($rowCount))->applyFromArray($styleArray);

        // Xuất file
        $fileName = 'DanhSachSanpham.xlsx';
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

    // --- 7. NHẬP EXCEL (Thêm đọc cột H: HSD) ---
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
                    for ($i = 2; $i <= $TotalRow; $i++) {
                        $masp = $sheet->getCell('A' . $i)->getValue();
                        $tensp = $sheet->getCell('B' . $i)->getValue();
                        $madm = $sheet->getCell('C' . $i)->getValue();
                        $mancc = $sheet->getCell('D' . $i)->getValue();
                        $gia = $sheet->getCell('E' . $i)->getValue();
                        $soluong = $sheet->getCell('F' . $i)->getValue();
                        $hinhanh = $sheet->getCell('G' . $i)->getValue();
                        $hsd = $sheet->getCell('H' . $i)->getValue(); // Đọc cột H

                        // Xử lý ngày tháng Excel
                        if(!empty($hsd) && is_numeric($hsd)) {
                            $hsd = date('Y-m-d', PHPExcel_Shared_Date::ExcelToPHP($hsd));
                        } else {
                            $hsd = NULL;
                        }

                        if (empty($hinhanh)) $hinhanh = "no-image.jpg";

                        if (!empty($masp) && !$this->spModel->CheckTrungMa($masp)) {
                            $this->spModel->InsertSanpham($masp, $tensp, $madm, $mancc, $gia, $soluong, $hinhanh, $hsd);
                            $count++;
                        }
                    }
                    echo "<script>alert('Đã nhập thành công $count sản phẩm!'); window.location.href='http://localhost/Baitaplon/Sanpham';</script>";

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