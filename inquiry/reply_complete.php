<?php
// セッション変数の利用を宣言する
session_start();

// ログイン認証処理 (セッション変数を利用すること）
require_once( 'inc/auth.inc.php' );


// テンプレートとなるhtmlファイルを読み込む
$html = file_get_contents( 'reply_complete.html' );

// htmlファイルの変更したい部分を変換する

// 変換したhtmlを表示する
print( $html );