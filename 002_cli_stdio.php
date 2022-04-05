<?php

assert($_GET === []);
assert($_POST === []);
echo 'hello '; // 標準出力に出力
$name = fgets(STDIN); // 標準入力から1行取得
fputs(STDOUT, $name . PHP_EOL); // 標準出力に出力
