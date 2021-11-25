<?php
// セッション変数の利用を宣言する
session_start();

// ログイン認証処理 (セッション変数を利用すること）
require_once( 'inc/auth.inc.php' );

// ログアウト処理

// 有効期限内のデータがユーザーセッション一覧に残っていたら消しておく
// DB接続処理
require_once( 'inc/db.inc.php' );

// データを削除するSQLを設定する
$sql = 'DELETE FROM login_sessions 
            WHERE onetime_key = :onetime_key;';

// データベースに事前にSQLを登録する
$statement = $dbh->prepare( $sql );

// データベース変数にphp側の変数を紐づける
$statement->bindParam( ':onetime_key', $onetime_key );

// SQLを実行する
$statement->execute();


// メッセージの設定
$error_message = 'ログアウトしました';
// セッション変数をクリアしておく
unset( $_SESSION['login_id'] );
unset( $_SESSION['onetime_key'] );
// ログアウトメッセージを登録しておく
$_SESSION['error_message'] = $error_message;
// ログインページにリダイレクトする
header( 'Location: login.php' );
exit;