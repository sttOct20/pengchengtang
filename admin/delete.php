<?php
session_start();
require_once 'config.php';
if (!isset($_SESSION['admin'])) { header('Location: login.php'); exit; }

$id = isset($_POST['id']) ? $_POST['id'] : '';
if ($id) {
    $articles = loadArticles();
    $filtered = array();
    foreach ($articles as $a) {
        if ($a['id'] !== $id) $filtered[] = $a;
    }
    saveArticles(array_values($filtered));
}
header('Location: index.php');
exit;
