<?php
$pageTitle = 'Liên hệ';
require 'includes/header.php';
$msg = ''; $err = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $n = trim($_POST['name']); $m = trim($_POST['email']); $t = trim($_POST['message']);
    if ($n === '' || !filter_var($m, FILTER_VALIDATE_EMAIL) || $t === '') {
        $err = 'Vui lòng nhập họ tên, email hợp lệ và nội dung.';
    } else {
        $pdo->prepare("INSERT INTO contacts (name,email,message) VALUES (?,?,?)")->execute([$n, $m, $t]);
        $msg = 'Cảm ơn bạn. Chúng tôi sẽ phản hồi sớm.';
    }
}
?>
<h1>Liên hệ</h1>
<p>Hiệu sách Trang Vàng · Cầu Diễn, Hà Nội · Hotline: 0900 000 000</p>
<?php if ($msg): ?><div class="msg ok"><?= e($msg) ?></div><?php endif; ?>
<?php if ($err): ?><div class="msg err"><?= e($err) ?></div><?php endif; ?>
<form class="panel" method="post">
  <label>Họ và tên</label><input name="name">
  <label>Email</label><input name="email" type="email">
  <label>Nội dung</label><textarea name="message" rows="5"></textarea>
  <p><button class="btn">Gửi liên hệ</button></p>
</form>
<?php require 'includes/footer.php'; ?>
