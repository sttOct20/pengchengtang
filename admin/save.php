<?php
session_start();
require_once 'config.php';
if (!isset($_SESSION['admin'])) { header('Location: login.php'); exit; }

$articles = loadArticles();
$id = isset($_POST['id']) ? $_POST['id'] : '';
$title = trim(isset($_POST['title']) ? $_POST['title'] : '');
$category = isset($_POST['category']) ? $_POST['category'] : '';
$author = trim(isset($_POST['author']) ? $_POST['author'] : '');
$publishedDate = isset($_POST['publishedDate']) ? $_POST['publishedDate'] : date('Y-m-d');
$excerpt = trim(isset($_POST['excerpt']) ? $_POST['excerpt'] : '');
$content = isset($_POST['content']) ? $_POST['content'] : '';
$thumbnail = isset($_POST['thumbnail']) ? $_POST['thumbnail'] : '';

if (!$title) { header('Location: edit.php?error=1'); exit; }

if ($id) {
    for ($i = 0; $i < count($articles); $i++) {
        if ($articles[$i]['id'] === $id) {
            $articles[$i]['title'] = $title;
            $articles[$i]['category'] = $category;
            $articles[$i]['author'] = $author;
            $articles[$i]['publishedDate'] = $publishedDate;
            $articles[$i]['excerpt'] = $excerpt;
            $articles[$i]['content'] = $content;
            if ($thumbnail) $articles[$i]['thumbnail'] = $thumbnail;
            $articles[$i]['updatedAt'] = time();
            break;
        }
    }
} else {
    $slug = preg_replace('/[^a-z0-9]+/', '-', strtolower($title));
    $slug = trim($slug, '-') . '-' . substr(md5(time()), 0, 6);
    $articles[] = array(
        'id' => uniqid('art_'),
        'slug' => $slug,
        'title' => $title,
        'category' => $category,
        'author' => $author,
        'publishedDate' => $publishedDate,
        'excerpt' => $excerpt,
        'content' => $content,
        'thumbnail' => $thumbnail,
        'createdAt' => time(),
    );
}

saveArticles($articles);
header('Location: index.php?saved=1');
exit;
