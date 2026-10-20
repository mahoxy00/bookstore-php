<?php
session_start();
$pdo = new PDO('mysql:host=localhost;dbname=bookstore;charset=utf8mb4', 'root', '', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
]);

function e($s) { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
function money($n) { return number_format($n, 0, ',', '.') . ' ₫'; }
function is_admin() { return isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin'; }
function require_admin() { if (!is_admin()) { header('Location: /bookstore/login.php'); exit; } }
function cart_count() { return array_sum($_SESSION['cart'] ?? []); }

function cover_class($id) { return 'c' . ($id % 6); }
function flash_show() {
    foreach (['flash' => 'ok', 'flash_err' => 'err'] as $k => $cls) {
        if (!empty($_SESSION[$k])) { echo '<div class="msg ' . $cls . '">' . e($_SESSION[$k]) . '</div>'; unset($_SESSION[$k]); }
    }
}
