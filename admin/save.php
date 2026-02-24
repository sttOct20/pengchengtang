<?php
session_start();
require_once 'config.php';
if (!isset($_SESSION['admin'])) { header('Location: login.php'); exit; }

$articles = loadArticles();
$id = $_POST['id'] ?? '';
$title = trim($_POST['title'] ?? '');
$category = $_POST['category'] ?? '';
$publishedDate = $_POST['publishedDate'] ?? date('Y-m-d');
$excerpt = trim($_POST['excerpt'] ?? '');
$content = $_POST['content'] ?? '';
$thumbnail = $_POST['thumbnail'] ?? '';

if (!$title) { header('Location: edit.php?error=1'); exit; }

if ($id) {
    foreach ($articles as &$a) {
        if ($a['id'] === $id) {
            $a['title'] = $title;
            $a['category'] = $category;
            $a['publishedDate'] = $publishedDate;
            $a['excerpt'] = $excerpt;
            $a['content'] = $content;
            if ($thumbnail) $a['thumbnail'] = $thumbnail;
            $a['updatedAt'] = time();
            break;
        }
    }
} else {
    $slug = preg_replace('/[^a-z0-9]+/', '-', strtolower($title));
    $slug = trim($slug, '-') . '-' . substr(md5(time()), 0, 6);
    $articles[] = [
        'id' => uniqid('art_'),
        'slug' => $slug,
        'title' => $title,
        'category' => $category,
        'publishedDate' => $publishedDate,
        'excerpt' => $excerpt,
        'content' => $content,
        'thumbnail' => $thumbnail,
        'createdAt' => time(),
    ];
}

saveArticles($articles);
header('Location: index.php?saved=1');
exit;
