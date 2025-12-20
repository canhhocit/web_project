<?php
require_once 'Model/hd_tk/ThongKeModel.php';

class ThongKeController {
    private $model;

    public function __construct($db) {
        $this->model = new ThongKeModel($db);
    }

    public function index() {
    $data = [
        'doanhThuTheoChuXe' => $this->model->getDoanhThuTheoChuXe(),
        'doanhThuThang' => $this->model->getDoanhThuThang(),
        'tyLeThueXe' => $this->model->getTyLeThueXe(),
        'tongDoanhThu' => $this->model->getTongDoanhThu(),
        'tyLeLoaiXe' => $this->model->getTyLeLoaiXe(),
        'doanhThuCacThang' => $this->model->getDoanhThuCacThang(),
        'statsPhu' => $this->model->getStatsPhu()
    ];
    extract($data);
    require 'View/thongke/indexTK.php';
}
}
?>