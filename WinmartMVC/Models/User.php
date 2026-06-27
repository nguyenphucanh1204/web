<?php
class User extends DB {
    public function CheckLogin($user, $pass){
        // Lưu ý: Thực tế nên mã hóa password (MD5/Bcrypt), ở đây làm demo nên để nguyên
        $sql = "SELECT * FROM taikhoan WHERE username = '$user' AND password = '$pass'";
        $result = mysqli_query($this->con, $sql);
        
        // Nếu tìm thấy 1 dòng kết quả thì trả về mảng dữ liệu
        if(mysqli_num_rows($result) > 0){
            return mysqli_fetch_assoc($result);
        }
        return false;
    }
}
?>