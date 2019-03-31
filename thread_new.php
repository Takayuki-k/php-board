<html>
<head>
  <title>スレッド作成画面</title>
  <link rel="stylesheet" href="style.css">
  <script src="jquery.min.js"></script>
  <script src="my.js"></script>
  <?php
    $type = (isset($_POST['type']))? $_POST['type'] : null;

    require_once('functions.php');
    $data = new bbCls();

    if( ($type=='create') && ($_POST['title']==null||ctype_space($_POST['title'])) && ($_POST['article']==null||ctype_space($_POST['article'])) ) {
      $eTitle = "err";
      $eArticle = "err";
    } elseif ( ($type=='create') && ($_POST['title']==null||ctype_space($_POST['title'])) ) {
      $eTitle = "err";
    } elseif ( ($type=='create') && ($_POST['article']==null||ctype_space($_POST['article'])) ) {
      $eArticle = "err";

    } elseif ($type=='create') {

    $col[0] = $data->hs($_POST['title']);
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
    $data->newThread($col);

    header("Location: index.php");

  }
  ?>
</head>
<body>
  <form method="post" action="thread_new.php">
    <?php if (!empty($eTitle)) {
      echo "<p class='errMess'>タイトルが未入力です</p>";
    } ?>
    <label>
      タイトル<input type="text" name="title" value="<?php if( !empty($_POST['title']) ){ echo $_POST['title']; } ?>" />
    </label>
    <label>
      名前<input type="text" name="name" value="<?php if( !empty($_POST['name']) ){ echo $_POST['name']; } ?>" />
    </label>
    <label>
      メール<input type="text" name="email" value="<?php if( !empty($_POST['email']) ){ echo $_POST['email']; } ?>" />
    </label>
    <label>
      パス<input type="password" name="password" />
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
    <textarea cols="50" rows="10" id="article" name="article" placeholder="本文"><?php if( !empty($_POST['article']) ){ echo $_POST['article']; } ?></textarea>
    <input type="hidden" name="type" value="create" />
    <input type="submit" name="submit" value="作成" />
  </form>
</body>
</html>