<html>
<head>
  <title>掲示板</title>
<?php
//アクセス記録を残すPath（テンポラリ/IP）を決定
$file = 'apache.txt';

//アクセスログを記録
//ファイル名はIPで中身はunixtime
//厳密に書き込むならロック制御が必要(ロックされてるとE_NOTICEかE_WARNINGがでるハズ)
file_put_contents($file, time() . PHP_EOL, FILE_APPEND | LOCK_EX);

//読み取り間隔を設定
//lineSizeはunixtime10桁+改行固定(11桁になるのは2286年)
$lineSize = 11;
//読み取り行数
$maxRow = 10;
//連続アクセスしたとするしきい値（秒）
$limitTime = 5;
//読み取るバイト数
$readByte = $lineSize * $maxRow;
//バイト数分後ろから読む
$readContent = file_get_contents($file, false, null, filesize($file) - $readByte);
$lines = explode(PHP_EOL, $readContent);
//バイト単位で読むので先頭がかけてる場合を想定して予め先頭行を切り落とす
array_shift($lines);

//初回と連続アクセス規制に満たなければ通過させる
if ($lines[0] + $limitTime < time() || count($lines) < $maxRow):?>
</head>
<body>
<h1>sample掲示板</h1>
<a href="thread_new.php">[新規スレッド]</a>
<a href="search.php">[検索]</a>
<hr>
<?php
  require_once('functions.php');

  $pdo = new bbCls();

  $pagination = $pdo->selTitleList();

  echo "<hr>";

  for ($x=1; $x <= $pagination ; $x++) {
    echo "<a href=\"?page=".$x."\" class='pagination'>".$x."</a> ";
  }
?>
</body>
<?php
    exit;
  else:
    echo $lines[0] - (time() - $limitTime) . '秒まってね';
    exit;

  endif;
?>
</html>