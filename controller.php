<?php
ini_set("display_errors", On);
error_reporting(E_ALL);

// 設定ファイル読み込み
require_once './conf/const.php';
// 関数ファイル読み込み
require_once './model/func.php';

$errors = array();
$data   = array();
//$sql_kind = '';
$request_method = get_request_method();
$link = get_db_connect();
$value['id'] = $_POST['id'];


if ($request_method === 'POST'){

  $user_name = get_post_data('user_name');

  // 名前が正しく入力されているかチェック
  $result = check_user_name($user_name);

  if ($result !== true) {
    $errors[] = $result;
  }

  $user_comment = get_post_data('user_comment');

  // ひとことが正しく入力されているかチェック
  $result = check_user_comment($user_comment);

  if ($result !== true) {
    $errors[] = $result;
  }
}

// エラーがなければ保存

var_dump($_POST['delete']);
var_dump($_POST['id']);
var_dump($_POST['submit']);
var_dump($_POST['sql_kind']);
/*if ($request_method === 'POST' && count($errors) === 0) {
  if ($_POST['delete'] === "削除" || $_POST['sql_kind'] === "delete_post") { 
    $id = $_POST['id'];
        try {
          delete_post($link, $id);
          // リロード対策でリダイレクト
          header('Location: http://'. $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
          //header('Location: ./controller.php');
          exit;
        } catch (PDOException $e) {
          $errors[] = '削除失敗。理由'.$e->getMessage();
        }
      }
  
  if (isset($_POST['submit'])){
  try {

    insert_post($link, $user_name, $user_comment);
    // リロード対策でリダイレクト
    header('Location: http://'. $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
    exit;

  } catch (PDOException $e) {
    $errors[] = 'レコード追加失敗。理由'.$e->getMessage();
  }
} 
}
}
}*/

//コメント削除
if ($request_method === 'POST' && count($errors) === 0) {
  if ($_POST['delete'] === "削除" || $_POST['sql_kind'] === "delete_post") { 
    $id = $_POST['id'];
        try {
          delete_post($link, $id);
          // リロード対策でリダイレクト
          header('Location: http://'. $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
          //header('Location: ./controller.php');
          exit;
        } catch (PDOException $e) {
          $errors[] = '削除失敗。理由'.$e->getMessage();
        }
      }
    }

//コメント挿入
/*if (isset($_POST['submit'])){
    try {
  
      insert_post($link, $user_name, $user_comment);
      // リロード対策でリダイレクト
      header('Location: http://'. $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
      exit;
  
    } catch (PDOException $e) {
      $errors[] = 'レコード追加失敗。理由'.$e->getMessage();
    }
  }*/


// 掲示板の書き込み一覧を取得する
$data = get_post_list($link);

// 特殊文字をHTMLエンティティに変換する
$data = entity_assoc_array($data);

// テンプレートファイル読み込み
include_once './view/post_comment.php';
?>