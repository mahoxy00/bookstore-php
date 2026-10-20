CREATE DATABASE IF NOT EXISTS bookstore CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE bookstore;

CREATE TABLE categories (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL
);

CREATE TABLE books (
  id INT AUTO_INCREMENT PRIMARY KEY,
  category_id INT NOT NULL,
  title VARCHAR(200) NOT NULL,
  author VARCHAR(120) NOT NULL,
  price INT NOT NULL,
  stock INT NOT NULL DEFAULT 0,
  image VARCHAR(255) DEFAULT '',
  description TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (category_id) REFERENCES categories(id)
);

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  full_name VARCHAR(120) NOT NULL,
  email VARCHAR(150) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  role ENUM('customer','admin') NOT NULL DEFAULT 'customer',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE orders (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NULL,
  customer_name VARCHAR(120) NOT NULL,
  phone VARCHAR(20) NOT NULL,
  address VARCHAR(255) NOT NULL,
  total INT NOT NULL,
  status ENUM('Chờ xử lý','Đang giao','Hoàn thành','Đã hủy') NOT NULL DEFAULT 'Chờ xử lý',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

CREATE TABLE order_items (
  id INT AUTO_INCREMENT PRIMARY KEY,
  order_id INT NOT NULL,
  book_id INT NOT NULL,
  quantity INT NOT NULL,
  price INT NOT NULL,
  FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
  FOREIGN KEY (book_id) REFERENCES books(id)
);

CREATE TABLE contacts (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL,
  email VARCHAR(150) NOT NULL,
  message TEXT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO categories (name) VALUES ('Văn học'),('Kinh tế'),('Kỹ năng sống'),('Công nghệ'),('Thiếu nhi');

INSERT INTO books (category_id,title,author,price,stock,description) VALUES
(1,'Nhà giả kim','Paulo Coelho',79000,50,'Hành trình đi tìm kho báu và ý nghĩa cuộc sống của chàng chăn cừu Santiago.'),
(1,'Dế Mèn phiêu lưu ký','Tô Hoài',55000,40,'Tác phẩm kinh điển về chú dế Mèn trên đường phiêu lưu.'),
(2,'Cha giàu cha nghèo','Robert Kiyosaki',95000,30,'Bài học về tiền bạc và tư duy tài chính.'),
(2,'Nghĩ giàu làm giàu','Napoleon Hill',89000,25,'13 nguyên tắc để đạt được thành công.'),
(3,'Đắc nhân tâm','Dale Carnegie',86000,60,'Nghệ thuật đối nhân xử thế và giao tiếp.'),
(3,'Tuổi trẻ đáng giá bao nhiêu','Rosie Nguyễn',80000,35,'Cuốn sách truyền cảm hứng cho người trẻ.'),
(4,'Lập trình PHP căn bản','Nhiều tác giả',120000,20,'Giáo trình PHP & MySQL cho người mới bắt đầu.'),
(4,'Clean Code','Robert C. Martin',250000,15,'Cẩm nang viết mã sạch cho lập trình viên.'),
(5,'Truyện cổ Andersen','H. C. Andersen',65000,45,'Tuyển tập truyện cổ tích nổi tiếng.'),
(5,'Hoàng tử bé','Antoine de Saint-Exupéry',60000,50,'Câu chuyện triết lý nhẹ nhàng dành cho mọi lứa tuổi.');
