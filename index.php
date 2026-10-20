<?php
$pageTitle = 'Trang chủ';
require 'includes/header.php';
$q = trim($_GET['q'] ?? '');
$cat = (int)($_GET['cat'] ?? 0);
$sql = "SELECT b.*, c.name AS cat_name FROM books b JOIN categories c ON c.id=b.category_id WHERE 1=1";
$params = [];
if ($q !== '') { $sql .= " AND (b.title LIKE ? OR b.author LIKE ?)"; $params[] = "%$q%"; $params[] = "%$q%"; }
if ($cat) { $sql .= " AND b.category_id=?"; $params[] = $cat; }
$sort = $_GET['sort'] ?? 'new';
$orders = ['new'=>'b.id DESC','price_asc'=>'b.price ASC','price_desc'=>'b.price DESC','name'=>'b.title ASC'];
$sql .= " ORDER BY " . ($orders[$sort] ?? $orders['new']);
$st = $pdo->prepare($sql); $st->execute($params);
$books = $st->fetchAll();
$cats = $pdo->query("SELECT * FROM categories ORDER BY name")->fetchAll();
$hero = $pdo->query("SELECT id,title,author FROM books ORDER BY id LIMIT 4")->fetchAll();
$filtering = ($q !== '' || $cat);
?>
<?php if (!$filtering): ?>
<section class="slider" aria-roledescription="carousel">
  <div class="track">
    <div class="slide s1">
      <div class="txt">
        <span class="kicker">Sách mới mỗi tuần</span>
        <h1>Tìm cuốn sách tiếp theo của bạn</h1>
        <p>Văn học, kinh tế, kỹ năng, công nghệ và sách thiếu nhi. Đặt hôm nay, nhận trong ngày tại nội thành Hà Nội.</p>
        <a class="btn" href="#sach">Khám phá ngay</a>
      </div>
      <div class="stack" aria-hidden="true">
        <?php foreach ($hero as $h): ?>
          <div class="cover <?= cover_class($h['id']) ?>"><span class="t"><?= e($h['title']) ?></span><span class="a"><?= e($h['author']) ?></span></div>
        <?php endforeach; ?>
      </div>
    </div>
    <div class="slide s2">
      <div class="txt">
        <span class="kicker">Ưu đãi</span>
        <h1>Miễn phí giao hàng cho đơn từ 300.000 ₫</h1>
        <p>Gom đủ vài cuốn yêu thích và nhận hàng tận nơi, không tốn phí vận chuyển.</p>
        <a class="btn" href="index.php?cat=2#sach">Xem sách Kinh tế</a>
      </div>
    </div>
    <div class="slide s3">
      <div class="txt">
        <span class="kicker">Dành cho lập trình viên</span>
        <h1>Học lập trình từ những cuốn sách chọn lọc</h1>
        <p>PHP, Clean Code và nhiều đầu sách công nghệ để bạn nâng cấp kỹ năng.</p>
        <a class="btn" href="index.php?cat=4#sach">Xem sách Công nghệ</a>
      </div>
    </div>
  </div>
  <button class="nav prev" type="button" aria-label="Trước">‹</button>
  <button class="nav next" type="button" aria-label="Sau">›</button>
  <div class="dots"></div>
</section>
<?php endif; ?>

<div class="chips">
  <a class="chip <?= !$cat ? 'on' : '' ?>" href="index.php">Tất cả</a>
  <?php foreach ($cats as $c): ?>
    <a class="chip <?= $cat == $c['id'] ? 'on' : '' ?>" href="index.php?cat=<?= $c['id'] ?>"><?= e($c['name']) ?></a>
  <?php endforeach; ?>
</div>

<div class="sec-head" id="sach">
  <h2><?= $q !== '' ? 'Kết quả cho “' . e($q) . '”' : 'Sách mới lên kệ' ?> <small class="muted">· <?= count($books) ?> cuốn</small></h2>
  <form method="get" class="sortbox">
    <input type="hidden" name="q" value="<?= e($q) ?>"><input type="hidden" name="cat" value="<?= $cat ?>">
    <label for="sort" class="muted">Sắp xếp</label>
    <select id="sort" name="sort" onchange="this.form.submit()">
      <option value="new" <?= $sort==='new'?'selected':'' ?>>Mới nhất</option>
      <option value="price_asc" <?= $sort==='price_asc'?'selected':'' ?>>Giá thấp đến cao</option>
      <option value="price_desc" <?= $sort==='price_desc'?'selected':'' ?>>Giá cao đến thấp</option>
      <option value="name" <?= $sort==='name'?'selected':'' ?>>Tên A–Z</option>
    </select>
  </form>
</div>
<?php if (!$books): ?><div class="empty">Không tìm thấy sách phù hợp. Hãy thử từ khóa khác hoặc chọn thể loại khác.</div><?php endif; ?>
<div class="grid">
<?php foreach ($books as $b): ?>
  <article class="book reveal">
    <a class="cover <?= cover_class($b['id']) ?>" href="product.php?id=<?= $b['id'] ?>">
      <?php if ($b['image']): ?><img src="uploads/<?= e($b['image']) ?>" alt="<?= e($b['title']) ?>">
      <?php else: ?><span class="t"><?= e($b['title']) ?></span><span class="a"><?= e($b['author']) ?></span><?php endif; ?>
    </a>
    <div class="body">
      <h3><a href="product.php?id=<?= $b['id'] ?>"><?= e($b['title']) ?></a></h3>
      <span class="muted"><?= e($b['author']) ?> · <?= e($b['cat_name']) ?></span>
      <span class="price"><?= money($b['price']) ?></span>
      <?php if ($b['stock'] > 0): ?><a class="btn" href="cart.php?add=<?= $b['id'] ?>" data-add="<?= $b['id'] ?>">Thêm vào giỏ</a>
      <?php else: ?><span class="btn alt" style="text-align:center;cursor:default">Hết hàng</span><?php endif; ?>
    </div>
  </article>
<?php endforeach; ?>
</div>
<?php require 'includes/footer.php'; ?>
