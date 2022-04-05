<?php

// forkで子プロセスに実行が枝分かれ
// $pidは増えた子プロセス側では0、親側では子のPID
$pid = pcntl_fork();
$message = $pid === 0 ? '子' : '親';
for ($i = 0; $i < 1000; $i++) {
    echo $message;
}
// 「親親親親子親子親子子親親子」などのように混在して出力

if ($pid !== 0) {
    pcntl_wait($status); // 子の終了を待つ
}