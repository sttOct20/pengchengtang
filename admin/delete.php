<?php
session_start();
require_once 'config.php';
if (!isset($_SESSION['admin'])) { header('Location: login.php'); exit; }

$id = $_POST['id'] ?? '';
if ($id) {
    $articles = loadArticles();
    $articles = array_filter($articles, fn($a) => $a['id'] !== $id);
    saveArticles(array_values($articles));
}
header('Location: index.php');
exit;
