<?php
//アプリの全ての関数をここに書く
//ログイン機能（ユーザ情報の取得）
function get_user($dbh, $user_name, $password){
    try {
        //SQL文作成
        $sql = 'SELECT * FROM user WHERE user_name = ? AND password = ?';
        //SQL文実行準備
        $stmt = $dbh->prepare($sql);
        //バインド
        $stmt->bindValue(1, $user_name, PDO::PARAM_STR);
        $stmt->bindValue(2, $password, PDO::PARAM_STR);
        // SQLを実行
        $stmt->execute();
        // レコードの取得
        $rows = $stmt->fetchAll();
    } catch (PDOException $e){
        throw $e;
    }
    return $rows;
}

//ログインチェック
function check_user_login($ulr_root = "") {
    if (isset($_SESSION['user_name']) !== TRUE){
        //loginページへリダイレクト
        redirect_login_page();
    }
}

//loginページへリダイレクト
function redirect_login_page() {
    $url_root = dirname($_SERVER["REQUEST_URI"]).'/';
     header('Location: '.(empty($_SERVER["HTTPS"]) ? "http://" : "https://"). $_SERVER['HTTP_HOST'] . $url_root . 'login.php');
     exit();
  }

// ユーザ情報の一覧を取得する(admin_user_model.phpより)

//ユーザ情報の存在チェック
function exist_user($dbh, $user_name) {
    $exist_flag = false;
    try {
        // SQL文を作成
        $sql = 'SELECT *
            FROM user
            WHERE user_name = ?';
        // SQL文を実行する準備
        $stmt = $dbh->prepare($sql);
        // SQL文のプレースホルダに値をバインド
        $stmt->bindValue(1, $user_name, PDO::PARAM_STR);
        // SQLを実行
        $stmt->execute();
        // レコードの取得
        $rows = $stmt->fetchAll();
    
        if (count($rows) === 1) {
         $exist_flag = true;
        }
    
      } catch (PDOException $e) {
        throw $e;
      }
    
      return $exist_flag;
    }

// ユーザ情報を登録する
function insert_user($dbh, $user_name, $password, $date) {

    try {
      // SQL文を作成
      $sql = 'INSERT INTO user (user_name, password, create_datetime, update_datetime) VALUES (?, ?, ?, ?)';
      // SQL文を実行する準備
      $stmt = $dbh->prepare($sql);
      // SQL文のプレースホルダに値をバインド
      $stmt->bindValue(1, $user_name, PDO::PARAM_STR);
      $stmt->bindValue(2, $password, PDO::PARAM_STR);
      $stmt->bindValue(3, $date, PDO::PARAM_STR);
      $stmt->bindValue(4, $date, PDO::PARAM_STR);
      // SQLを実行
      $stmt->execute();
  
    } catch (PDOException $e) {
      throw $e;
    }
  
  }

// 特殊文字をHTMLエンティティに変換する
function entity_str($str) {
    return htmlspecialchars($str, ENT_QUOTES, HTML_CHARACTER_SET);
}

// 特殊文字をHTMLエンティティに変換する(2次元配列の値)
function entity_assoc_array($assoc_array) {
    foreach ($assoc_array as $key => $value) {
        foreach ($value as $keys => $values) {
            $assoc_array[$key][$keys] = entity_str($values);
        }
    }
    return $assoc_array;
}

// 数値かどうかチェック
function check_number($number) {
    if (preg_match('/\A\d+\z/', $number) === 1) {
        return true;
    } else {
        return false;
    }
}
// POSTデータを取得 
function get_post_data($key) {
    $str = '';
    if (isset($_POST[$key]) === TRUE) {
        $str = $_POST[$key];
    }
    return $str;
}

// 前後の空白を削除
function trim_space($str) {
    return preg_replace('/\A[　\s]*|[　\s]*\z/u', '', $str);
}

// DBに接続
function get_db_connect() {
    $dbh = null;
    try {
        // データベースに接続
        $dbh = new PDO(DNS, DB_USER, DB_PASSWD);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    } catch (PDOException $e) {
        throw $e;
    }
    return $dbh;
}

//クエリを実行しその結果を配列で取得する
function get_as_array($dbh, $sql) {

    try {
      // SQL文を実行する準備
      $stmt = $dbh->prepare($sql);
      // SQLを実行
      $stmt->execute();
      // レコードの取得
      $rows = $stmt->fetchAll();
    } catch (PDOException $e) {
     echo '接続できませんでした。理由：'.$e->getMessage();
    }
  
    return $rows;
  }

//ひとことが正しく入力されているかチェック
function check_user_comment($user_comment){
    if (mb_strlen($user_comment) === 0){
        return 'ひとことを入力してください';
    } elseif (mb_strlen($user_comment) > 100){
        return 'ひとことは100文字以内で入力してください';
    } else {
        return true;
    }
}

//掲示板へ書き込みを追加する
function insert_post($dbh, $user_name, $user_comment) {
    try {
      // SQL生成
      $sql = 'INSERT INTO post(user_name, user_comment) VALUES(?, ?)';
      // SQL文を実行する準備
      $stmt = $dbh->prepare($sql);
      // SQL文のプレースホルダに値をバインド
      $stmt->bindValue(1, $user_name,    PDO::PARAM_STR);
      $stmt->bindValue(2, $user_comment, PDO::PARAM_STR);
      // SQLを実行
      $stmt->execute();
      // レコードの取得
      $rows = $stmt->fetchAll();
  
    } catch (PDOException $e) {
      throw $e;
    } 
  }

//掲示板の書き込み一覧を取得する(要修正)
function get_post_list($link) {
    // SQL生成
    $sql = 'SELECT user_name, user_comment FROM post order by user_name asc ';
    // クエリ実行
    return get_as_array($link, $sql);
  }
//リクエストメソッドを取得
function get_request_method() {
    return $_SERVER['REQUEST_METHOD'];
  }

//名前が正しく入力されているかチェック
function check_user_name($user_name) {

    if (mb_strlen($user_name) === 0){
      return '名前を入力してください';
  
    } elseif (mb_strlen($user_name) > 20){
      return '名前は20文字以内で入力してください';
  
    } else {
      return true;
    }
  }

//コメント削除
function delete_post($dbh, $id) {
    try {
        // SQL文を作成
        $sql = 'DELETE FROM post WHERE id = ?';
        // SQL文を実行する準備
        $stmt = $dbh->prepare($sql);
        // SQL文のプレースホルダに値をバインド
        $stmt->bindValue(1, $id, PDO::PARAM_INT);
        // SQLを実行
        $stmt->execute();
    } catch (PDOException $e) {
        throw $e;
    }
}
?>