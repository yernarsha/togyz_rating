<?php

require 'vendor/autoload.php';
require 'lib.php';

const root = 'http://9kumalak.com';

function getPlayers($client, $uri, $filename) {
    $uri = root . $uri;
    $response = $client->request('GET', $uri);

    $lists = $response->evaluate('//table[@class="category"]//tbody//tr//td/a');
    $new_link = '';

    foreach ($lists as $key => $title) {
//        echo trim($title->textContent) . ', ' . $title->getAttribute('href') . PHP_EOL;
        $new_link = root . $title->getAttribute('href');
        break;
    }

    echonl($new_link);
    if (empty($new_link)) return;

    $players_file = fopen($filename, "w");

    $response = $client->request('GET', $new_link);
    $table = $response->evaluate('//table[@class="MsoNormalTable"]//tbody//tr');

    foreach ($table as $key => $title) {
        $str = $title->textContent;
        $str = trim(preg_replace('/\s\s+/', ' ', $str));
        fwrite($players_file, $str . "\n");
    }

    fclose($players_file);
}

$httpClient = new \Goutte\Client();
getPlayers($httpClient, '/index.php/2012-04-10-18-56-53/reiting-uldar', 'men.txt');
getPlayers($httpClient, '/index.php/2012-04-10-18-56-53/yzdar', 'ladies.txt');
echo 'Well done, officer!';

