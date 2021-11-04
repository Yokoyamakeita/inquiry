<?php
// セッション変数の利用を宣言する
session_start();

// 利用する変数を初期化する
$inquiries_id = '';
$answer = '';

// 入力画面で入力された値を取得する
if ( isset( $_SESSION['id'] ) ) {
    $inquiries_id = $_SESSION['id'];
    // 利用したセッション変数を削除する
    unset( $_SESSION['id'] );
}

if ( isset( $_SESSION['answer'] ) ) {
    $answer = $_SESSION['answer'];
    // 利用したセッション変数を削除する
    unset( $_SESSION['answer'] );
}


// !!! 取得した値をデータベースに登録する
// !!! エラーがでたらエラーメッセージを出力する
$dbh = new PDO( 'mysql:host=localhost;dbname=inquiry', 'iqadmin', 'password' );

// データベースにデータを登録する

try {
    // データベースのエラー発生時に例外を発行するようにする
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // トランザクションを開始する
    $dbh->beginTransaction();
    // 発言一覧IDを取得する    
    // データを取得するSQLを設定する
    $sql = 'SELECT  cm.id AS comments_id 
                FROM inquiries AS iq 
                    INNER JOIN comments AS cm 
                    ON iq.id = cm.inquiries_id 
                WHERE iq.id = :inquiries_id;';

    // データベースに事前にSQLを登録する
    $statement = $dbh->prepare( $sql );

    // データベース変数にphp側の変数を紐づける
    $statement->bindParam( ':inquiries_id', $inquiries_id );

    // SQLを実行する
    $statement->execute();

    // 結果を取得する
    $result = $statement->fetch(PDO::FETCH_ASSOC);

    // 発言IDを取得する
    $comments_id = $result['comments_id'];



// 回答一覧に登録する
    // データを登録するSQLを設定する(回答一覧)
    $sql = 'INSERT INTO answers ( inquiries_id, answer ) VALUES( :inquiries_id, :answer );';

    // データベースに事前にSQLを登録する
    $statement = $dbh->prepare( $sql );

    // データベース変数にphp側の変数を紐づける
    $statement->bindParam( ':inquiries_id', $inquiries_id );
    $statement->bindParam( ':answer', $answer );

    // SQLを実行する（結果は使わない）
    $statement->execute();

    // 今登録した問合せIDを取得する
    $answers_id = $dbh->lastInsertId();


// 発言ー回答　リンクテーブルに登録する
    // データを登録するSQLを設定する(発言ー回答　リンクテーブル)
    $sql = 'INSERT INTO comment_answer_links ( comments_id, answers_id ) VALUES( :comments_id, :answers_id );';

    // データベースに事前にSQLを登録する
    $statement = $dbh->prepare( $sql );

    // データベース変数にphp側の変数を紐づける
    $statement->bindParam( ':comments_id', $comments_id );
    $statement->bindParam( ':answers_id', $answers_id );

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
$html = file_get_contents( 'answer_complete.html' );

// 変換したhtmlを表示する
print( $html );