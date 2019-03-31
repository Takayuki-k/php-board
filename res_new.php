<html>
<head>
  <title>レス投稿画面</title>
  <link rel="stylesheet" href="style.css">
  <script src="jquery.min.js"></script>
  <script src="my.js"></script>
  <?php
    $idT = (isset($_GET['idT']) )? $_GET['idT'] : null;
    $idN = (isset($_GET['idN']) )? $_GET['idN'] : null;
    $type = (isset($_POST['type']) )? $_POST['type'] : null;

    require_once('functions.php');
    $data = new bbCls();

    if( ($type=='create') && ($_POST['article']==null||ctype_space($_POST['article'])) ) {
      $eArticle = "err";
    } elseif($type=='create') {

      $col[0] = $_GET["idT"];
      $col[1] = $data->hs($_POST['name']);
      $col[2] = $data->trip();
      $col[3] = $data->hs($_POST['email']);
      $col[4] = date('Y-m-d h:i:s');

      if(is_null($_POST['password'])) { // passを強制
        $col[5] = crypt($col[2]);
      } else {
        $col[5] = $data->hs($_POST['password']);
      }

      $col[6] = nl2br($data->hs($_POST['article']));
      if (isset($_POST['down'])){
        $col[7] = true;
      }else{
        $col[7] = false;
      }

      $data->newRes($col);
      //スレッド画面に遷移
      header("Location: thread.php?idT=".$idT);

    }
  ?>
</head>
<body>
  <form method="post" action="res_new.php?idT=<?=$idT?>">
    <label>
      名前<input type="text" name="name" value="<?php if( !empty($_POST['name']) ){ echo $_POST['name']; } ?>" />
    </label>
    <label>
      メール<input type="text" name="email" value="<?php if( !empty($_POST['email']) ){ echo $_POST['email']; } ?>" />
    </label>
    <label>
      パス<input type="text" name="password" />
    </label>

    <div>
      <input type="button" class="redBtn" value="赤">
      <input type="button" class="bluBtn" value="青">
      <input type="button" class="greBtn" value="緑">
      <input type="button" class="ylwBtn" value="黄">
      <input type="button" class="whtBtn" value="白">
      <input type="button" class="gryBtn" value="灰">
      <input type="button" class="qutBtn" value="引用">
      <input type="button" class="ancBtn" value="リンク">
      <input type="button" class="bdoBtn" value="逆">
      <input type="button" class="bigBtn" value="大文字">
      <input type="button" class="smlBtn" value="小文字">
      <input type="button" class="hrzBtn" value="横線">
    </div>
    <?php if (!empty($eArticle)) {
      echo "<p class='errMess'>本文が未入力です</p>";
    } ?>
    <textarea cols="50" rows="10" id="article" name="article" placeholder="本文"><?php if(isset($_GET['idN'])){ echo ">>".$idN."\n";} ?></textarea>
    <label>
      下げ<input type="checkbox" name="down" value="true">
    </label>

    <input type="hidden" name="idT" value="<?=$idT?>" />
    <input type="hidden" name="type" value="create" />
    <input type="submit" name="submit" value="投稿" />
  </form>
</body>
</html>