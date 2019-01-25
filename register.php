<?php

//設定ファイル読込み
require_once './conf/const.php';

//関数ファイル読込み
require_once './model/func.php';


//変数初期化
$result_msg = '';
$err_msg = array();
$dbh = null;

$user_name = '';
$password = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //ユーザ名取得
    $user_name = get_post_data('user_name');

    //ユーザ名チェック
    if (mb_strlen($user_name) < 8) {
        $err_msg[] = 'ユーザー名は8文字以上の文字を入力してください';
    } else if (preg_match('/^[a-zA-Z0-9]+$/', $password) !== 1) {
        $err_msg[] = 'ユーザ名は半角英数字を入力してください';
    }

    // パスワードの取得
    $password = get_post_data('password');

    // パスワードのチェック
    if (mb_strlen($password) < 8){
        $err_msg[] = 'パスワードは8文字以上の文字を入力してください';
    } else if (preg_match('/^[a-zA-Z0-9]+$/', $password) !== 1 ) {
        $err_msg[] = 'パスワードは半角英数字を入力してください';
    }

    try {
        // DBに接続します
        $dbh = get_db_connect(DSN, DB_USER, DB_PASSWD);
    
        // DBを操作します
        if ($dbh) {
    
          // ユーザ情報の存在チェック
          $result = exist_user($dbh, $user_name);
          if ($result === true) {   // 既に同じユーザ名が存在する場合
    
            $err_msg[] = '同じユーザー名が既に登録されています';
    
          } 
          // まだユーザ名が存在する場合
          else {
    
            // 最新の日時を取得します
            $date = date('Y-m-d H:i:s');
            // ユーザ情報を登録する
            insert_user($dbh, $user_name, $password, $date);
            $result_msg = 'アカウント作成を完了しました';
          }
        }
      } catch (PDOException $e) {
        $err_msg[] = 'エラーが発生しました。管理者へお問い合わせください。'.$e->getMessage();
      }
}

// テンプレートファイル読み込み
include_once './view/register_view.php';

?>
