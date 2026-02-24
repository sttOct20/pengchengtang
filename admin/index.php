<?php
session_start();
require_once 'config.php';
if (!isset($_SESSION['admin'])) { header('Location: login.php'); exit; }

$articles = loadArticles();
$cats = array('全部','炁针疗法','辨证施治','疑难杂症','健康养生','中药本草');
$filter = isset($_GET['cat']) ? $_GET['cat'] : '全部';

if ($filter === '全部') {
    $filtered = $articles;
} else {
    $filtered = array();
    foreach ($articles as $a) {
        if (isset($a['category']) && $a['category'] === $filter) {
            $filtered[] = $a;
        }
    }
}
$filtered = array_reverse(array_values($filtered));
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>文章管理 — 彭城堂中医</title>
<style>
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:'PingFang SC',sans-serif;background:#f7f7f7;color:#333}
.topbar{background:#111;padding:0 24px;height:56px;display:flex;align-items:center;justify-content:space-between}
.topbar h1{color:#fff;font-size:1rem;font-weight:600}
.topbar a{color:#999;font-size:.85rem;text-decoration:none}
.topbar a:hover{color:#fff}
.main{max-width:1000px;margin:32px auto;padding:0 20px}
.toolbar{display:flex;align-items:center;gap:12px;margin-bottom:24px;flex-wrap:wrap}
.btn-new{background:#C41E3A;color:#fff;padding:10px 20px;border-radius:6px;text-decoration:none;font-size:.9rem}
.btn-new:hover{background:#a01830}
.cats{display:flex;gap:8px;flex-wrap:wrap}
.cat{padding:6px 14px;border-radius:20px;border:1px solid #ddd;background:#fff;font-size:.85rem;text-decoration:none;color:#555}
.cat.active,.cat:hover{background:#C41E3A;color:#fff;border-color:#C41E3A}
.card{background:#fff;border-radius:8px;padding:20px 24px;margin-bottom:12px;display:flex;align-items:center;gap:16px;box-shadow:0 1px 4px rgba(0,0,0,.06)}
.thumb{width:80px;height:60px;object-fit:cover;border-radius:4px;background:#eee;flex-shrink:0}
.thumb-ph{width:80px;height:60px;border-radius:4px;background:#f0e8e8;display:flex;align-items:center;justify-content:center;color:#C41E3A;font-size:1.5rem;flex-shrink:0}
.info{flex:1;min-width:0}
.info h3{font-size:1rem;margin-bottom:6px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.meta{font-size:.8rem;color:#999;display:flex;gap:12px}
.tag{background:#fef2f2;color:#C41E3A;padding:2px 8px;border-radius:10px;font-size:.75rem}
.actions{display:flex;gap:8px;flex-shrink:0}
.btn-edit{padding:7px 14px;border-radius:5px;border:1px solid #ddd;background:#fff;color:#333;text-decoration:none;font-size:.85rem}
.btn-edit:hover{background:#f5f5f5}
.btn-del{padding:7px 14px;border-radius:5px;border:1px solid #fca5a5;background:#fff;color:#C41E3A;font-size:.85rem;cursor:pointer}
.btn-del:hover{background:#fef2f2}
.empty{text-align:center;padding:60px;color:#bbb}
.count{color:#999;font-size:.85rem;margin-left:auto}
</style>
</head>
<body>
<div class="topbar">
  <h1>彭城堂中医 · 内容管理</h1>
  <a href="login.php?logout=1">退出登录</a>
</div>
<div class="main">
  <div class="toolbar">
    <a href="edit.php" class="btn-new">+ 新建文章</a>
    <div class="cats">
      <?php foreach ($cats as $c): ?>
      <a href="?cat=<?php echo urlencode($c); ?>" class="cat <?php echo $filter===$c?'active':''; ?>"><?php echo $c; ?></a>
      <?php endforeach; ?>
    </div>
    <span class="count">共 <?php echo count($filtered); ?> 篇</span>
  </div>

  <?php if (empty($filtered)): ?>
  <div class="empty">暂无文章，点击「新建文章」开始创作</div>
  <?php else: ?>
  <?php foreach ($filtered as $a): ?>
  <div class="card">
    <?php if (!empty($a['thumbnail'])): ?>
    <img class="thumb" src="<?php echo htmlspecialchars($a['thumbnail']); ?>" alt="">
    <?php else: ?>
    <div class="thumb-ph">炁</div>
    <?php endif; ?>
    <div class="info">
      <h3><?php echo htmlspecialchars($a['title']); ?></h3>
      <div class="meta">
        <span class="tag"><?php echo htmlspecialchars(isset($a['category']) ? $a['category'] : '未分类'); ?></span>
        <span><?php echo htmlspecialchars(isset($a['publishedDate']) ? $a['publishedDate'] : ''); ?></span>
      </div>
    </div>
    <div class="actions">
      <a href="edit.php?id=<?php echo $a['id']; ?>" class="btn-edit">编辑</a>
      <form method="post" action="delete.php" onsubmit="return confirm('确定删除？')">
        <input type="hidden" name="id" value="<?php echo $a['id']; ?>">
        <button type="submit" class="btn-del">删除</button>
      </form>
    </div>
  </div>
  <?php endforeach; ?>
  <?php endif; ?>
</div>
</body>
</html>
