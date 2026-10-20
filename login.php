<?php
require 'config.php';
$err = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $st = $pdo->prepare("SELECT * FROM users WHERE email=?"); $st->execute([trim($_POST['email'])]);
    $u = $st->fetch();
    if ($u && password_verify($_POST['password'], $u['password'])) {
        $_SESSION['user'] = ['id' => $u['id'], 'full_name' => $u['full_name'], 'role' => $u['role']];
        header('Location: ' . ($u['role'] === 'admin' ? 'admin/index.php' : 'index.php')); exit;
    }
    $err = 'Email hoặc mật khẩu không đúng.';
}
$pageTitle = 'Đăng nhập';
require 'includes/header.php';
?>
<h1>Đăng nhập</h1>
<?php if ($err): ?><div class="msg err"><?= e($err) ?></div><?php endif; ?>
<form class="panel" method="post">
  <label>Email</label><input name="email" type="email">
  <label>Mật khẩu</label><input name="password" type="password">
  <p><button class="btn">Đăng nhập</button></p>
</form>
<?php require 'includes/footer.php'; ?>
