<?php
// セッション変数の利用を宣言する
session_start();

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
$dbh = new PDO( 'mysql:host=localhost;dbname=inquiry', 'iqadmin', 'password' );
// データを登録するSQLを設定する
$sql = 'SELECT  iq.id AS id, 
                name, 
                mail, 
                comment AS inquiry,
                iq.created_at AS created_at, 
                iq.updated_at AS updated_at 
            FROM inquiries AS iq 
                INNER JOIN comments AS cm 
                ON iq.id = cm.inquiries_id 
            WHERE iq.id = :id;';

// データベースに事前にSQLを登録する
$statement = $dbh->prepare( $sql );

// データベース変数にphp側の変数を紐づける
$statement->bindParam( ':id', $id );

// SQLを実行する
$statement->execute();

// 結果を取得する
$result = $statement->fetch(PDO::FETCH_ASSOC);


// テンプレートとなるhtmlファイルを読み込む
$html = file_get_contents( 'answer_confirm.html' );

// htmlファイルの変更したい部分を変換する
$html = str_replace( '$$$name$$$', htmlspecialchars( $result['name'] ), $html );
$html = str_replace( '$$$mail$$$', htmlspecialchars( $result['mail'] ), $html );
$html = str_replace( '$$$inquiry$$$', htmlspecialchars( $result['inquiry'] ), $html );
$html = str_replace( '$$$created_at$$$', htmlspecialchars( $result['created_at'] ), $html );
$html = str_replace( '$$$updated_at$$$', htmlspecialchars( $result['updated_at'] ), $html );

$html = str_replace( '$$$answer$$$', htmlspecialchars( $answer ), $html );


// 変換したhtmlを表示する
print( $html );