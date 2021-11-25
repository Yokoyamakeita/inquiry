<?php
// セッション変数の利用を宣言する
session_start();

// 利用する変数を初期化する
$error_message = '';
$name = '';
$mail = '';
$password = '';

// 入力画面で入力された値を取得する
if ( isset( $_SESSION['name'] ) ) {
    $name = $_SESSION['name'];
    // 利用したセッション変数を削除する
    unset( $_SESSION['name'] );
}

if ( isset( $_SESSION['mail'] ) ) {
    $mail = $_SESSION['mail'];
    // 利用したセッション変数を削除する
    unset( $_SESSION['mail'] );
}

if ( isset( $_SESSION['password'] ) ) {
    $password = $_SESSION['password'];
    // 利用したセッション変数を削除する
    unset( $_SESSION['password'] );
}


// 入力されたパスワードからパスワードハッシュを作る
$password_hash = password_hash( $password, PASSWORD_DEFAULT );


// !!! 取得した値をデータベースに登録する
// !!! エラーがでたらエラーメッセージを出力する
// DB接続処理
require_once( 'inc/db.inc.php' );

// データベースにデータを登録する

try {
    // データベースのエラー発生時に例外を発行するようにする
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // トランザクションを開始する
    $dbh->beginTransaction();

// ログインユーザー一覧に登録する
    // データを登録するSQLを設定する(ログインユーザー一覧)
    $sql = 'INSERT INTO login_users ( login_id, password ) VALUES( :login_id, :password );';

    // データベースに事前にSQLを登録する
    $statement = $dbh->prepare( $sql );

    // データベース変数にphp側の変数を紐づける
    $statement->bindParam( ':login_id', $mail );
    $statement->bindParam( ':password', $password_hash );

    // SQLを実行する（結果は使わない）
    $statement->execute();

    // 今登録したログインユーザーIDを取得する
    $login_users_id = $dbh->lastInsertId();


// ログインユーザー詳細に登録する
    // データを登録するSQLを設定する(ログインユーザー詳細)
    $sql = 'INSERT INTO user_details 
                    ( login_users_id, name, mail ) 
                VALUES( :login_users_id, :name, :mail );';

    // データベースに事前にSQLを登録する
    $statement = $dbh->prepare( $sql );

    // データベース変数にphp側の変数を紐づける
    $statement->bindParam( ':login_users_id', $login_users_id );
    $statement->bindParam( ':name', $name );
    $statement->bindParam( ':mail', $mail );

    // SQLを実行する（結果は使わない）
    $statement->execute();

// データベースにコミットする
    $dbh->commit();    

} catch ( Exception $e ) {
    // データベースエラーなので、ロールバックする
    $dbh->rollback();
    // XXX エラーメッセージを表示する
    echo "データベースエラー" . $e->getMessage();
}


// テンプレートとなるhtmlファイルを読み込む
$html = file_get_contents( 'user_regist_complete.html' );

// 変換したhtmlを表示する
print( $html );