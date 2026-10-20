<?php
require 'config.php';
$_SESSION['cart'] = $_SESSION['cart'] ?? [];
// Dự phòng khi trình duyệt tắt JavaScript
if (isset($_GET['add'])) { $id = (int)$_GET['add']; $_SESSION['cart'][$id] = ($_SESSION['cart'][$id] ?? 0) + 1; header('Location: cart.php'); exit; }
if (isset($_GET['remove'])) { unset($_SESSION['cart'][(int)$_GET['remove']]); header('Location: cart.php'); exit; }
$pageTitle = 'Giỏ hàng';
require 'includes/header.php';
$items = []; $total = 0;
if ($_SESSION['cart']) {
    $ids = array_keys($_SESSION['cart']);
    $in = implode(',', array_fill(0, count($ids), '?'));
    $st = $pdo->prepare("SELECT * FROM books WHERE id IN ($in)"); $st->execute($ids);
    foreach ($st->fetchAll() as $b) {
        $b['qty'] = $_SESSION['cart'][$b['id']]; $b['sub'] = $b['qty'] * $b['price'];
        $total += $b['sub']; $items[] = $b;
    }
}
?>
<h1>Giỏ hàng</h1>
<div id="cartPage">
<?php if (!$items): ?>
  <div class="empty">Giỏ hàng đang trống. <a href="index.php">Chọn sách ngay</a></div>
<?php else: ?>
<table>
  <tr><th>Sách</th><th>Đơn giá</th><th>Số lượng</th><th>Thành tiền</th><th></th></tr>
  <?php foreach ($items as $b): ?>
  <tr data-id="<?= $b['id'] ?>">
    <td><strong><?= e($b['title']) ?></strong><br><span class="muted"><?= e($b['author']) ?></span></td>
    <td><?= money($b['price']) ?></td>
    <td><div class="stepper">
      <button type="button" data-q="-1" data-id="<?= $b['id'] ?>" aria-label="Giảm">−</button>
      <span class="qty"><?= $b['qty'] ?></span>
      <button type="button" data-q="1" data-id="<?= $b['id'] ?>" aria-label="Tăng">+</button></div></td>
    <td class="sub"><?= money($b['sub']) ?></td>
    <td><a href="cart.php?remove=<?= $b['id'] ?>" data-rm="<?= $b['id'] ?>">Xóa</a></td>
  </tr>
  <?php endforeach; ?>
</table>
<div class="total"><span>Tổng cộng:</span><strong id="cartTotal"><?= money($total) ?></strong></div>
<p style="text-align:right"><a class="btn alt" href="index.php">Tiếp tục mua sắm</a> <a class="btn" href="checkout.php">Đặt hàng</a></p>
<?php endif; ?>
</div>
<?php require 'includes/footer.php'; ?>
