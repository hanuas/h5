<?php

$max = 800000;
$workers = 20;
 
$pids = array();
for($i = 0; $i < $workers; $i++){
    $pids[$i] = pcntl_fork();
    switch ($pids[$i]) {
        case -1:
            echo "fork error : {$i} \r\n";
            exit;
        case 0:
            $param = array(
                'lastid' => $max / $workers * $i,
                'maxid' => $max / $workers * ($i+1),
            );
            print_r($param);
            //$this->executeWorker($input, $output, $param);
            exit;
        default:
            break;
    }
}
 
foreach ($pids as $i => $pid) {
    if($pid) {
        pcntl_waitpid($pid, $status);
    }
}

//有一个超级大的int数组要求和,假设有1000W,写一个php脚本,根据当前机器(假设是多核的)cpu的核数,fork出这么多子进程,把数组平分,每个子进程计算其中一部分,并把结果保存到/tmp/子进程pid.txt.

$num = 10;
$array = range(1,$num,1);

$workers = 2;
$pid = array();



for($i=0;$i<$workers;$i++){
    $pids[$i] = pcntl_fork();
    switch($pids[$i]){
        case -1:
            echo 'fork error:'.$i."\r\n";
            exit;
        case 0: //子进程
            $offset = floor($num/$workers);
            $start = $offset*$i;
            $arr = array_splice($array,$start,$offset);
            #print_r($arr);
            getSum($arr,$i);
            exit;
        default:
            break;


    }
}

$sum = 0;
foreach ($pids as $i => $pid) {
    if($pid) {
        pcntl_waitpid($pid, $status);
    }

    $sum+=file_get_contents("/tmp/{$i}.txt");

}
echo $sum;exit;


function getSum($arr,$pid){
    $res = 0;
    foreach($arr as $k=>$v){
        $res+=$v;
    }
    file_put_contents("/tmp/{$pid}.txt",$res);
}

