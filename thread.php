<html>
<head>
  <?php
    require_once('functions.php');

    $idT = $_GET["idT"];

    $data = new bbCls();
    $title = $data->selThrTitl($idT);
  ?>
  <title><?php echo $title; ?></title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <?php echo $title; ?>
  <div>
    <a href="res_new.php?idT=<?=$idT?>">[書き込み]</a>
    <a href="thread_del.php?idT=<?=$_GET['idT']?>">[削除]</a>
  </div>

  <hr>
  <?php
    $pagination = $data->selThread($idT);
  ?>
  <hr>
  <?php
    for ($x=1; $x <= $pagination ; $x++) {
      echo "<a href=\"?idT=".$idT."&page=".$x."\" class='pagination'>".$x."</a> ";
    }
  ?>
  <hr>
  <a href="res_new.php?idT=<?=$idT?>">[書き込み]</a>

</body>
</html>