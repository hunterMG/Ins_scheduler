<?php

date_default_timezone_set('Asia/Shanghai');

require __DIR__.'/vendor/autoload.php';
require 'login.php';

$verbose = false;

// echo "现在时间是 " . date("h:i:s")."\n";
$lines =  file("./resource/task.txt");
$scheduler = new \Scheduler\Scheduler();
$jobs = [];
foreach ($lines as $line) {
    $line = trim($line);
    if ($line == "") {
        continue;
    }
    // print_r($line."\n");
    list($dt, $captionText, $videoFilename) = explode(";", $line, 3);
    $videoFilename = trim($videoFilename);
    $videoFilepath = "./resource/" . $videoFilename;
    if($verbose){
        echo $dt."\n";
        echo $captionText."\n";
        echo "#####".$videoFilepath."#####\n";
    }
    // print_r($videoFilepath."\n");
    $executionTime = new \DateTime($dt);
    $rule = new \Scheduler\Job\RRule('FREQ=MINUTELY;COUNT=1', $executionTime); 
    //run monthly, at 20:00:00 starting from the 12th of December 2017, 5 times
    $job = new \Scheduler\Job\Job($rule,function() use($ig, $dt, $videoFilepath, $captionText){
        echo "POSTING: ".$dt." # ".$captionText;
        try {
            $video = new \InstagramAPI\Media\Video\InstagramVideo($videoFilepath);
            if ($video->getFile() == null){
                echo "DEBUG: null\n";
                return;
            }
            if($ig == null){
                echo "DEBUG: ig = null\n";
                exit;
            }
            $ig->timeline->uploadVideo($video->getFile(), ['caption' => $captionText]);
        } catch (\Exception $e) {
            echo 'Something went wrong: '.$e->getMessage()."\n";
        }
        echo " ___DONE\n";
    });
    $scheduler->addJob($job);
    // array_push($jobs, $job);
}
// echo "LOG :".sizeof($scheduler->jobs)."jobs added.\n";

$jobRunner = new \Scheduler\JobRunner\JobRunner();
$from      = new \DateTime('2017-12-12 20:00:00');
$to        = new \DateTime('2019-12-12 20:10:00');
// $reports   = $jobRunner->run($scheduler, $from, $to, true);

$worker = new \Scheduler\Worker\Worker($jobRunner, $scheduler);
$worker->setMaxIterations(10);
$worker->run(time(), 'PT1M');