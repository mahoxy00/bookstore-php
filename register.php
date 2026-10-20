<?php
require 'config.php';
$err = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $n = trim($_POST['full_name']); $m = trim($_POST['email']); $p = $_POST['password'];
    if ($n === '' || !filter_var($m, FILTER_VALIDATE_EMAIL) || strlen($p) < 6) {
        $err = 'Nhập họ tên, email hợp lệ và mật khẩu từ 6 ký tự.';
    } else {
        $chk = $pdo->prepare("SELECT id FROM users WHERE email=?"); $chk->execute([$m]);
        if ($chk->fetch()) { $err = 'Email này đã được đăng ký.'; }
        else {
            $pdo->prepare("INSERT INTO users (full_name,email,password) VALUES (?,?,?)")
                ->execute([$n, $m, password_hash($p, PASSWORD_DEFAULT)]);
            header('Location: login.php'); exit;
        }
    }
}
$pageTitle = 'Đăng ký';
require 'includes/header.php';
?>
<h1>Đăng ký tài khoản</h1>
<?php if ($err): ?><div class="msg err"><?= e($err) ?></div><?php endif; ?>
<form class="panel" method="post">
  <label>Họ và tên</label><input name="full_name">
  <label>Email</label><input name="email" type="email">
  <label>Mật khẩu</label><input name="password" type="password">
  <p><button class="btn">Đăng ký</button></p>
</form>
<?php require 'includes/footer.php'; ?>
