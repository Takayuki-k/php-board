<html>
<head>
  <title>検索結果画面</title>
  <link rel="stylesheet" href="style.css">
<?php
  require_once('functions.php');
  $data = new bbCls();
?>
</head>
<body>
  <?php
    $pagination = $data->seaRes($_GET['search']);
  ?>
  <hr>
  <?php
    for ($x=0; $x < $pagination ; $x++) {
    echo "<a href=searchRes.php?search=".$_GET['search']."&type=search&submit=検索&page=".$x." class='pagination'>".$x."</a>";
    }
  ?>
</body>
</html>