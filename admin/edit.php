<?php
session_start();
require_once 'config.php';
if (!isset($_SESSION['admin'])) { header('Location: login.php'); exit; }

$articles = loadArticles();
$id = isset($_GET['id']) ? $_GET['id'] : null;
$article = null;
if ($id) {
    foreach ($articles as $a) {
        if ($a['id'] === $id) { $article = $a; break; }
    }
}
$cats = array('炁针疗法','辨证施治','疑难杂症','健康养生','中药本草');
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title><?php echo $article ? '编辑文章' : '新建文章'; ?> — 彭城堂中医</title>
<link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
<style>
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:'PingFang SC',sans-serif;background:#f7f7f7;color:#333}
.topbar{background:#111;padding:0 24px;height:56px;display:flex;align-items:center;gap:16px}
.topbar h1{color:#fff;font-size:1rem;flex:1}
.topbar a{color:#999;font-size:.85rem;text-decoration:none}
.topbar a:hover{color:#fff}
.main{max-width:900px;margin:32px auto;padding:0 20px 80px}
.field{margin-bottom:20px}
label{display:block;font-size:.85rem;font-weight:600;margin-bottom:8px;color:#555}
input[type=text],input[type=date],select{width:100%;padding:11px 14px;border:1px solid #ddd;border-radius:6px;font-size:.95rem;outline:none;background:#fff}
input:focus,select:focus{border-color:#C41E3A}
.row{display:grid;grid-template-columns:1fr 1fr;gap:16px}
#editor-container{background:#fff;border-radius:6px;border:1px solid #ddd}
#editor-container .ql-toolbar{border-radius:6px 6px 0 0;border-color:#ddd}
#editor-container .ql-container{border-radius:0 0 6px 6px;border-color:#ddd;min-height:360px;font-size:1rem}
.bottom-bar{position:fixed;bottom:0;left:0;right:0;background:#fff;border-top:1px solid #eee;padding:14px 24px;display:flex;justify-content:flex-end;gap:12px}
.btn-save{background:#C41E3A;color:#fff;padding:11px 32px;border:none;border-radius:6px;font-size:.95rem;cursor:pointer;font-weight:600}
.btn-save:hover{background:#a01830}
.btn-cancel{padding:11px 24px;border:1px solid #ddd;border-radius:6px;background:#fff;font-size:.95rem;cursor:pointer;text-decoration:none;color:#555}
.btn-cancel:hover{background:#f5f5f5}
.upload-btn{display:inline-block;padding:8px 16px;border:1px dashed #C41E3A;border-radius:6px;color:#C41E3A;cursor:pointer;font-size:.85rem;margin-top:4px}
.upload-btn:hover{background:#fef2f2}
.hint{font-size:.78rem;color:#aaa;margin-top:4px}
.thumb-preview{margin-top:8px}
.thumb-preview img{height:60px;border-radius:4px;object-fit:cover}
</style>
</head>
<body>
<div class="topbar">
  <h1><?php echo $article ? '编辑文章' : '新建文章'; ?></h1>
  <a href="index.php">← 返回列表</a>
</div>
<div class="main">
  <form id="articleForm" method="post" action="save.php">
    <input type="hidden" name="id" value="<?php echo htmlspecialchars(isset($article['id']) ? $article['id'] : ''); ?>">
    <input type="hidden" name="content" id="contentInput">
    <input type="hidden" name="thumbnail" id="thumbnailInput" value="<?php echo htmlspecialchars(isset($article['thumbnail']) ? $article['thumbnail'] : ''); ?>">

    <div class="field">
      <label>文章标题 *</label>
      <input type="text" name="title" value="<?php echo htmlspecialchars(isset($article['title']) ? $article['title'] : ''); ?>" placeholder="请输入文章标题" required>
    </div>

    <div class="row">
      <div class="field">
        <label>文章分类 *</label>
        <select name="category" required>
          <?php foreach ($cats as $c): ?>
          <option value="<?php echo $c; ?>" <?php echo (isset($article['category']) && $article['category'] === $c) ? 'selected' : ''; ?>><?php echo $c; ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="field">
        <label>发布日期</label>
        <input type="date" name="publishedDate" value="<?php echo htmlspecialchars(isset($article['publishedDate']) ? $article['publishedDate'] : date('Y-m-d')); ?>">
      </div>
    </div>

    <div class="field">
      <label>封面图片</label>
      <label class="upload-btn" for="thumbFile">点击上传封面图</label>
      <input type="file" id="thumbFile" accept="image/*" style="display:none">
      <p class="hint">建议尺寸 800×500，支持 JPG / PNG / WebP</p>
      <div class="thumb-preview" id="thumbPreview">
        <?php if (!empty($article['thumbnail'])): ?>
        <img src="<?php echo htmlspecialchars($article['thumbnail']); ?>" id="thumbImg">
        <?php endif; ?>
      </div>
    </div>

    <div class="field">
      <label>文章摘要</label>
      <input type="text" name="excerpt" value="<?php echo htmlspecialchars(isset($article['excerpt']) ? $article['excerpt'] : ''); ?>" placeholder="简短描述，显示在列表页（可选）">
    </div>

    <div class="field">
      <label>文章正文 *</label>
      <div id="editor-container">
        <div id="editor"><?php echo isset($article['content']) ? $article['content'] : ''; ?></div>
      </div>
    </div>
  </form>
</div>

<div class="bottom-bar">
  <a href="index.php" class="btn-cancel">取消</a>
  <button class="btn-save" onclick="submitForm()">保存发布</button>
</div>

<script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>
<script>
var quill = new Quill('#editor', {
  theme: 'snow',
  placeholder: '开始撰写文章内容...',
  modules: {
    toolbar: [
      [{ header: [1, 2, 3, false] }],
      ['bold', 'italic', 'underline', 'strike'],
      [{ color: [] }, { background: [] }],
      [{ align: [] }],
      [{ list: 'ordered' }, { list: 'bullet' }],
      ['blockquote'],
      ['link', 'image'],
      ['clean']
    ]
  }
});

quill.getModule('toolbar').addHandler('image', function() {
  var input = document.createElement('input');
  input.type = 'file';
  input.accept = 'image/*';
  input.click();
  input.onchange = function() {
    var file = input.files[0];
    if (!file) return;
    uploadFile(file, function(url) {
      if (url) {
        var range = quill.getSelection(true);
        quill.insertEmbed(range.index, 'image', url);
      }
    });
  };
});

document.getElementById('thumbFile').addEventListener('change', function() {
  var file = this.files[0];
  if (!file) return;
  uploadFile(file, function(url) {
    if (url) {
      document.getElementById('thumbnailInput').value = url;
      document.getElementById('thumbPreview').innerHTML = '<img src="' + url + '" style="height:60px;border-radius:4px;object-fit:cover">';
    }
  });
});

function uploadFile(file, callback) {
  var fd = new FormData();
  fd.append('file', file);
  var xhr = new XMLHttpRequest();
  xhr.open('POST', 'upload.php');
  xhr.onload = function() {
    try {
      var data = JSON.parse(xhr.responseText);
      callback(data.url || null);
      if (!data.url) alert('上传失败：' + (data.error || '未知错误'));
    } catch(e) { callback(null); }
  };
  xhr.send(fd);
}

function submitForm() {
  document.getElementById('contentInput').value = quill.root.innerHTML;
  document.getElementById('articleForm').submit();
}
</script>
</body>
</html>
