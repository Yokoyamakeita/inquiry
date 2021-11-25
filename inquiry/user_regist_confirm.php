<?php
// セッション変数の利用を宣言する
session_start();

// 変数を初期化する
$error_message = '';
$name = '';
$mail = '';
$password = '';
$password_confirm = '';


// GETパラメータ取得する
$name = $_GET["name"];
$mail = $_GET["mail"];
$password = $_GET["password"];
$password_confirm = $_GET["password_confirm"];


// 取得したパラメータをセッションに保存する
$_SESSION['name'] = $name;
$_SESSION['mail'] = $mail;
$_SESSION['password'] = $password;


// !!! 入力値のチェックはここでする
if ( empty( $name ) || empty( $mail ) || empty( $password ) || empty( $password_confirm ) ) {
    // エラーメッセージを設定する
    $error_message .= '必要な項目が入力されていません。';
}

if ( strcmp( $password, $password_confirm ) !== 0 ) {
    $error_message .= 'パスワードが一致しません。';
}

if ( !empty( $error_message ) ){
    // セッションにエラーメッセージを登録する
    $_SESSION['error_message'] = $error_message;
    // !!! 入力値でエラーが出る場合は、ここで入力画面に戻る
    header( 'Location: user_regist.php' );
    exit;
}


// テンプレートとなるhtmlファイルを読み込む
$html = file_get_contents( 'user_regist_confirm.html' );

// htmlファイルの変更したい部分を変換する
$html = str_replace( '$$$error_message$$$', htmlspecialchars( $error_message ), $html );
$html = str_replace( '$$$name$$$', htmlspecialchars( $name ), $html );
$html = str_replace( '$$$mail$$$', htmlspecialchars( $mail ), $html );

// 変換したhtmlを表示する
print( $html );