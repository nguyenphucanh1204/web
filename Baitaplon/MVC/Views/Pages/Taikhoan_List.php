<div class="card">
    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
        <h3>Danh sách Tài khoản</h3>
        <a href="http://localhost/Baitaplon/Taikhoan/Create" style="background: #28a745; color: white; padding: 8px 15px; text-decoration: none; border-radius: 4px;">
            <i class="fas fa-plus"></i> Thêm mới
        </a>
    </div>
    <div class="card-body">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #f8f9fa; border-bottom: 2px solid #dee2e6;">
                    <th style="padding: 10px; text-align: left;">ID</th>
                    <th style="padding: 10px; text-align: left;">Username</th>
                    <th style="padding: 10px; text-align: left;">Họ tên</th>
                    <th style="padding: 10px; text-align: left;">Quyền hạn</th>
                    <th style="padding: 10px; text-align: center;">Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php if(isset($data['dulieu'])) {
                    while($row = mysqli_fetch_array($data['dulieu'])){
                ?>
                <tr style="border-bottom: 1px solid #eee;">
                    <td style="padding: 10px;"><?php echo $row['id'] ?></td>
                    <td style="padding: 10px; font-weight: bold;"><?php echo $row['username'] ?></td>
                    <td style="padding: 10px;"><?php echo $row['hoten'] ?></td>
                    <td style="padding: 10px;">
                        <?php 
                            if($row['role'] == 1) 
                                echo "<span style='background:#007bff; color:white; padding:3px 8px; border-radius:3px; font-size:12px;'>Admin</span>";
                            else 
                                echo "<span style='background:#6c757d; color:white; padding:3px 8px; border-radius:3px; font-size:12px;'>Nhân viên</span>";
                        ?>
                    </td>
                    <td style="padding: 10px; text-align: center;">
                        <a href="http://localhost/Baitaplon/Taikhoan/Edit/<?php echo $row['id'] ?>" style="color: #ffc107; margin-right: 10px; font-size: 18px;"><i class="fas fa-edit"></i></a>
                        
                        <?php if($row['id'] != 1) { ?>
                        <a href="http://localhost/Baitaplon/Taikhoan/Delete/<?php echo $row['id'] ?>" onclick="return confirm('Bạn có chắc muốn xóa?')" style="color: #dc3545; font-size: 18px;"><i class="fas fa-trash-alt"></i></a>
                        <?php } ?>
                    </td>
                </tr>
                <?php }} ?>
            </tbody>
        </table>
    </div>
</div>