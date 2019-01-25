<?php
//設定ファイル読込み
require_once './conf/const.php';
//関数ファイル読込み
require_once './model/func.php';

//セッション開始
session_start();
//セッション名取得
$session_name = session_name();
// セッション変数を全て削除
$_SESSION = array();
// ユーザのCookieに保存されているセッションIDを削除
if (isset($_COOKIE[$session_name])) {
    setcookie($session_name, '', time() - 42000);
}
// セッションIDを無効化
session_destroy();

// loginページへ移動
redirect_login_page();

?>




