<?php
class Taikhoan extends Controller {
    public $tkModel;

    public function __construct() {
        $this->tkModel = $this->model("TaikhoanModel");
    }

    // 1. Hiển thị danh sách & Tìm kiếm
    function Get_data(){
        $keyword = "";
        if(isset($_POST['btnTimKiem'])) {
            $keyword = $_POST['txtTimKiem'];
        }

        $this->view("Master", [
            "page" => "Taikhoan_V",
            "dulieu" => $this->tkModel->GetAll($keyword),
            "keyword" => $keyword
        ]);
    }

    // 2. Hiển thị giao diện Sửa
    function Sua($id){
        $this->view("Master", [
            "page" => "Taikhoan_V",
            "dulieu" => $this->tkModel->GetAll(),
            "editData" => $this->tkModel->GetByID($id)
        ]);
    }

    // 3. Xử lý Lưu (Thêm hoặc Sửa)
    function Save(){
        if(isset($_POST['btnLuu'])){
            $id = $_POST['txtID'];
            $user = $_POST['txtUser'];
            $pass = $_POST['txtPass'];
            $name = $_POST['txtHoTen'];
            $role = $_POST['ddlRole'];

            if($id == ""){
                // Thêm mới
                $kq = $this->tkModel->Insert($user, $pass, $name, $role);
                if($kq) echo "<script>alert('Thêm tài khoản thành công!');</script>";
                else echo "<script>alert('Lỗi: Tên đăng nhập đã tồn tại!');</script>";
            } else {
                // Cập nhật
                $this->tkModel->Update($id, $pass, $name, $role);
                echo "<script>alert('Cập nhật thành công!');</script>";
            }
            
            // Quay về trang chính
            echo "<script>window.location.href='http://localhost/Baitaplon/Taikhoan';</script>";
        }
    }

    // 4. Xóa
    function Xoa($id){
        $this->tkModel->Delete($id);
        header("Location: http://localhost/Baitaplon/Taikhoan");
    }

    // --- 5. XUẤT EXCEL ---
    public function XuatExcel() {
        $objExcel = new PHPExcel();
        $objExcel->setActiveSheetIndex(0);
        $sheet = $objExcel->getActiveSheet()->setTitle('DS Tai Khoan');
        $rowCount = 1;

        // Tiêu đề cột
        $sheet->setCellValue('A1', 'Username');
        $sheet->setCellValue('B1', 'Mật khẩu');
        $sheet->setCellValue('C1', 'Họ và Tên');
        $sheet->setCellValue('D1', 'Quyền hạn');

        // Định dạng tiêu đề
        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        
        $sheet->getStyle('A1:D1')->getFont()->setBold(true);
        $sheet->getStyle('A1:D1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('11998e');
        $sheet->getStyle('A1:D1')->getFont()->getColor()->setRGB('FFFFFF');

        // Đổ dữ liệu
        $data = $this->tkModel->GetAll("");
        while ($row = mysqli_fetch_array($data)) {
            $rowCount++;
            // Xử lý hiển thị quyền
            $roleName = ($row['role'] == 1) ? "Admin" : "User";

            $sheet->setCellValue('A' . $rowCount, $row['username']);
            $sheet->setCellValue('B' . $rowCount, $row['password']);
            $sheet->setCellValue('C' . $rowCount, $row['hoten']);
            $sheet->setCellValue('D' . $rowCount, $roleName);
        }

        // Kẻ khung
        $styleArray = array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN)));
        $sheet->getStyle('A1:' . 'D' . $rowCount)->applyFromArray($styleArray);

        // Xuất file
        $fileName = 'DanhSachTaiKhoan.xlsx';
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

    // --- 6. NHẬP EXCEL ---
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
                        $user = $sheet->getCell('A' . $i)->getValue();
                        $pass = $sheet->getCell('B' . $i)->getValue();
                        $hoten = $sheet->getCell('C' . $i)->getValue();
                        $roleRaw = $sheet->getCell('D' . $i)->getValue();

                        // Xử lý quyền hạn (Nhập 1 hoặc Admin đều hiểu là 1)
                        $role = 0; // Mặc định là User
                        if($roleRaw == 1 || strtolower($roleRaw) == 'admin' || strtolower($roleRaw) == 'quản trị') {
                            $role = 1;
                        }

                        if (!empty($user) && !empty($pass) && !empty($hoten)) {
                            // Hàm Insert đã có sẵn check trùng username
                            if($this->tkModel->Insert($user, $pass, $hoten, $role)) {
                                $count++;
                            }
                        }
                    }
                    echo "<script>alert('Đã nhập thành công $count tài khoản!'); window.location.href='http://localhost/Baitaplon/Taikhoan';</script>";

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