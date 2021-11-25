<?php
// セッション変数の利用を宣言する
session_start();

// ログイン認証処理 (セッション変数を利用すること）
require_once( 'inc/auth.inc.php' );

// 利用する変数を初期化する
$name = '';
$mail = '';
$inquiry = '';

// 入力画面で入力された値を取得する
if ( isset( $_SESSION['name'] ) ) {
    $name = $_SESSION['name'];
    // 利用したセッション変数を削除する
    unset( $_SESSION['name'] );
}

if ( isset( $_SESSION['mail'] ) ) {
    $mail = $_SESSION['mail'];
    // 利用したセッション変数を削除する
    unset( $_SESSION['mail'] );
}

if ( isset( $_SESSION['inquiry'] ) ) {
    $inquiry = $_SESSION['inquiry'];
    // 利用したセッション変数を削除する
    unset( $_SESSION['inquiry'] );
}

// !!! 取得した値をデータベースに登録する
// !!! エラーがでたらエラーメッセージを出力する

// DB接続処理
require_once( 'inc/db.inc.php' );

// データベースにデータを登録する

try {
    // データベースのエラー発生時に例外を発行するようにする
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // トランザクションを開始する
    $dbh->beginTransaction();

// 問合せ一覧に登録する
    // データを登録するSQLを設定する(問合せ一覧)
    $sql = 'INSERT INTO inquiries ( name, mail ) VALUES( :name, :mail );';

    // データベースに事前にSQLを登録する
    $statement = $dbh->prepare( $sql );

    // データベース変数にphp側の変数を紐づける
    $statement->bindParam( ':name', $name );
    $statement->bindParam( ':mail', $mail );

    // SQLを実行する（結果は使わない）
    $statement->execute();

    // 今登録した問合せIDを取得する
    $inquiries_id = $dbh->lastInsertId();


// ユーザー発言一覧に登録する
    // データを登録するSQLを設定する(ユーザー発言一覧)
    $sql = 'INSERT INTO comments ( inquiries_id, comment ) VALUES( :inquiries_id, :comment );';

    // データベースに事前にSQLを登録する
    $statement = $dbh->prepare( $sql );

    // データベース変数にphp側の変数を紐づける
    $statement->bindParam( ':inquiries_id', $inquiries_id );
    $statement->bindParam( ':comment', $inquiry );

    // SQLを実行する（結果は使わない）
    $statement->execute();

// データベースにコミットする
    $dbh->commit();    

} catch ( Exception $e ) {
    // データベースエラーなので、ロールバックする
    $dbh->rollback();
    // XXX エラーメッセージを表示する
    echo "データベースエラー" . $e->getMessage();
}



// テンプレートとなるhtmlファイルを読み込む
$html = file_get_contents( 'complete.html' );

// 変換したhtmlを表示する
print( $html );