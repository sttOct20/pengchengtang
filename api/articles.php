<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

$dataFile = __DIR__ . '/../data/articles.json';
if (!file_exists($dataFile)) { echo json_encode(array('articles'=>array(),'total'=>0)); exit; }

$articles = json_decode(file_get_contents($dataFile), true);
if (!$articles) $articles = array();

// Single article by id
$id = isset($_GET['id']) ? $_GET['id'] : '';
if ($id) {
    $article = null;
    foreach ($articles as $a) {
        if ($a['id'] === $id) { $article = $a; break; }
    }
    echo json_encode(array('articles' => $article ? array($article) : array(), 'total' => $article ? 1 : 0), JSON_UNESCAPED_UNICODE);
    exit;
}

// filter by category
$cat = isset($_GET['category']) ? $_GET['category'] : '';
if ($cat && $cat !== '全部') {
    $tmp = array();
    foreach ($articles as $a) {
        if (isset($a['category']) && $a['category'] === $cat) $tmp[] = $a;
    }
    $articles = $tmp;
}

// sort by date desc
usort($articles, function($a, $b) {
    $da = isset($a['publishedDate']) ? $a['publishedDate'] : '';
    $db = isset($b['publishedDate']) ? $b['publishedDate'] : '';
    return strcmp($db, $da);
});

// pagination
$page = max(1, intval(isset($_GET['page']) ? $_GET['page'] : 1));
$limit = intval(isset($_GET['limit']) ? $_GET['limit'] : 9);
$total = count($articles);
$articles = array_slice(array_values($articles), ($page - 1) * $limit, $limit);

// strip full content from list view
if (!isset($_GET['full'])) {
    foreach ($articles as &$a) unset($a['content']);
}

echo json_encode(array(
    'total' => $total,
    'page' => $page,
    'limit' => $limit,
    'articles' => array_values($articles)
), JSON_UNESCAPED_UNICODE);
