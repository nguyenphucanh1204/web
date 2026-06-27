<?php
class Baocao extends controller {
    public $bcModel;

    public function __construct() {
        $this->bcModel = $this->model("BaocaoModel");
    }

    public function Get_data() {
        // Mặc định lấy từ đầu tháng đến hiện tại
        $tungay = isset($_POST['txtTuNgay']) ? $_POST['txtTuNgay'] : date('Y-m-01');
        $denngay = isset($_POST['txtDenNgay']) ? $_POST['txtDenNgay'] : date('Y-m-d');

        // Gọi 3 hàm báo cáo từ Model
        $top_sanpham = $this->bcModel->GetTopBanChay($tungay, $denngay);
        $sap_hethan = $this->bcModel->GetSapHetHan();
        $tonkho_thap = $this->bcModel->GetTonKhoThap();

        $this->view("Master", [
            "page" => "Baocao_V",
            "tungay" => $tungay,
            "denngay" => $denngay,
            "top_sp" => $top_sanpham,
            "sap_hethan" => $sap_hethan,
            "tonkho_thap" => $tonkho_thap
        ]);
    }

    // Xuất Excel: Top Sản Phẩm Bán Chạy
    public function XuatExcel() {
        $tungay = $_POST['txtTuNgayEx']; 
        $denngay = $_POST['txtDenNgayEx'];

        $objExcel = new PHPExcel();
        $objExcel->setActiveSheetIndex(0);
        $sheet = $objExcel->getActiveSheet()->setTitle('Top Ban Chay');

        // Tiêu đề
        $sheet->setCellValue('A1', 'TOP SẢN PHẨM BÁN CHẠY');
        $sheet->setCellValue('A2', "Từ ngày: " . date('d/m/Y', strtotime($tungay)) . " - Đến ngày: " . date('d/m/Y', strtotime($denngay)));
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);

        // Header bảng
        $sheet->setCellValue('A4', 'Tên Sản Phẩm');
        $sheet->setCellValue('B4', 'Số Lượng Bán');
        $sheet->setCellValue('C4', 'Doanh Số');
        
        $sheet->getColumnDimension('A')->setWidth(30);
        $sheet->getColumnDimension('B')->setWidth(15);
        $sheet->getColumnDimension('C')->setWidth(20);
        $sheet->getStyle('A4:C4')->getFont()->setBold(true);
        $sheet->getStyle('A4:C4')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('FFD700');

        $data = $this->bcModel->GetTopBanChay($tungay, $denngay);
        $row = 5;
        while ($r = mysqli_fetch_array($data)) {
            $sheet->setCellValue('A' . $row, $r['TenSP']);
            $sheet->setCellValue('B' . $row, $r['SoLuongBan']);
            $sheet->setCellValue('C' . $row, $r['DoanhSo']);
            $row++;
        }

        $fileName = 'BaoCao_BanChay.xlsx';
        $objWriter = new PHPExcel_Writer_Excel2007($objExcel);
        $objWriter->save($fileName);
        if (ob_get_length()) ob_end_clean();
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        header('Content-Length: ' . filesize($fileName));
        readfile($fileName);
        unlink($fileName);
    }
}
?>