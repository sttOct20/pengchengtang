<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

$dataFile = __DIR__ . '/../data/articles.json';
if (!file_exists($dataFile)) { echo json_encode(['articles'=>[],'total'=>0]); exit; }

$articles = json_decode(file_get_contents($dataFile), true) ?: [];

// Single article by id
$id = $_GET['id'] ?? '';
if ($id) {
    $article = null;
    foreach ($articles as $a) {
        if ($a['id'] === $id) { $article = $a; break; }
    }
    echo json_encode(['articles' => $article ? [$article] : [], 'total' => $article ? 1 : 0], JSON_UNESCAPED_UNICODE);
    exit;
}

// filter by category
$cat = $_GET['category'] ?? '';
if ($cat && $cat !== '全部') {
    $articles = array_filter($articles, fn($a) => ($a['category'] ?? '') === $cat);
}

// sort by date desc
usort($articles, fn($a, $b) => strcmp($b['publishedDate'] ?? '', $a['publishedDate'] ?? ''));

// pagination
$page = max(1, intval($_GET['page'] ?? 1));
$limit = intval($_GET['limit'] ?? 9);
$total = count($articles);
$articles = array_slice(array_values($articles), ($page - 1) * $limit, $limit);

// strip full content from list view
if (!isset($_GET['full'])) {
    foreach ($articles as &$a) unset($a['content']);
}

echo json_encode([
    'total' => $total,
    'page' => $page,
    'limit' => $limit,
    'articles' => array_values($articles)
], JSON_UNESCAPED_UNICODE);
