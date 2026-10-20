<?php
require 'config.php';
header('Content-Type: application/json; charset=utf-8');
$_SESSION['cart'] = $_SESSION['cart'] ?? [];
$a = $_GET['a'] ?? 'list';
$id = (int)($_GET['id'] ?? 0);
$msg = '';
$title = '';

if (in_array($a, ['add', 'inc']) && $id) {
    $st = $pdo->prepare("SELECT title, stock FROM books WHERE id=?"); $st->execute([$id]);
    $b = $st->fetch();
    if ($b) {
        $title = $b['title'];
        $d = $a === 'add' ? 1 : (int)($_GET['d'] ?? 1);
        $new = ($_SESSION['cart'][$id] ?? 0) + $d;
        if ($new > $b['stock']) { $new = $b['stock']; $msg = 'Kho chỉ còn ' . $b['stock'] . ' cuốn.'; }
        if ($new <= 0) unset($_SESSION['cart'][$id]); else $_SESSION['cart'][$id] = $new;
    }
}
if ($a === 'remove' && $id) unset($_SESSION['cart'][$id]);

$items = []; $total = 0;
if ($_SESSION['cart']) {
    $ids = array_keys($_SESSION['cart']);
    $in = implode(',', array_fill(0, count($ids), '?'));
    $st = $pdo->prepare("SELECT id,title,author,price,image FROM books WHERE id IN ($in)"); $st->execute($ids);
    $found = [];
    foreach ($st->fetchAll() as $b) {
        $q = $_SESSION['cart'][$b['id']];
        $found[] = $b['id'];
        $total += $b['price'] * $q;
        $items[] = ['id'=>(int)$b['id'],'title'=>$b['title'],'author'=>$b['author'],'price'=>(int)$b['price'],
                    'qty'=>$q,'sub'=>$b['price']*$q,'cls'=>cover_class($b['id']),'image'=>$b['image']];
    }
    foreach ($ids as $i) if (!in_array($i, $found)) unset($_SESSION['cart'][$i]);
}
echo json_encode(['ok'=>true,'msg'=>$msg,'title'=>$title,'count'=>array_sum($_SESSION['cart']),'total'=>$total,'items'=>$items], JSON_UNESCAPED_UNICODE);
