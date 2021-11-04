<?php
// セッション変数の利用を宣言する
session_start();

// 変数を初期化する
$login_id = '';
$password = '';

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

// データベースにアクセスして、ログインユーザー一覧を、ログインユーザーIDで検索する
// データベースに接続する
$dbh = new PDO( 'mysql:host=localhost;dbname=inquiry', 'iqadmin', 'password' );
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


// XXX検索結果がある場合は、取得したパスワードと、パスワードハッシュが一致するかを調べる


// 一致したらログイン状態にする。一致しなければエラーなのでログイン画面にリダイレクトする

