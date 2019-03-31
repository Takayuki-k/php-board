<html>
<head>
  <title>検索フォーム画面</title>
  <?php
    $type = (isset($_POST['type']) )? $_POST['type'] : null;
    require_once('functions.php');
    $data = new bbCls();

  if($type=='search') {

  echo $data;
    $data = $data->hs($search);
  echo $data;
    header("Location: searchRes.php?search=".$data);

  }
  ?>
</head>
<body>
  <form method="get" action="searchRes.php">
    <label>検索値<input type="text" name="search" /></label>
    <input type="hidden" name="type" value="search" />
    <input type="submit" name="submit" value="検索" />
  </form>
</body>
</html>