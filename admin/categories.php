<?php
session_start();
require_once 'config.php';
if (!isset($_SESSION['admin'])) { header('Location: login.php'); exit; }

$cats = loadCategories();
$error = '';
$success = '';

// Handle POST actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = isset($_POST['action']) ? $_POST['action'] : '';

    if ($action === 'add') {
        $name = trim(isset($_POST['name']) ? $_POST['name'] : '');
        if ($name === '') {
            $error = '分类名称不能为空';
        } elseif (in_array($name, $cats)) {
            $error = '该分类已存在';
        } else {
            $cats[] = $name;
            saveCategories($cats);
            $success = '分类「' . $name . '」已添加';
        }
    } elseif ($action === 'delete') {
        $idx = intval(isset($_POST['idx']) ? $_POST['idx'] : -1);
        if (isset($cats[$idx])) {
            $deleted = $cats[$idx];
            array_splice($cats, $idx, 1);
            saveCategories($cats);
            $success = '分类「' . $deleted . '」已删除';
        }
    } elseif ($action === 'rename') {
        $idx = intval(isset($_POST['idx']) ? $_POST['idx'] : -1);
        $newname = trim(isset($_POST['newname']) ? $_POST['newname'] : '');
        if (!isset($cats[$idx])) {
            $error = '分类不存在';
        } elseif ($newname === '') {
            $error = '新名称不能为空';
        } elseif (in_array($newname, $cats) && $cats[$idx] !== $newname) {
            $error = '该名称已存在';
        } else {
            $old = $cats[$idx];
            $cats[$idx] = $newname;
            saveCategories($cats);
            $success = '已将「' . $old . '」重命名为「' . $newname . '」';
        }
    }
    // Re-load after save
    $cats = loadCategories();
}
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>分类管理 — 彭城堂中医</title>
<style>
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:'PingFang SC',sans-serif;background:#f7f7f7;color:#333}
.topbar{background:#111;padding:0 24px;height:56px;display:flex;align-items:center;gap:16px}
.topbar h1{color:#fff;font-size:1rem;flex:1}
.topbar a{color:#999;font-size:.85rem;text-decoration:none}
.topbar a:hover{color:#fff}
.main{max-width:680px;margin:32px auto;padding:0 20px 60px}
.notice{padding:12px 16px;border-radius:6px;margin-bottom:20px;font-size:.9rem}
.notice.success{background:#ecfdf5;color:#15803d;border:1px solid #86efac}
.notice.error{background:#fef2f2;color:#b91c1c;border:1px solid #fca5a5}
.card{background:#fff;border-radius:8px;box-shadow:0 1px 4px rgba(0,0,0,.06);margin-bottom:24px}
.card-header{padding:18px 24px;border-bottom:1px solid #f0f0f0;font-weight:600;font-size:1rem}
.cat-list{list-style:none}
.cat-item{display:flex;align-items:center;gap:10px;padding:14px 24px;border-bottom:1px solid #f8f8f8}
.cat-item:last-child{border-bottom:none}
.cat-name{flex:1;font-size:.95rem}
.cat-index{color:#bbb;font-size:.8rem;width:24px;text-align:right;flex-shrink:0}
.btn-sm{padding:6px 12px;border-radius:5px;font-size:.8rem;cursor:pointer;border:1px solid #ddd;background:#fff;color:#555}
.btn-sm:hover{background:#f5f5f5}
.btn-danger{border-color:#fca5a5;color:#C41E3A}
.btn-danger:hover{background:#fef2f2}
.btn-primary{background:#C41E3A;color:#fff;border-color:#C41E3A;padding:10px 20px;border-radius:6px;font-size:.9rem}
.btn-primary:hover{background:#a01830}
.add-form{padding:18px 24px;display:flex;gap:10px;align-items:center}
.add-form input{flex:1;padding:10px 14px;border:1px solid #ddd;border-radius:6px;font-size:.9rem;outline:none}
.add-form input:focus{border-color:#C41E3A}
/* rename inline */
.rename-form{display:none;gap:6px;align-items:center}
.rename-form input{padding:6px 10px;border:1px solid #ddd;border-radius:5px;font-size:.85rem;outline:none;width:160px}
.rename-form input:focus{border-color:#C41E3A}
.btn-save-sm{padding:6px 12px;border-radius:5px;font-size:.8rem;cursor:pointer;border:none;background:#C41E3A;color:#fff}
.btn-save-sm:hover{background:#a01830}
.btn-cancel-sm{padding:6px 10px;border-radius:5px;font-size:.8rem;cursor:pointer;border:1px solid #ddd;background:#fff;color:#777}
</style>
</head>
<body>
<div class="topbar">
  <h1>分类管理</h1>
  <a href="index.php">← 返回文章列表</a>
</div>

<div class="main">
  <?php if ($success): ?>
  <div class="notice success"><?php echo htmlspecialchars($success); ?></div>
  <?php endif; ?>
  <?php if ($error): ?>
  <div class="notice error"><?php echo htmlspecialchars($error); ?></div>
  <?php endif; ?>

  <div class="card">
    <div class="card-header">当前分类（共 <?php echo count($cats); ?> 个）</div>
    <ul class="cat-list" id="catList">
      <?php foreach ($cats as $idx => $cat): ?>
      <li class="cat-item" id="cat-<?php echo $idx; ?>">
        <span class="cat-index"><?php echo $idx + 1; ?></span>
        <span class="cat-name" id="name-<?php echo $idx; ?>"><?php echo htmlspecialchars($cat); ?></span>

        <!-- 重命名内联表单 -->
        <form method="post" class="rename-form" id="rename-form-<?php echo $idx; ?>">
          <input type="hidden" name="action" value="rename">
          <input type="hidden" name="idx" value="<?php echo $idx; ?>">
          <input type="text" name="newname" value="<?php echo htmlspecialchars($cat); ?>" id="rename-input-<?php echo $idx; ?>">
          <button type="submit" class="btn-save-sm">保存</button>
          <button type="button" class="btn-cancel-sm" onclick="cancelRename(<?php echo $idx; ?>)">取消</button>
        </form>

        <button class="btn-sm" onclick="startRename(<?php echo $idx; ?>)" id="rename-btn-<?php echo $idx; ?>">重命名</button>
        <form method="post" style="display:inline" onsubmit="return confirm('确定删除分类「<?php echo htmlspecialchars($cat); ?>」？\n注意：已使用该分类的文章不会受影响，但分类标签将无法在下拉中选择。')">
          <input type="hidden" name="action" value="delete">
          <input type="hidden" name="idx" value="<?php echo $idx; ?>">
          <button type="submit" class="btn-sm btn-danger">删除</button>
        </form>
      </li>
      <?php endforeach; ?>
      <?php if (empty($cats)): ?>
      <li style="padding:24px;text-align:center;color:#bbb">暂无分类，请添加</li>
      <?php endif; ?>
    </ul>
    <div class="add-form">
      <form method="post" style="display:flex;gap:10px;width:100%;align-items:center">
        <input type="hidden" name="action" value="add">
        <input type="text" name="name" placeholder="输入新分类名称" required>
        <button type="submit" class="btn-primary">+ 添加分类</button>
      </form>
    </div>
  </div>
</div>

<script>
function startRename(idx) {
  document.getElementById('name-' + idx).style.display = 'none';
  document.getElementById('rename-btn-' + idx).style.display = 'none';
  var form = document.getElementById('rename-form-' + idx);
  form.style.display = 'flex';
  document.getElementById('rename-input-' + idx).focus();
}
function cancelRename(idx) {
  document.getElementById('name-' + idx).style.display = '';
  document.getElementById('rename-btn-' + idx).style.display = '';
  document.getElementById('rename-form-' + idx).style.display = 'none';
}
</script>
</body>
</html>
