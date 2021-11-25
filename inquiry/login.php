<?php
// セッション変数の利用を宣言する
session_start();

// 変数を初期化する
$error_message = '';
$login_id = '';

// セッション変数があれば、値を取得する
if ( !empty( $_SESSION['error_message'] ) ) {
    $error_message = $_SESSION['error_message'];
    // 使ったセッションをクリアしておく
    unset( $_SESSION['error_message'] );
}

if ( !empty( $_SESSION['login_id'] ) ) {
    $login_id = $_SESSION['login_id'];
    // 使ったセッションをクリアしておく
    unset( $_SESSION['login_id'] );
}


// テンプレートとなるhtmlファイルを読み込む
$html = file_get_contents( 'login.html' );

// htmlファイルの変更したい部分を変換する
$html = str_replace( '$$$error_message$$$', htmlspecialchars( $error_message ), $html );
$html = str_replace( '$$$login_id$$$', htmlspecialchars( $login_id ), $html );

// 変換したhtmlを表示する
print( $html );