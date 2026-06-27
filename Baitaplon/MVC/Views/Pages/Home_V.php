
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
    .dashboard-cards {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
        margin-bottom: 30px;
    }
    .card-box {
        background: #fff;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .card-info h3 {
        font-size: 24px;
        font-weight: bold;
        margin: 5px 0;
        color: #333;
    }
    .card-info p {
        color: #777;
        font-size: 14px;
        margin: 0;
    }
    .card-icon {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        color: #fff;
    }
    /* Màu sắc đẹp mắt */
    .bg-1 { background: linear-gradient(45deg, #FF5370, #ff869a); }
    .bg-2 { background: linear-gradient(45deg, #4099ff, #73b4ff); }
    .bg-3 { background: linear-gradient(45deg, #2ed8b6, #59e0c5); }
    .bg-4 { background: linear-gradient(45deg, #FFB64D, #ffcb80); }

    /* CSS MỚI CHO BIỂU ĐỒ & BẢNG */
    .row-dashboard { display: flex; gap: 20px; margin-top: 20px; }
    
    .card-chart {
        flex: 2; /* Chiếm 2 phần */
        background: #fff; padding: 20px; border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    
    .card-list {
        flex: 1.5; /* Chiếm 1.5 phần */
        background: #fff; padding: 20px; border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    h4.card-title { margin-top: 0; color: #444; border-bottom: 1px solid #eee; padding-bottom: 10px; margin-bottom: 15px; font-weight: bold; font-size: 16px; }

    /* Bảng đơn hàng nhỏ */
    .table-sm { width: 100%; border-collapse: collapse; font-size: 13px; }
    .table-sm th { text-align: left; color: #666; padding: 8px; border-bottom: 1px solid #eee; }
    .table-sm td { padding: 8px; border-bottom: 1px solid #f5f5f5; color: #333; }
    .badge-success { background: #e8f5e9; color: #2e7d32; padding: 3px 8px; border-radius: 4px; font-weight: bold; font-size: 11px; }
</style>

<div class="dashboard-cards">
    <div class="card-box">
        <div class="card-info">
            <h3><?php echo number_format($data['doanhthu'] ?? 0); ?> đ</h3>
            <p>Doanh Thu</p>
        </div>
        <div class="card-icon bg-1"><i class="fas fa-money-bill-wave"></i></div>
    </div>
    <div class="card-box">
        <div class="card-info">
            <h3><?php echo $data['donhang']; ?></h3>
            <p>Đơn Hàng</p>
        </div>
        <div class="card-icon bg-2"><i class="fas fa-shopping-cart"></i></div>
    </div>
    <div class="card-box">
        <div class="card-info">
            <h3><?php echo $data['sanpham']; ?></h3>
            <p>Sản Phẩm</p>
        </div>
        <div class="card-icon bg-3"><i class="fas fa-cubes"></i></div>
    </div>
    <div class="card-box">
        <div class="card-info">
            <h3><?php echo $data['khachhang']; ?></h3>
            <p>Khách Hàng</p>
        </div>
        <div class="card-icon bg-4"><i class="fas fa-users"></i></div>
    </div>
</div>

<div class="row-dashboard">
    
    <div class="card-chart">
        <h4 class="card-title"><i class="fas fa-chart-bar"></i> Biểu đồ doanh thu 7 ngày qua</h4>
        <canvas id="revenueChart" style="width:100%; height:300px;"></canvas>
    </div>

    <div class="card-list">
        <h4 class="card-title"><i class="fas fa-receipt"></i> Đơn hàng mới nhất</h4>
        <table class="table-sm">
            <thead>
                <tr>
                    <th>Mã ĐH</th>
                    <th>Khách hàng</th>
                    <th>Tổng tiền</th>
                    <th>Trạng thái</th>
                </tr>
            </thead>
            <tbody>
                <?php if(isset($data['newOrders'])) {
                    while($row = mysqli_fetch_array($data['newOrders'])){
                ?>
                <tr>
                    <td style="font-weight:bold; color:#1a237e;">#<?php echo $row['MaHD']; ?></td>
                    <td><?php echo $row['TenKH']; ?></td>
                    <td style="font-weight:bold;"><?php echo number_format($row['TongTien']); ?></td>
                    <td><span class="badge-success">Hoàn thành</span></td>
                </tr>
                <?php 
                    }
                } 
                ?>
            </tbody>
        </table>
        <div style="text-align:center; margin-top:15px;">
            <a href="http://localhost/Baitaplon/Donhang" style="font-size:13px; text-decoration:none; color:#4099ff;">Xem tất cả đơn hàng &rarr;</a>
        </div>
    </div>
</div>

<script>
    // Lấy dữ liệu từ PHP
    var labels = <?php echo $data['chartLabels']; ?>; // Mảng ngày (trục ngang)
    var values = <?php echo $data['chartValues']; ?>; // Mảng tiền (trục dọc)

    var ctx = document.getElementById('revenueChart').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'bar', // Loại biểu đồ: bar (cột), line (đường)
        data: {
            labels: labels,
            datasets: [{
                label: 'Doanh thu (VNĐ)',
                data: values,
                backgroundColor: 'rgba(54, 162, 235, 0.6)', // Màu cột xanh dương nhạt
                borderColor: 'rgba(54, 162, 235, 1)',       // Viền cột đậm
                borderWidth: 1,
                borderRadius: 5
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            },
            plugins: {
                legend: { display: false } // Ẩn chú thích cho gọn
            }
        }
    });
</script>