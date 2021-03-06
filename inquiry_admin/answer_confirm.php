<?php
// セッション変数の利用を宣言する
session_start();

// 変数を初期化する
$error_message = '';
$answer = '';
$id = '';


// GETパラメータ取得する
$answer = $_GET["answer"];

// セッションより該当データのIDを取得する
$id = $_SESSION["id"];

// 取得したパラメータをセッションに保存する
$_SESSION['answer'] = $answer;

// !!! 入力値のチェックはここでする
if ( empty( $answer ) ) {
    // !!! 入力値でエラーが出る場合は、ここで入力画面に戻る
    header( 'Location: detail.php' );
    exit;
}

// データベースに接続して、問い合わせのデータを取得する
// データベースに接続する
require_once( '../inquiry/inc/db.inc.php' );
// データを登録するSQLを設定する
try {
    // データベースのエラー発生時に例外を発行するようにする
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // データを登録するSQLを設定する
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
$html = file_get_contents( 'answer_confirm.html' );

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