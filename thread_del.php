<html>
<head>
  <title>スレッド削除画面</title>
  <?php
    $id = (isset($_GET['idT']) )? $_GET['idT'] : null;
    $type = (isset($_POST['type']) )? $_POST['type'] : null;

    require_once('functions.php');
    $data = new bbCls();

    if ($type=='delete') {

      $col[0] = $_GET["idT"];
      $col[1]  = htmlspecialchars($_POST['password'], ENT_QUOTES);

      if( $data->thrDel($col) ){
        //スレッド画面に遷移
        header("Location: index.php");
      } else {
        echo "パスワードが違います。";
      }

    }
  ?>
</head>
<body>
<form method="post" action="thread_del.php?idT=<?=$idT?>">
  <label>パス<input type="text" name="password" /></label>

  <input type="hidden" name="id" value="<?=$idT?>" />
  <input type="hidden" name="type" value="delete" />
  <input type="submit" name="submit" value="削除" />
</form>
</body>
</html>