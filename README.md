# Website bán sách – Hiệu sách Trang Vàng

Công nghệ: PHP thuần (PDO) + MySQL + HTML/CSS. Chạy trên XAMPP.

## Cài đặt
1. Cài XAMPP, bật Apache và MySQL.
2. Chép thư mục `bookstore` vào `C:\xampp\htdocs\`.
3. Mở http://localhost/phpmyadmin, tab Import, chọn file `database.sql`.
4. Mở http://localhost/bookstore/setup.php một lần để tạo tài khoản admin.
5. Truy cập http://localhost/bookstore/

## Tài khoản quản trị
- Email: admin@bookstore.vn
- Mật khẩu: admin123

Nếu MySQL của bạn có mật khẩu root, sửa trong `config.php`.

## Chức năng
- Khách: xem sách, tìm kiếm, lọc thể loại, giỏ hàng, đặt hàng, đăng ký, đăng nhập, liên hệ.
- Quản trị: thêm/sửa/xóa sách (có upload ảnh), thể loại; xem/cập nhật/xóa đơn hàng; xem/xóa liên hệ.
