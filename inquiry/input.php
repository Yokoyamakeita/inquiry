<?php
// セッション変数の利用を宣言する
session_start();

// ログイン認証処理 (セッション変数を利用すること）
require_once( 'inc/auth.inc.php' );

//var_dump( $_SESSION );

// 利用する変数を初期化する
$name = '';
$mail = '';
$inquiry = '';
$error_message = '';

// エラーで飛んできたか判定する
if ( isset( $_SESSION['name'] ) ) {
    if ( empty( $_SESSION['name'] ) || empty( $_SESSION['mail'] ) || empty( $_SESSION['inquiry'] ) ) {
        $error_message = '入力されていない項目があります';
    }
    // セッション変数から値を取得する
    $name = $_SESSION['name'];
    // 利用したセッション変数を削除する
    unset( $_SESSION['name'] );

    $mail = $_SESSION['mail'];
    // 利用したセッション変数を削除する
    unset( $_SESSION['mail'] );

    $inquiry = $_SESSION['inquiry'];
    // 利用したセッション変数を削除する
    unset( $_SESSION['inquiry'] );
}

// テンプレートとなるhtmlファイルを読み込む
$html = file_get_contents( 'input.html' );

// htmlファイルの変更したい部分を変換する
$html = str_replace( '$$$error_message$$$', htmlspecialchars( $error_message ), $html );
$html = str_replace( '$$$name$$$', htmlspecialchars( $name ), $html );
$html = str_replace( '$$$mail$$$', htmlspecialchars( $mail ), $html );
$html = str_replace( '$$$inquiry$$$', htmlspecialchars( $inquiry ), $html );

// 変換したhtmlを表示する
print( $html );