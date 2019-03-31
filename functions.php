<?php

class bbCls {

  private $stmt = [];
  private $default = null;
  private $name;
  public $noName = "774";

  // PDO生成--clear
  function dbconnect(){
    $db      = "mysql";
    $host    = "localhost";
    $dbname  = "bb_db";
    $charset = "utf8";
    $dsn = $db.':host='.$host.';dbname='.$dbname.';charset='.$charset.';';
    $user    = "bb_user";
    $pass    = "bbpass";

    try {
      $dbh = new PDO($dsn, $user, $pass);
      $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      return $dbh;

    } catch (PDOException $e) {
      echo 'Connection failed:'.$e->getMessage();
      die();
    }
  }


  // タイトル一覧用SQL--clear
  function selTitleList(){
    try {
      $dbh = $this->dbconnect();

      if ( isset($_GET['page']) ) {
        $page = (int)$_GET['page'];
      } else {
        $page = 1;
      }

      // スタートのポジションを計算
      if ($page > 1) {
        // 2ページ目 (2×10)-10 = 10』
        $start = ($page * 10) - 11;
      } else {
        $start = 0;
      }

      if ( $start == 0 ){
        $stmt = $dbh->prepare("
          SELECT
            titleList.idTitle,
            titleList.title,
            COUNT(thread.idName) AS cntRes
          FROM titleList, thread
          WHERE
            titleList.idTitle = 0 AND
            titleList.idTitle = thread.idTitle
          group by titleList.idTitle");
        $stmt->execute();
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($users as $value) {
          echo $value['idTitle'].'.<a href="thread.php?idT='.$value['idTitle'].'">'.$value['title'].';'.$value['cntRes'].'</a><br>';
        }
      }

      $stmt = $dbh->prepare("
        SELECT
          titleList.idTitle,
          titleList.title,
          COUNT(thread.idName) AS cntRes
        FROM
          thread,
          titleList
        WHERE
          titleList.idTitle != 0 AND
          titleList.idTitle = thread.idTitle
        GROUP BY thread.idTitle
        ORDER BY MAX( IF(thread.down, '1992-08-14', thread.date ) ) DESC
        LIMIT :start, :end");
      if ($start == 0) {
        $stmt->bindParam(':start', $start, PDO::PARAM_INT);
        $stmt->bindValue(':end', 9, PDO::PARAM_INT);
      }else{
        $stmt->bindParam(':start', $start, PDO::PARAM_INT);
        $stmt->bindValue(':end', 10, PDO::PARAM_INT);
      }
      $stmt->execute();
      $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
      foreach ($users as $value) {
        echo $value['idTitle'].'.<a href="thread.php?idT='.$value['idTitle'].'">'.$value['title'].';'.$value['cntRes'].'</a><br>';
      }

      $page_num = $dbh->prepare("
        SELECT COUNT(idTitle)
        FROM titleList
      ");
      $page_num->execute();
      $page_num = $page_num->fetchColumn();

      // ページネーション
      return $pagination = ceil($page_num / 10);

    } catch (PDOException $e) {
      echo 'Error:'.$e->getMessage();
      die();
    }
  }

  // スレッド作成用SQL--clear
  public function newThread(&$col){
    try {
      $dbh = $this->dbconnect();
      $idName = 1;
      $newThrId = $this->retThrId();

      if($col[5] == null) { // passを強制的に挿入
        $col[5] = crypt($col[2]);
      }

      $stmt = $dbh->prepare("
        INSERT INTO thread (
          `idTitle`,
          `idName`,
          `name`,
          `trip`,
          `email`,
          `date`,
          `password`,
          `article`)
        VALUES (
          :idTitle,
          :idName,
          :name,
          :trip,
          :email,
          :date,
          :password,
          :article)");
      $stmt->bindParam(':idTitle', $newThrId, PDO::PARAM_INT);
      $stmt->bindParam(':idName', $idName, PDO::PARAM_INT);
      $stmt->bindParam(':name', $col[1], PDO::PARAM_STR);
      $stmt->bindParam(':trip', $col[2], PDO::PARAM_STR);
      $stmt->bindParam(':email', $col[3], PDO::PARAM_STR);
      $stmt->bindParam(':date', $col[4], PDO::PARAM_STR);
      $stmt->bindParam(':password', $col[5], PDO::PARAM_STR);
      $stmt->bindParam(':article', $col[6], PDO::PARAM_STR);
      $stmt->execute();

      $titleStmt = $dbh->prepare("
        INSERT INTO titleList (
          `idTitle`,
          `title` )
        VALUES (
          :idTitle,
          :title)");
      $titleStmt->bindParam(':idTitle', $newThrId, PDO::PARAM_INT);
      $titleStmt->bindParam(':title', $col[0], PDO::PARAM_STR);
      $titleStmt->execute();

    } catch (PDOException $e) {
      echo 'Connection failed:'.$e->getMessage();
      die();
    }
  }


  // 新規スレッドid生成--clear
  public function retThrId(){
    try {
      $dbh = $this->dbconnect();
      $sql = "SELECT MAX(titleList.idTitle)+1 FROM titleList";
      $stmt = $dbh->query($sql);
      $stmt->execute();

      $objNeThId = $stmt->fetch();
      $iNeThId = (int)$objNeThId[0];
      return $iNeThId;
    }catch(PDOException $e){
      echo 'Connection failed:'.$e->getMessage();
      die();
    }
  }


  // スレッドタイトル
  public function selThrTitl($idTitle){
    try {
      $dbh = $this->dbconnect();

      $stmt = $dbh->prepare("
        SELECT
          idTitle,
          title
        FROM titleList
        WHERE
          idTitle = :idTitle");
      $stmt->bindValue(':idTitle', $idTitle, PDO::PARAM_INT);
      $stmt->execute();
      $title = $stmt->fetchAll(PDO::FETCH_ASSOC);
      foreach ($title as $value) {
        $retTitl = $value['idTitle'].':'.$value['title'];
      }

      return $retTitl;
    } catch (PDOException $e) {
      echo 'Connection failed:'.$e->getMessage();
      die();
    }
  }


  // スレッド閲覧用--clear
  function selThread($idTitle){
    try {
      $dbh = $this->dbconnect();

      if ( isset($_GET['page']) ) {
        $page = (int)$_GET['page'];
      } else {
        $page = 1;
      }

      // スタートのポジションを計算
      if ( $page > 1 ) {
        // 2ページ目
        $start = ($page * 10) - 11;
      } else {
        $start = 0;
      }

      if ( $start == 0) { // 0レス目
        $stmt = $dbh->prepare("
          SELECT
            idTitle,
            idName,
            name,
            trip,
            date,
            email,
            article
          FROM thread
          WHERE
            idTitle = :idT AND
            idName = 1
          ORDER BY idName DESC");
        $stmt->bindValue(':idT', $idTitle, PDO::PARAM_INT);
        $stmt->execute();
        $user = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($user as $value) {
          $this->resDisp($value);
        }

        // 1P
        $stmt = $dbh->prepare("
          SELECT
            idTitle,
            idName,
            name,
            trip,
            date,
            email,
            article,
            down
          FROM thread
          WHERE
            idTitle = :idT AND
            idName != 1
          ORDER BY idName DESC
          LIMIT 0, 9");
        $stmt->bindValue(':idT', $idTitle, PDO::PARAM_INT);
        $stmt->execute();
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($users as $value) {
          $this->resDisp($value);
        }

      } else { // 2p〜

        $stmt = $dbh->prepare("
          SELECT
            idTitle,
            idName,
            name,
            trip,
            date,
            email,
            article,
            down
          FROM thread
          WHERE
            idTitle = :idT AND
            idName != 1
          ORDER BY idName DESC
          LIMIT :start, 10");
        $stmt->bindValue(':idT', $idTitle, PDO::PARAM_INT);
        $stmt->bindParam(':start', $start, PDO::PARAM_INT);
        $stmt->execute();
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($users as $value) {
          $this->resDisp($value);
        }
      }

      $page_num = $dbh->prepare("
        SELECT COUNT(idName)
        FROM thread
        WHERE idTitle = :idT
      ");
      $page_num->bindValue(':idT', $idTitle, PDO::PARAM_INT);
      $page_num->execute();
      $page_num = $page_num->fetchColumn();

      // ページネーション
      return $pagination = ceil($page_num / 10);

    } catch (PDOException $e) {
      echo 'Connection failed:'.$e->getMessage();
      die();
    }
  }

  // レス表示関数
  public function resDisp($value){
    echo '<div class="res"><p><span class="idName">'.$value['idName'].'.</span>';

    if ($value['trip']=="◆xxxxxxxxxx") {
      echo $value['name'].'</p><article>'
      .$value['date'].'&emsp;'.$value['trip']
      .'<div>'
      .$value['article'].'</div></article>'
      .'[削除済み]'
      .'<a href="res_new.php?id='
      .$value['idTitle'].'&idN='.$value['idName'].'">[返信]</a>';
    }else{

      if ($value['email'] && $value['name']) {
        echo '<a href="mailto:'.$value['email'].'">'
        .$value['name'].'</a>';

      } elseif(!$value['email'] && $value['name']){
        echo $value['name'];

      } elseif ($value['email'] && !$value['name']) {
        echo '<a href="mailto:'.$value['email'].'">'
        .$this->noName.'</a>';

      } else { // !$value['email'] && !$value['name']
        echo $this->noName;

      }
      echo '</p><article>'
        .$value['date'].'&emsp;'.$value['trip'].'<div>';
      echo $this->mce($value['article']);
      echo '</div></article>'
        .'<a href="res_del.php?idT='.$value['idTitle']
        .'&idN='.$value['idName'].'">[削除]</a>
        <a href="res_new.php?idT='
        .$value['idTitle'].'&idN='.$value['idName'].'">[返信]</a>';
      if ( ($value['down'] == 1) ) {
        echo '[下げ]';
      }
    }
    echo '</div><hr>';
  }



  // レス用SQL--clear
  public function newRes(&$col){
    try {
      $dbh = $this->dbconnect();
      $resNeId = $this->retResId($col[0]);
      if ($resNeId > 999) {
        header("Location: resMax.html");
        exit;
      }
      $trip = $this->trip();

      if ($col[7] === true) {
        $down = true;
      }else{
        $down = false;
      }

      $stmt = $dbh->prepare("
        INSERT INTO thread (
          `idTitle`,
          `idName`,
          `name`,
          `trip`,
          `email`,
          `date`,
          `password`,
          `article`,
          `down`)
        VALUES (
          :idTitle,
          :idName,
          :name,
          :trip,
          :email,
          :date,
          :password,
          :article,
          :down)");

      $stmt->bindParam( ':idTitle', $col[0], PDO::PARAM_INT);
      $stmt->bindParam( 'idName',$resNeId, PDO::PARAM_INT);
      $stmt->bindParam( ':name', $col[1], PDO::PARAM_STR);
      $stmt->bindParam( ':trip', $col[2], PDO::PARAM_STR);
      $stmt->bindParam( ':email', $col[3], PDO::PARAM_STR);
      $stmt->bindParam( ':date', $col[4], PDO::PARAM_STR);
      $stmt->bindParam( ':password', $col[5], PDO::PARAM_STR);
      $stmt->bindParam( ':article', $col[6], PDO::PARAM_STR);
      $stmt->bindParam( ':down', $down, PDO::PARAM_INT);
      $stmt->execute();

    } catch (PDOException $e) {
      echo 'Connection failed:'.$e->getMessage();
      die();
    }
  }

  // 新規レスid生成--clear
  public function retResId($intId){
    try {
      $dbh = $this->dbconnect();
      $stmt = $dbh->prepare("
        SELECT MAX(idName) +1 FROM thread WHERE idTitle = ?");
      $stmt->bindParam( 1, $intId, PDO::PARAM_INT);
      $stmt->execute();

      $objNeReId = $stmt->fetch();
      $iNeReId = (int)$objNeReId[0];
      return $iNeReId;

    }catch(PDOException $e){
      echo 'Connection failed:'.$e->getMessage();
      die();
    }
  }

  // トリップ作成--clear
  public function trip(){
    $tripkey = $this->getUserIP() . date('Y-m-d'); //日付更新
    $salt = substr($tripkey, 1, 2);
    $salt = preg_replace('/[^\.-z]/', '.', $salt);
    $salt = strtr($salt, ':;<=>?@[\\]^_`', 'ABCDEFGabcdef');
    $trip = crypt($tripkey, $salt);

    $trip = '◆' . $trip;
    return $trip;
  }

  // Function to get the user IP address
  public function getUserIP() {
    $ipaddress = '';
    if (isset($_SERVER['HTTP_CLIENT_IP']))
      $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
      $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    else if(isset($_SERVER['HTTP_X_FORWARDED']))
      $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    else if(isset($_SERVER['HTTP_X_CLUSTER_CLIENT_IP']))
      $ipaddress = $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'];
    else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
      $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    else if(isset($_SERVER['HTTP_FORWARDED']))
      $ipaddress = $_SERVER['HTTP_FORWARDED'];
    else if(isset($_SERVER['REMOTE_ADDR']))
      $ipaddress = $_SERVER['REMOTE_ADDR'];
    else
      $ipaddress = 'UNKNOWN';
    return $ipaddress;
  }

  // resDeleteFunc--clear
  public function resDel(&$col) {
    $delCol[0] = "【蒸発】";
    $delCol[1] = "◆xxxxxxxxxx";
    $delCol[2] = date('Y-m-d h:i:s');
    $delCol[3] = "蒸発確認––––orz";

    $dbh = $this->dbconnect();
    $stmt = $dbh->prepare("
      SELECT password
      FROM `thread`
      WHERE
      `thread`.`idTitle` = :idTitle
      AND `thread`.`idName` = :idName");
    $stmt->bindParam( ':idTitle', $col[0], PDO::PARAM_INT);
    $stmt->bindParam( ':idName', $col[1], PDO::PARAM_INT);
    $stmt->execute();

    $objIdNPass = $stmt->fetch();
    $intIdNPass = (string)$objIdNPass[0];

    if(($col[1] == 1) && ($intIdNPass === $col[2])){
      $stmt = $dbh->prepare("
        DELETE FROM `thread`
        WHERE idTitle = :idTitle");

      $stmt->bindParam( ':idTitle', $col[0], PDO::PARAM_INT);
      $stmt->execute();

      $delTit = $dbh->prepare("
        DELETE FROM `titleList`
        WHERE idTitle = :idTitle");
      $delTit->bindParam( ':idTitle', $col[0], PDO::PARAM_INT);
      $delTit->execute();

      return true;

    } elseif ($intIdNPass === $col[2]) {
      $stmt = $dbh->prepare("
        DELETE FROM `thread`
        WHERE
        `thread`.`idTitle` = :idTitle
        AND `thread`.`idName` = :idName");

      $stmt->bindParam( ':idTitle', $col[0], PDO::PARAM_INT);
      $stmt->bindParam( ':idName', $col[1], PDO::PARAM_INT);
      $stmt->execute();

      $delRes = $dbh->prepare("
        INSERT INTO `thread` (
          `idTitle`,
          `idName`,
          `name`,
          `trip`,
          `date`,
          `article`)
        VALUES (
          :idTitle,
          :idName,
          :name,
          :trip,
          :date,
          :article)");

      $delRes->bindParam( ':idTitle', $col[0], PDO::PARAM_INT);
      $delRes->bindParam( ':idName', $col[1], PDO::PARAM_INT);
      $delRes->bindParam( ':name', $delCol[0], PDO::PARAM_STR);
      $delRes->bindParam( ':trip', $delCol[1], PDO::PARAM_STR);
      $delRes->bindParam( ':date', $delCol[2], PDO::PARAM_STR);
      $delRes->bindParam( ':article', $delCol[3], PDO::PARAM_STR);
      $delRes->execute();

      return true;
    } else {
      return false;
    }
  }


  // thrDeleteFunc--clear
  public function thrDel(&$col) {
    try{
      $dbh = $this->dbconnect();
      $stmt = $dbh->prepare("
        SELECT password
        FROM `thread`
        WHERE
        `thread`.`idTitle` = :idTitle");
      $stmt->bindParam( ':idTitle', $col[0], PDO::PARAM_INT);
      $stmt->execute();
      $objIdPass = $stmt->fetch();

      if($objIdPass[0] === $col[1]){

        $delThr = $dbh->prepare("
          DELETE FROM `thread` WHERE `thread`.`idTitle` = :idTitle
        ");
        $delTit = $dbh->prepare("
          DELETE FROM `titleList` WHERE `titleList`.`idTitle` = :idTitle
        ");
        $delThr->bindParam( ':idTitle', $col[0], PDO::PARAM_INT);
        $delThr->execute();
        $delTit->bindParam( ':idTitle', $col[0], PDO::PARAM_INT);
        $delTit->execute();

        return true;
      } else {
        return false;
      }
    }catch(PDOException $e){
      echo 'Connection failed:'.$e->getMessage();
      die();
    }
  }


  // 検索用
  public function seaRes($col){
    $search = '%'.$col.'%';
    $noCnt = 0;
    $cnt = 0;
    try {
      $dbh = $this->dbconnect();

      if ( isset($_GET['page']) ) {
        $page = (int)$_GET['page'];
      } else {
        $page = 0;
      }

      // スタートのポジションを計算する
      $start = $page * 9;
      $end = $start + 9;

      $stmt = $dbh->prepare("
        SELECT
          `trip`
        FROM `thread`
        WHERE `article`
        LIKE :search");
      $stmt->bindValue(':search', $search, PDO::PARAM_STR);
      $stmt->execute();
      $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
      foreach ($users as $value) {
        if (!($value['trip']=="◆xxxxxxxxxx")) {
          $cnt++;
        }
      }
      echo "<p>検索値：「".$col ."」</p>";
      echo "<p>検索結果：".$cnt ."件みつかりました。</p><hr>";


      $stmt = $dbh->prepare("
        SELECT
          `idTitle`,
          `idName`,
          `name`,
          `trip`,
          `date`,
          `email`,
          `article`
        FROM `thread`
        WHERE `article`
        LIKE :search
        ORDER BY thread.date DESC
        LIMIT :start, :end");
      $stmt->bindValue(':search', $search, PDO::PARAM_STR);
      $stmt->bindParam(':start', $start, PDO::PARAM_INT);
      $stmt->bindParam(':end', $end, PDO::PARAM_INT);
      $stmt->execute();
      $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
      foreach ($users as $value) {
        if (!($value['trip']=="◆xxxxxxxxxx")) {
          echo '<div class="res"><p>'.$value['idTitle'].'.<span class="idName">'.$value['idName'].'.</span>';
          if ($value['name']) {
            echo $value['name'];
          } else {
            echo $this->noName;
          }
          echo '</p><article>'
            .$value['date'].'&emsp;'.$value['trip'].'<div>';
          echo $this->mce($value['article']);
          echo '</div></article><hr></div>';

        }
      }
      // ページネーション
      return $pagination = ceil($cnt / 10);
    } catch (PDOException $e) {
      echo 'Connection failed:'.$e->getMessage();
      die();
    }
  }

  public function hs($str){
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
  }


  public function mce($str){ // タグ挿入MCE

    $serS = array(
      "[red]", "[blu]", "[gre]",
      "[ylw]", "[wht]", "[gry]",
      "[qut]", "[bdo]", "[big]",
      "[sml]", "[hrz]"
    );
    $repS = array(
      "<span class='red'>", "<span class='blu'>", "<span class='gre'>",
      "<span class='ylw'>", "<span class='wht'>", "<span class='gry'>",
      "<q>", "<bdo dir='rtl'>", "<span class='big'>",
      "<span class='sml'>", "<hr>"
    );
    $strIs = str_replace($serS, $repS, $str);

    $serE = array(
      "[/red]", "[/blu]", "[/gre]",
      "[/ylw]", "[/wht]", "[/gry]",
      "[/qut]", "[/bdo]", "[/big]",
      "[/sml]"
    );
    $repE = array(
      "</span>", "</span>", "</span>",
      "</span>", "</span>", "</span>",
      "</q>", "</bdo>", "</span>",
      "</span>"
    );
    $strIe = str_replace($serE, $repE, $strIs);

    while (strpos($strIe, '[a]') !== false) { // a要素のみ別指定、URL・名前同じ
      $posA = strpos($strIe, '[a]');
      $posE = strpos($strIe, '[/a]');
      $urlIt = substr($strIe, $posA, $posE -$posA +4);

      // while ((strpos($urlIt, "<br>") !== false) || (strpos($urlIt, "<br />") !== false)) {
      //   $urlIt = str_replace("\r\n", "", str_replace("<br>", "", $urlIt));
      //   $urlIt = str_replace("\r\n", "", str_replace("<br />", "", $urlIt));
      // }
      $url = substr($urlIt, 3, -4);

      $aTag = "<a href='".$url."'>".$url."</a>";

      $strIa = str_replace("[a]", "<a href=\"".$url."\">", $strIe);
      $strIe = str_replace("[/a]", "</a>", $strIa);
    }
      return $strIe;
  }

}

?>
