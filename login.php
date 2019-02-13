<?php
date_default_timezone_set('Asia/Shanghai');

require 'config.php';
require __DIR__.'/vendor/autoload.php';

$debug = false;
$truncatedDebug = false;

$ig = new \InstagramAPI\Instagram($debug, $truncatedDebug);

try {
    $ig->login($username, $password);
    echo "Login succeed: ".$username."\n";
} catch (\Exception $e) {
    echo 'Something went wrong(login failed): '.$e->getMessage()."\n";
    exit(0);
}

// $videoFilename = './instest.mov';
// $captionText = 'my sky #nature #funny';
// // $hashtags = ['nature', 'funny'];
// try {
//     $video = new \InstagramAPI\Media\Video\InstagramVideo($videoFilename);
//     $ig->timeline->uploadVideo($video->getFile(), ['caption' => $captionText]);
// } catch (\Exception $e) {
//     echo 'Something went wrong: '.$e->getMessage()."\n";
// }