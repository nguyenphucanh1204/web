<?php
class Phuongthuc extends controller {
    public $ptModel;

    public function __construct() {
        $this->ptModel = $this->model("PhuongthucModel");
    }

    public function Get_data() {
        $this->view("Master", [
            "page" => "Phuongthuc_V", // Đảm bảo đúng tên file View của bạn
            "dulieu" => $this->ptModel->DanhSach()
        ]);
    }

    public function Luu() {
        if(isset($_POST['btnThem']) || isset($_POST['btnLuu'])) {
            $ten = $_POST['txtTen'];
            
            // --- XỬ LÝ UPLOAD ẢNH ---
            $hinh = "";
            if(isset($_FILES['fileHinh']) && $_FILES['fileHinh']['name'] != "") {
                $hinh = basename($_FILES['fileHinh']['name']);
                $target_dir = "./Public/Images/";
                $target_file = $target_dir . $hinh;
                move_uploaded_file($_FILES["fileHinh"]["tmp_name"], $target_file);
            }

            if(isset($_POST['btnLuu'])) {
                // --- SỬA ---
                $id = $_POST['txtID'];
                $trangthai = $_POST['ddlTrangThai'];
                $this->ptModel->Sua($id, $ten, $trangthai, $hinh);
                echo "<script>alert('Cập nhật thành công!'); window.location.href='http://localhost/Baitaplon/Phuongthuc';</script>";
            } else {
                // --- THÊM ---
                if($this->ptModel->CheckTrung($ten)){
                    echo "<script>alert('Tên phương thức đã tồn tại!'); window.history.back();</script>";
                    return;
                }
                $this->ptModel->Them($ten, $hinh);
                echo "<script>alert('Thêm mới thành công!'); window.location.href='http://localhost/Baitaplon/Phuongthuc';</script>";
            }
        }
    }

    // Các hàm Sua, Xoa giữ nguyên...
    public function Sua($id) {
        $this->view("Master", [
            "page" => "Phuongthuc_V",
            "dulieu" => $this->ptModel->DanhSach(),
            "edit_data" => mysqli_fetch_array($this->ptModel->GetByID($id))
        ]);
    }
    public function Xoa($id) {
        $this->ptModel->Xoa($id);
        header("Location: http://localhost/Baitaplon/Phuongthuc");
    }
}
?>