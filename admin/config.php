<?php
define('ADMIN_PASSWORD', 'pengchengtang2025');
define('DATA_FILE', __DIR__ . '/../data/articles.json');
define('UPLOAD_DIR', __DIR__ . '/../assets/uploads/');
define('UPLOAD_URL', '/assets/uploads/');

function loadArticles() {
    if (!file_exists(DATA_FILE)) return [];
    $json = file_get_contents(DATA_FILE);
    return json_decode($json, true) ?: [];
}

function saveArticles($articles) {
    if (!file_exists(dirname(DATA_FILE))) {
        mkdir(dirname(DATA_FILE), 0755, true);
    }
    file_put_contents(DATA_FILE, json_encode(array_values($articles), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
}
