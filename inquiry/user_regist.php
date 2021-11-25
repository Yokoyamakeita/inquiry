<?php
// セッション変数の利用を宣言する
session_start();

// 変数を初期化する
$error_message = '';
$name = '';
$mail = '';
$password = '';
$password_confirm = '';


// セッションに値がある場合は取得する
if ( !empty( $_SESSION['error_message'] ) ){
    $error_message = $_SESSION['error_message'];
    // セッション変数を消去する
    unset( $_SESSION['error_message'] );
}

if ( !empty( $_SESSION['name'] ) ){
    $name = $_SESSION['name'];
    // セッション変数を消去する
    unset( $_SESSION['name'] );
}

if ( !empty( $_SESSION['mail'] ) ){
    $mail = $_SESSION['mail'];
    // セッション変数を消去する
    unset( $_SESSION['mail'] );
}

if ( !empty( $_SESSION['password'] ) ){
    // セッション変数を消去する
    unset( $_SESSION['password'] );
}


// テンプレートとなるhtmlファイルを読み込む
$html = file_get_contents( 'user_regist.html' );

// htmlファイルの変更したい部分を変換する
$html = str_replace( '$$$error_message$$$', htmlspecialchars( $error_message ), $html );
$html = str_replace( '$$$name$$$', htmlspecialchars( $name ), $html );
$html = str_replace( '$$$mail$$$', htmlspecialchars( $mail ), $html );

// 変換したhtmlを表示する
print( $html );