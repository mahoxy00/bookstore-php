<?php
$pageTitle = 'Chi tiết sách';
require 'includes/header.php';
$st = $pdo->prepare("SELECT b.*, c.name AS cat_name FROM books b JOIN categories c ON c.id=b.category_id WHERE b.id=?");
$st->execute([(int)($_GET['id'] ?? 0)]);
$b = $st->fetch();
if (!$b) { echo '<div class="empty">Không tìm thấy sách này. <a href="index.php">Về trang chủ</a></div>'; require 'includes/footer.php'; exit; }
?>
<div class="detail">
  <div class="cover <?= cover_class($b['id']) ?>">
    <?php if ($b['image']): ?><img src="uploads/<?= e($b['image']) ?>" alt="<?= e($b['title']) ?>">
    <?php else: ?><span class="t"><?= e($b['title']) ?></span><span class="a"><?= e($b['author']) ?></span><?php endif; ?>
  </div>
  <div>
    <span class="tag"><?= e($b['cat_name']) ?></span>
    <h1 style="margin-top:10px"><?= e($b['title']) ?></h1>
    <p class="muted">Tác giả: <?= e($b['author']) ?></p>
    <p class="price"><?= money($b['price']) ?></p>
    <p><?= nl2br(e($b['description'])) ?></p>
    <p><?php if ($b['stock'] > 0): ?><span class="tag">Còn <?= (int)$b['stock'] ?> cuốn</span><?php else: ?><span class="tag out">Hết hàng</span><?php endif; ?></p>
    <p>
      <?php if ($b['stock'] > 0): ?><a class="btn" href="cart.php?add=<?= $b['id'] ?>" data-add="<?= $b['id'] ?>">Thêm vào giỏ</a><?php endif; ?>
      <a class="btn alt" href="index.php">Quay lại</a>
    </p>
  </div>
</div>
<?php require 'includes/footer.php'; ?>
