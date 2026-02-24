<?php
session_start();
require_once 'config.php';
if (!isset($_SESSION['admin'])) { http_response_code(403); echo json_encode(['error'=>'未授权']); exit; }

header('Content-Type: application/json');

if (!isset($_FILES['file'])) { echo json_encode(['error'=>'无文件']); exit; }

$file = $_FILES['file'];
$allowed = ['image/jpeg','image/png','image/webp','image/gif'];
if (!in_array($file['type'], $allowed)) { echo json_encode(['error'=>'仅支持图片格式']); exit; }
if ($file['size'] > 5 * 1024 * 1024) { echo json_encode(['error'=>'文件不能超过5MB']); exit; }

if (!file_exists(UPLOAD_DIR)) mkdir(UPLOAD_DIR, 0755, true);

$ext = pathinfo($file['name'], PATHINFO_EXTENSION);
$filename = uniqid('img_') . '.' . strtolower($ext);
$dest = UPLOAD_DIR . $filename;

if (move_uploaded_file($file['tmp_name'], $dest)) {
    echo json_encode(['url' => UPLOAD_URL . $filename]);
} else {
    echo json_encode(['error' => '上传失败，请检查目录权限']);
}
