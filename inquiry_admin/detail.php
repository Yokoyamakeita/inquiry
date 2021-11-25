<?php

// セッション変数の利用を宣言する
session_start();

// 変数を初期化する
$error_message = '';
$id = '';
$answer = '';

// IDを受け取る
if ( !isset( $_GET["id"] ) ) {
    if ( !isset( $_SESSION["id"] ) ) {
        // GETパラメータにもSESSION変数にもIDがなければエラー
        header( 'Location: list.php' );
        exit;
    } else {
        // セッション変数からIDを取得する
        $id = $_SESSION["id"];
    }
} else {
    // GETパラメーターからIDを取得する
    $id = $_GET["id"];
    // idをセッション変数に登録する
    $_SESSION['id'] = $id;
}


// 確認画面から戻ってきた場合に、セッション変数から回答を受け取る
if ( isset( $_SESSION["answer"] ) ) {
    $answer = $_SESSION["answer"];
    // 使わないセッション変数は消しておく
    unset( $_SESSION["answer"] );
}



// データベースに接続して、問い合わせのデータを取得する
// DB接続処理
require_once( '../inquiry/inc/db.inc.php' );

try {
    // データベースのエラー発生時に例外を発行するようにする
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // データを取得するSQLを設定する
    $sql = 'SELECT  iq.id AS id, 
                    ud.name AS name,  
                    ud.mail AS mail,  
                    comment AS inquiry,
                    iq.created_at AS created_at, 
                    iq.updated_at AS updated_at 
                FROM inquiries AS iq 
                    INNER JOIN comments AS cm 
                        ON iq.id = cm.inquiries_id 
                    INNER JOIN 
                        login_users AS lu 
                        ON iq.login_users_id = lu.id
                    INNER JOIN 
                        user_details AS ud 
                        ON ud.login_users_id = lu.id

                WHERE iq.id = :id;';

    // データベースに事前にSQLを登録する
    $statement = $dbh->prepare( $sql );

    // データベース変数にphp側の変数を紐づける
    $statement->bindParam( ':id', $id );

    // SQLを実行する
    $statement->execute();

    // 結果を取得する
    $result = $statement->fetch(PDO::FETCH_ASSOC);

} catch ( Exception $e ) {
    // XXX エラーメッセージを表示する
    $error_message = 'データベースエラー';
    echo $error_message . $e->getMessage();
}


// テンプレートとなるhtmlファイルを読み込む
$html = file_get_contents( 'detail.html' );

// htmlファイルの変更したい部分を変換する
$html = str_replace( '$$$error_message$$$', htmlspecialchars( $error_message ), $html );
$html = str_replace( '$$$name$$$', htmlspecialchars( $result['name'] ), $html );
$html = str_replace( '$$$mail$$$', htmlspecialchars( $result['mail'] ), $html );
$html = str_replace( '$$$inquiry$$$', htmlspecialchars( $result['inquiry'] ), $html );
$html = str_replace( '$$$created_at$$$', htmlspecialchars( $result['created_at'] ), $html );
$html = str_replace( '$$$updated_at$$$', htmlspecialchars( $result['updated_at'] ), $html );

$html = str_replace( '$$$answer$$$', htmlspecialchars( $answer ), $html );



// 変換したhtmlを表示する
print( $html );