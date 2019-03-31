<html>
<head>
  <title>レス削除画面</title>
  <?php
    $idT = (isset($_GET['idT']) )? $_GET['idT'] : null;
    $idN = (isset($_GET['idN']) )? $_GET['idN'] : null;
    $type = (isset($_POST['type']) )? $_POST['type'] : null;

    require_once('functions.php');
    $data = new bbCls();

  if ($type=='delete') {

    $col[0] = $_GET["idT"];
    $col[1] = $_GET["idN"];
    $col[2]  = htmlspecialchars($_POST['password'], ENT_QUOTES);

    if( $data->resDel($col) ){
      //スレッド画面に遷移
      header("Location: thread.php?idT=".$idT);
    } else {
      echo "パスワードが違います。";
    }

  }
  ?>
</head>
<body>
  <form method="post" action="res_del.php?idT=<?php echo $idT ?>&idN=<?php echo $idN; ?>">
    <label>
      パス<input type="text" name="password" />
    </label>

    <input type="hidden" name="idT" value="<?php echo $idT; ?>" />
    <input type="hidden" name="idN" value="<?php echo $idN; ?>" />
    <input type="hidden" name="type" value="delete" />
    <input type="submit" name="submit" value="削除" />
  </form>
</body>
</html>