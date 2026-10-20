<?php
require 'config.php';
header('Content-Type: application/json; charset=utf-8');
$q = trim($_GET['q'] ?? '');
$out = [];
if (mb_strlen($q) >= 2) {
    $st = $pdo->prepare("SELECT id,title,author,price,image FROM books WHERE title LIKE ? OR author LIKE ? ORDER BY title LIMIT 6");
    $st->execute(["%$q%", "%$q%"]);
    foreach ($st->fetchAll() as $b) $out[] = ['id'=>(int)$b['id'],'title'=>$b['title'],'author'=>$b['author'],'price'=>(int)$b['price'],'cls'=>cover_class($b['id']),'image'=>$b['image']];
}
echo json_encode($out, JSON_UNESCAPED_UNICODE);
