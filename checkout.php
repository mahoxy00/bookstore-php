<?php
require 'config.php';
if (empty($_SESSION['cart'])) { header('Location: cart.php'); exit; }
$err = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']); $phone = trim($_POST['phone']); $addr = trim($_POST['address']);
    if ($name === '' || $phone === '' || $addr === '') {
        $err = 'Vui lòng nhập đủ họ tên, số điện thoại và địa chỉ.';
    } else {
        $ids = array_keys($_SESSION['cart']);
        $in = implode(',', array_fill(0, count($ids), '?'));
        $st = $pdo->prepare("SELECT * FROM books WHERE id IN ($in)"); $st->execute($ids);
        $books = $st->fetchAll();
        $total = 0;
        foreach ($books as $b) $total += $b['price'] * $_SESSION['cart'][$b['id']];
        $pdo->beginTransaction();
        $pdo->prepare("INSERT INTO orders (user_id,customer_name,phone,address,total) VALUES (?,?,?,?,?)")
            ->execute([$_SESSION['user']['id'] ?? null, $name, $phone, $addr, $total]);
        $oid = $pdo->lastInsertId();
        $ins = $pdo->prepare("INSERT INTO order_items (order_id,book_id,quantity,price) VALUES (?,?,?,?)");
        $upd = $pdo->prepare("UPDATE books SET stock = GREATEST(stock-?,0) WHERE id=?");
        foreach ($books as $b) {
            $q = $_SESSION['cart'][$b['id']];
            $ins->execute([$oid, $b['id'], $q, $b['price']]);
            $upd->execute([$q, $b['id']]);
        }
        $pdo->commit();
        $_SESSION['cart'] = [];
        $_SESSION['flash'] = "Đặt hàng thành công. Mã đơn của bạn là #$oid.";
        header('Location: index.php'); exit;
    }
}
$pageTitle = 'Đặt hàng';
require 'includes/header.php';
?>
<h1>Thông tin giao hàng</h1>
<?php if ($err): ?><div class="msg err"><?= e($err) ?></div><?php endif; ?>
<form class="panel" method="post">
  <label>Họ và tên</label><input name="name" value="<?= e($_SESSION['user']['full_name'] ?? '') ?>">
  <label>Số điện thoại</label><input name="phone">
  <label>Địa chỉ nhận hàng</label><textarea name="address" rows="3"></textarea>
  <p><button class="btn">Xác nhận đặt hàng</button></p>
</form>
<?php require 'includes/footer.php'; ?>
