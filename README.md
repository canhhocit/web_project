website tương tác giữa người dùng với người dùng
\*\*\*PHÂN CÔNG NHIỆM VỤ:

1. PHẠM HỮU CẢNH

- Phần tài khoản
  - Đăng ký, đăng nhập, đăng xuất, đổi mật khẩu
  - Thêm/Cập nhật thông tin cá nhân
  - Xóa tài khoản
  - Upload ảnh đại diện
- Phần Đăng xe:
  - Kiểm tra đăng nhập + thông tin trước khi đăng
  - Thêm xe + upload nhiều ảnh

3. LÒ VĂN ĐIỆP

- Phần thanh toán & trả xe
   - Tính toán tiền thuê tự động: Khi ấn nút "Trả xe", hệ thống tự động tính toán dữ liệu dựa trên thời gian thực:Số ngày thuê dự kiến: Tính từ ngày mượn đến ngày trả dự kiến trong hợp đồng.Số ngày quá hạn: So sánh ngày trả dự kiến với ngày hiện tại.
   -  Tổng tiền: Được tính theo công thức: $TongTien = (NgayThueDuKien + NgayQuaHan) \times GiaThue$.
   -  Xác nhận thanh toán:Hỗ trợ lựa chọn phương thức thanh toán (Tiền mặt hoặc Chuyển khoản).
   -  Cập nhật trạng thái hóa đơn thành "Đã thanh toán" (trangthai = 1).Tự động lưu vết giao dịch vào bảng thanhtoan với mã giao dịch ngẫu nhiên để phục vụ đối soát.

- Phần thống kê
Thống kê doanh thu:

  - Tổng doanh thu: Tổng số tiền thu được từ tất cả các giao dịch hoàn tất.
  - Doanh thu tháng hiện tại: Tự động lọc các giao dịch phát sinh trong tháng và năm hiện tại.
  - Doanh thu theo chủ xe: Liệt kê số tiền từng chủ xe nhận được, giúp quản lý công nợ khách quan.

  - Số lượng xe đang thuê: Đếm số đơn hàng chưa hoàn tất thực tế trên hệ thống.
  - Tổng số khách hàng: Thống kê số lượng người dùng đăng ký vai trò là người thuê.
  - Tỷ lệ thuê xe: Tính toán phần trăm số lượng xe đang hoạt động trên tổng số xe hiện có.

  - Biểu đồ cột (Bar Chart): So sánh doanh thu giữa các tháng trong năm để thấy được xu hướng tăng trưởng.
  - Biểu đồ tròn (Doughnut Chart): Phân tích tỷ trọng các loại xe được ưa chuộng (Xe máy điện, Xe đạp điện...).