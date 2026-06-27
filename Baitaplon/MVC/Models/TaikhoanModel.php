<?php
class TaikhoanModel extends connectDB {
    
    // 1. Lấy danh sách
    public function GetAll($keyword = ""){
        $sql = "SELECT * FROM taikhoan";
        if($keyword != ""){
            $sql .= " WHERE username LIKE '%$keyword%' OR hoten LIKE '%$keyword%'";
        }
        $sql .= " ORDER BY id DESC";
        return mysqli_query($this->con, $sql);
    }

    public function GetByID($id){
        $sql = "SELECT * FROM taikhoan WHERE id = $id";
        $result = mysqli_query($this->con, $sql);
        return mysqli_fetch_assoc($result);
    }

    // 2. Thêm mới (BỎ MD5)
    public function Insert($user, $pass, $hoten, $role){
        $check = mysqli_query($this->con, "SELECT * FROM taikhoan WHERE username='$user'");
        if(mysqli_num_rows($check) > 0) return false;

        // Lưu trực tiếp pass thô, không mã hóa
        $sql = "INSERT INTO taikhoan (username, password, hoten, role) 
                VALUES ('$user', '$pass', '$hoten', $role)";
        return mysqli_query($this->con, $sql);
    }

    // 3. Cập nhật (BỎ MD5)
    public function Update($id, $pass, $hoten, $role){
        if(!empty($pass)){
            // Lưu trực tiếp pass thô
            $sql = "UPDATE taikhoan SET password='$pass', hoten='$hoten', role=$role WHERE id=$id";
        } else {
            $sql = "UPDATE taikhoan SET hoten='$hoten', role=$role WHERE id=$id";
        }
        return mysqli_query($this->con, $sql);
    }

    public function Delete($id){
        if($id == 1) return false;
        $sql = "DELETE FROM taikhoan WHERE id = $id";
        return mysqli_query($this->con, $sql);
    }
}
?>