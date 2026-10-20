<?php
// Chạy 1 lần: http://localhost/bookstore/setup.php để tạo tài khoản admin
require 'config.php';
$exists = $pdo->query("SELECT COUNT(*) FROM users WHERE role='admin'")->fetchColumn();
if (!$exists) {
    $st = $pdo->prepare("INSERT INTO users (full_name,email,password,role) VALUES (?,?,?,'admin')");
    $st->execute(['Quản trị viên', 'admin@bookstore.vn', password_hash('admin123', PASSWORD_DEFAULT)]);
    echo 'Đã tạo admin: admin@bookstore.vn / admin123';
} else { echo 'Admin đã tồn tại.'; }
