<?php
ini_set("display_errors", On);
error_reporting(E_ALL);

// 設定ファイル読み込み
require_once './conf/const.php';
// 関数ファイル読み込み
require_once './model/func.php';

$errors = array();
$data   = array();
$sql_kind = '';
$request_method = get_request_method();
$link = get_db_connect();
$img_dir    = './img/';
$img = '';


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

// アップロード画像ファイルの保存
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // HTTP POST でファイルがアップロードされたかどうかチェック
  if (is_uploaded_file($_FILES['img']['tmp_name']) === TRUE) {
    // 画像の拡張子を取得
    $extension = pathinfo($_FILES['img']['name'], PATHINFO_EXTENSION);
    // 指定の拡張子であるかどうかチェック
    if ($extension === 'jpg' || $extension === 'jpeg') {
      // 保存する新しいファイル名の生成（ユニークな値を設定する）
      $img = sha1(uniqid(mt_rand(), true)). '.' . $extension;
      // 同名ファイルが存在するかどうかチェック
      if (is_file($img_dir . $img) !== TRUE) {
        // アップロードされたファイルを指定ディレクトリに移動して保存
        if (move_uploaded_file($_FILES['img']['tmp_name'], $img_dir . $img) !== TRUE) {
            $err_msg[] = 'ファイルアップロードに失敗しました';
        }
      } else {
        $err_msg[] = 'ファイルアップロードに失敗しました。再度お試しください。';
      }
    } else {
      $err_msg[] = 'ファイル形式が異なります。画像ファイルはJPEGのみ利用可能です。';
    }
  } else {
    $err_msg[] = 'ファイルを選択してください';
  }
}


//コメント挿入&削除
if (isset($_POST['submit'])){
    try {
  
      insert_post($link, $user_name, $user_comment, $img);
      // リロード対策でリダイレクト
      header('Location: http://'. $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
      exit;
  
    } catch (PDOException $e) {
      $errors[] = 'レコード追加失敗。理由'.$e->getMessage();
  }
} else if ($request_method === 'POST') {
  $sql_kind = get_post_data('sql_kind');
  //$id = $_POST['id'];
  $id = get_post_data('id');
  if ($sql_kind === 'delete_post') {
    try {
      delete_post($link, $id);
      // リロード対策でリダイレクト
      header('Location: http://'. $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
      exit;
    } catch (PDOException $e) {
      $errors[] = '削除失敗。理由'.$e->getMessage();
    }
  }
}


// 掲示板の書き込み一覧を取得する
$data = get_post_list($link);

// 特殊文字をHTMLエンティティに変換する
$data = entity_assoc_array($data);

// テンプレートファイル読み込み
include_once './view/post_comment.php';
?>