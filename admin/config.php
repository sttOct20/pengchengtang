<?php
define('ADMIN_PASSWORD', 'pengchengtang2025');
define('DATA_FILE', __DIR__ . '/../data/articles.json');
define('CATS_FILE', __DIR__ . '/../data/categories.json');
define('UPLOAD_DIR', __DIR__ . '/../assets/uploads/');
define('UPLOAD_URL', '/assets/uploads/');

$DEFAULT_CATS = array('炁针疗法','辨证施治','疑难杂症','健康养生','中药本草');

function loadArticles() {
    if (!file_exists(DATA_FILE)) return array();
    $json = file_get_contents(DATA_FILE);
    $data = json_decode($json, true);
    return $data ? $data : array();
}

function saveArticles($articles) {
    if (!file_exists(dirname(DATA_FILE))) {
        mkdir(dirname(DATA_FILE), 0755, true);
    }
    file_put_contents(DATA_FILE, json_encode(array_values($articles), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
}

function loadCategories() {
    global $DEFAULT_CATS;
    if (!file_exists(CATS_FILE)) return $DEFAULT_CATS;
    $json = file_get_contents(CATS_FILE);
    $data = json_decode($json, true);
    return (is_array($data) && count($data) > 0) ? $data : $DEFAULT_CATS;
}

function saveCategories($cats) {
    if (!file_exists(dirname(CATS_FILE))) {
        mkdir(dirname(CATS_FILE), 0755, true);
    }
    file_put_contents(CATS_FILE, json_encode(array_values($cats), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
}
