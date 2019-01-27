<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <title>ユーザ登録ページ</title>
  <link type="text/css" rel="stylesheet" href="./css/common.css">
</head>
<body>
  <header>
    <div class="header-box">
      <a href="./top.php">
        <img class="logo" src="./images/logo.png" alt="掲示板">
      </a>
      <a href="./cart.php" class="cart"></a>
    </div>
  </header>
  <div class="content">
    <div class="register">
      <form method="post" action="./register.php">
        <div>ユーザー名：<input type="text" name="user_name" placeholder="ユーザー名"></div>
        <div>パスワード：<input type="password" name="password" placeholder="パスワード">
        <div><input type="submit" value="ユーザーを新規作成する">
<?php foreach ($err_msg as $value) { ?>
        <p class="err-msg"><?php print $value; ?></p>
<?php } ?>
      </form>
<?php if (empty($result_msg) !== TRUE) { ?>
      <p class="success-msg"><?php print $result_msg; ?></p>
      <div class="login-link"><a href="./login.php">ログインページに移動する</a></div>
<?php } ?>
    </div>
  </div>
</body>
</html>