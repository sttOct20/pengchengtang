<?php
session_start();
require_once 'config.php';

if (isset($_POST['password'])) {
    if ($_POST['password'] === ADMIN_PASSWORD) {
        $_SESSION['admin'] = true;
        header('Location: index.php');
        exit;
    }
    $error = '密码错误';
}

if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>后台登录 — 彭城堂中医</title>
<style>
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:'PingFang SC',sans-serif;background:#f5f5f5;display:flex;align-items:center;justify-content:center;min-height:100vh}
.box{background:#fff;padding:48px 40px;border-radius:8px;box-shadow:0 2px 20px rgba(0,0,0,.08);width:360px}
.logo{text-align:center;margin-bottom:32px}
.logo h1{font-size:1.4rem;color:#111;margin-top:8px}
.logo p{font-size:.85rem;color:#999;margin-top:4px}
input[type=password]{width:100%;padding:12px 16px;border:1px solid #ddd;border-radius:6px;font-size:1rem;outline:none;transition:border .2s}
input[type=password]:focus{border-color:#C41E3A}
button{width:100%;padding:13px;background:#C41E3A;color:#fff;border:none;border-radius:6px;font-size:1rem;cursor:pointer;margin-top:16px}
button:hover{background:#a01830}
.error{color:#C41E3A;font-size:.85rem;margin-top:10px;text-align:center}
</style>
</head>
<body>
<div class="box">
  <div class="logo">
    <h1>彭城堂中医</h1>
    <p>内容管理后台</p>
  </div>
  <form method="post">
    <input type="password" name="password" placeholder="请输入管理员密码" required autofocus>
    <button type="submit">登录</button>
    <?php if (isset($error)): ?><p class="error"><?= $error ?></p><?php endif; ?>
  </form>
</div>
</body>
</html>
