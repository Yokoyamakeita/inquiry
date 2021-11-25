<?php
// セッション変数の利用を宣言する
session_start();

// 変数を初期化する
$login_id = '';
$password = '';
$onetime_key = '';

// httpパラメーター（GET?）から、ログインユーザーIDとパスワードを取得する
if ( empty( $_GET['login_id']) || empty( $_GET['password'] ) ){
    $error_message = 'ログインIDかパスワードが正しくありません。';
    $_SESSION['error_message'] = $error_message;
    // ログインページにリダイレクトする
    header( 'Locatio: login.php');
    exit;
}

$login_id = $_GET['login_id'];
$password = $_GET['password'];

// セッション変数に保存しとく
$_SESSION['login_id'] = $login_id;


// データベースにアクセスして、ログインユーザー一覧を、ログインユーザーIDで検索する
// DB接続処理
require_once( 'inc/db.inc.php' );
// データを取得するSQLを設定する
$sql = 'SELECT  id, 
                login_id, 
                password 
            FROM login_users 
            WHERE login_id = :login_id;';

// データベースに事前にSQLを登録する
$statement = $dbh->prepare( $sql );

// データベース変数にphp側の変数を紐づける
$statement->bindParam( ':login_id', $login_id );

// SQLを実行する
$statement->execute();

// 結果を取得する
$result = $statement->fetch(PDO::FETCH_ASSOC);


// 検索結果がある場合は、取得したパスワードと、パスワードハッシュが一致するかを調べる
if ( !empty( $result ) ) {
    if ( password_verify( $password, $result['password'] ) ) {

        // 一致する場合はログイン状態にする

        // XXX 一意のワンタイムキーを設定する
        $onetime_key = uniqid(dechex(random_int(0, 255)));

        // 有効期限を設定する（分単位：本当なら設定ファイルに書く！）
        $expire_period = 3;

        // データベースに接続する
        require_once('inc/db.inc.php');

        // データベースにデータを登録する
        try {
            // データベースのエラー発生時に例外を発行するようにする
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // ログインセッション一覧に登録する
            // データを登録するSQLを設定する(ログインセッション一覧)
            $sql = 'INSERT INTO login_sessions 
                              ( login_users_id, 
                                onetime_key, 
                                expire_datetime ) 
                        VALUES( :login_users_id, 
                                :onetime_key, 
                                NOW() + INTERVAL :expire_period MINUTE );';
        
            // データベースに事前にSQLを登録する
            $statement = $dbh->prepare( $sql );
        
            // データベース変数にphp側の変数を紐づける
            $statement->bindParam( ':login_users_id', $result['id'] );
            $statement->bindParam( ':onetime_key', $onetime_key );
            $statement->bindParam( ':expire_period', $expire_period );
        
            // SQLを実行する（結果は使わない）
            $statement->execute();
        } catch ( Exception $e ) {
            // XXX エラーメッセージを表示する
            echo "データベースエラー" . $e->getMessage();
        }

        // セッションにワンタイムキーを設定する
        $_SESSION['onetime_key'] = $onetime_key;

        // トップページにリダイレクトする
        header( 'Location: index.php' );
        exit;
    }
}

// エラーなのでログイン画面にリダイレクトする
$error_message = 'ログインIDかパスワードが正しくありません。';
$_SESSION['error_message'] = $error_message;

header( 'Location: login.php' );
exit;

