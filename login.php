<?php
//500エラーが出たら、下記を書いて原因を追求
//ini_set("display_errors", On);
//error_reporting(E_ALL);

//設定ファイル読込み
require_once './conf/const.php';
//関数ファイル読込み
require_once './model/func.php';

//変数初期化
$err_msg = array();

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_name = '';
    $password = '';

    //パラメータ取得
    $user_name = get_post_data('user_name');
    $password = get_post_data('password');

    //パラメータエラーチェック
    if($user_name === '') {
        $err_msg[] = '名前を入力して下さい';
    }
    if($password === '') {
        $err_msg[] = 'パスワードを入力して下さい';
    }

    //DB接続
    try {
        $dbh = get_db_connect(DSN, DB_USER, DB_PASSWD);
    } catch (PDOException $e) {
        $err_msg[] = 'DBに接続できません'.$e->getMessage();
    }

    //DB操作
    if($dbh) {
        //該当ユーザ取得
        $result = get_user($dbh, $user_name, $password);

        if($result) {
            //セッション開始
            session_start();

            //セッション変数に値を保存
            $_SESSION['user_id'] = $result[0]['user_id'];
            $_SESSION['user_name'] = $user_name;

            $url_root = dirname($_SERVER['REQUEST_URI']).'/';
            header('Location: http://'. $_SERVER['HTTP_HOST'] . $url_root . './controller.php');
            //header('Location: http://'. $_SERVER['HTTP_HOST'] . $url_root . './view/post_comment.php');
        } else {
            $err_msg[] = 'ユーザー名あるいはパスワードが違います';
        }
    }

}

// テンプレートファイル読み込み
include_once './view/login_view.php';

?>


