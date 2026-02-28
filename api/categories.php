<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

$catsFile = __DIR__ . '/../data/categories.json';
$defaults = array('炁针疗法','辨证施治','疑难杂症','健康养生','中药本草');

if (!file_exists($catsFile)) {
    echo json_encode($defaults, JSON_UNESCAPED_UNICODE);
    exit;
}

$data = json_decode(file_get_contents($catsFile), true);
echo json_encode((is_array($data) && count($data) > 0) ? $data : $defaults, JSON_UNESCAPED_UNICODE);
