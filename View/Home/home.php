<div class="row">
    <link rel="stylesheet" href="./View/CSS/style.css">
<form action="index.php" method="GET" class="mb-4">
    <input type="hidden" name="controller" value="home">
    <div class="input-group">
        <input type="text" name="keyword" class="form-control" placeholder="Tìm tên xe bạn thích...">
        <button type="submit" class="btn btn-primary">Tìm kiếm</button>
    </div>
</form>
    <?php
        if(empty($listCar)){
            echo '<div class="alert alert-warning w-100 text-center p-5">';
            echo '<h4>Chưa có xe nào được đăng</h4>';
            echo '<p>Đăng xe của bạn</p>';
            echo '</div>';
        }else{
            foreach($listCar as $car){
                $hinhAnh = !empty($car['hinh_anh']) ? 'View/image/' . $car['hinh_anh'] : 'https://via.placeholder.com/300x200?text=No+Image';                $giaThue = number_format($car['giathue'],0,',','.');
                $hangXe = isset($car['tenhang']) ? $car['tenhang'] : 'Chưa rõ hãng';
                $loaiXe = isset($car['loaixe']) ? $car['loaixe'] : 'Chưa rõ loại';

        echo '
            <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                <div class="card h-100 shadow-sm border-0 hover-card">
                    <div class="position-relative" style="height: 200px; overflow: hidden;">
                        <img src="' . $hinhAnh . '" class="card-img-top w-100 h-100" style="object-fit: cover; transition: transform 0.3s;" alt="' . $car['tenxe'] . '">
                        <span class="position-absolute top-0 end-0 badge bg-danger m-2">Hot</span>
                    </div>
                    
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title text-truncate fw-bold text-primary" title="' . $car['tenxe'] . '">
                            ' . $car['tenxe'] . '
                           </h5>
                        
                        <div class="mb-3">
                            <small class="text-muted"><i class="fa-solid fa-tag"></i> ' . $hangXe . '</small>
                            <small class="text-muted ms-2"><i class="fa-solid fa-car"></i> ' . $loaiXe . '</small>
                        </div>

                        <h5 class="card-text text-danger fw-bold mb-3">
                            ' . $giaThue . ' đ <small class="text-dark fs-6 fw-normal">/ngày</small>
                        </h5>
                        
                        <a href="index.php?controller=car&action=detail&id=' . $car['idxe'] . '" class="btn btn-outline-primary mt-auto fw-bold">
                            Xem Chi Tiết <i class="fa-solid fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
            ';
        }
    }
    ?>
</div>