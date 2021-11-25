<?php
// セッション変数の利用を宣言する
session_start();

// ログイン認証処理 (セッション変数を利用すること）
require_once( 'inc/auth.inc.php' );

// GETパラメータ取得する
$name = $_GET["name"];
$mail = $_GET["mail"];
$inquiry = $_GET["inquiry"];

// 取得したパラメータをセッションに保存する
$_SESSION['name'] = $name;
$_SESSION['mail'] = $mail;
$_SESSION['inquiry'] = $inquiry;

// !!! 入力値のチェックはここでする
if ( empty( $name ) || empty( $mail ) || empty( $inquiry ) ) {
    // !!! 入力値でエラーが出る場合は、ここで入力画面に戻る
    header( 'Location: input.php' );
    exit;
}


// テンプレートとなるhtmlファイルを読み込む
$html = file_get_contents( 'confirm.html' );

// htmlファイルの変更したい部分を変換する
$html = str_replace( '$$$name$$$', htmlspecialchars( $name ), $html );
$html = str_replace( '$$$mail$$$', htmlspecialchars( $mail ), $html );
$html = str_replace( '$$$inquiry$$$', htmlspecialchars( $inquiry ), $html );

// 変換したhtmlを表示する
print( $html );