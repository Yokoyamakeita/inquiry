<?php
// セッション変数の利用を宣言する
session_start();

// 変数の初期化
$error_message = '';
$name = "";
$mail = "";
$password  ="";
$password_confirm ="";

// GETパラメータ取得する
$name = $_GET["name"];
$mail = $_GET["mail"];
$password = $_GET["password"];
$password_confirm = $_GET["password_confirm"];


// 取得したパラメータをセッションに保存する
$_SESSION['name'] = $name;
$_SESSION['mail'] = $mail;
$_SESSION['password'] = $password;

// !!! 入力値のチェック
if ( empty( $name ) || empty( $mail ) || empty( $password )|| empty($password_confirm) ) {
    $error_message .= "必要な項目が入力されていません";
}

// 文字列の比較　strcmp 0/一致　1/一致していない
if(strcmp($password,$password_confirm)!== 0){
    $error_message .= "パスワードが一致していません";
}

if(!empty($error_message)){
    // セッションにエラーメッセージを登録
    $_SESSION['error_message'] = $error_message;
    // さらに入力画面に戻す
    header('Location: user_regist.php');
    exit;
}

// テンプレートとなるhtmlファイルを読み込む
$html = file_get_contents( 'user_regist_confirm.html' );

$html = str_replace( '$$$name$$$', htmlspecialchars( $name ), $html );
$html = str_replace( '$$$mail$$$', htmlspecialchars( $mail ), $html );
$html = str_replace( '$$$error_message$$$', htmlspecialchars( $error_message ), $html );


// 変換したhtmlを表示する
print( $html );