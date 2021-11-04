<?php

// セッション変数の使用を宣言
session_start();

// 利用する変数の初期化
$name ='';
$mail ='';
$password ='';
$password_confirm ='';
$error_message ="";

// エラー判定
if (!empty($_SESSION['error_message'])){
    $error_message = $_SESSION['error_message'];
    unset($_SESSION['error_message']);
}



// 確認画面から戻ってきているのか確認　→入力保持
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


// htmlファイル読み込み
$html = file_get_contents("user_regist.html");

// htmlファイルの変更したい部分を変換する
$html = str_replace( '$$$name$$$', htmlspecialchars( $name ), $html );
$html = str_replace( '$$$mail$$$', htmlspecialchars( $mail ), $html );
$html = str_replace( '$$$error_message$$$', htmlspecialchars( $error_message ), $html );

// 出力
print($html);