<?php
$basePath = dirname(__DIR__);
$population = json_decode(file_get_contents('/home/kiang/public_html/tw_population/json/city/2020/11.json'), true);
$dataPath = $basePath . '/data/city/2020';
if(!file_exists($dataPath)) {
    mkdir($dataPath, 0777, true);
}
/*
    [0] => 縣市代碼
    [1] => 縣市名稱
    [2] => 鄉鎮市區代碼
    [3] => 鄉鎮市區名稱
    [4] => 平日夜間停留人數
    [5] => 平日上午活動人數
    [6] => 平日下午活動人數
    [7] => 平日日間活動人數
    [8] => 假日夜間停留人數
    [9] => 假日上午活動人數
    [10] => 假日下午活動人數
    [11] => 假日日間活動人數
    [12] => 平日早晨旅次
    [13] => 平日中午旅次
    [14] => 平日午後旅次
    [15] => 平日晚上旅次
    [16] => 假日早晨旅次
    [17] => 假日中午旅次
    [18] => 假日午後旅次
    [19] => 假日晚上旅次
    [20] => 資料時間
*/
$fh = fopen($basePath . '/raw/行政區電信信令人口統計資料/109年11月行政區電信信令人口統計資料_鄉鎮市區.csv', 'r');
$header = fgetcsv($fh, 2048);
fgetcsv($fh, 2048);
$result = [];
while($line = fgetcsv($fh, 2048)) {
    foreach($line AS $k => $v) {
        if($k > 3) {
            if($k > 11) {
                if($k < 20) {
                    $line[$k] = floatval($v);
                }
            } else {
                $line[$k] = intval($v);
            }
        }
    }
    $data = array_combine($header, $line);
    $pKey = substr($data['TOWN_ID'], 0, -1);
    $result[$data['TOWN_ID']] = [
        'population' => $population[$pKey]['population'],
        'work_night' => $data['NIGHT_WORK'],
        'work_day1' => $data['DAY_WORK(7:00~13:00)'],
        'work_day2' => $data['DAY_WORK(13:00~19:00)'],
        'work_day' => $data['DAY_WORK'],
        'weekend_night' => $data['NIGHT_WEEKEND'],
        'weekend_day1' => $data['DAY_WEEKEND(7:00~13:00)'],
        'weekend_day2' => $data['DAY_WEEKEND(13:00~19:00)'],
        'weekend_day' => $data['DAY_WEEKEND'],
    ];
}
file_put_contents($dataPath . '/11.json', json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));