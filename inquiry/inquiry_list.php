<?php
// セッション変数の利用を宣言する
session_start();

// ログイン認証処理 (セッション変数を利用すること）
require_once( 'inc/auth.inc.php' );

// 変数を初期化する
$error_message = '';
$login_users_id = $_SESSION['login_users_id'];

// DB接続処理
require_once( 'inc/db.inc.php' );

try {
    // データベースのエラー発生時に例外を発行するようにする
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 問合せ一覧を取得する
        // データを取得するSQLを設定する(問合せ一覧)
        $sql = 'SELECT 
                    iq.id AS id,  
                    ud.name AS name,  
                    ud.mail AS mail,  
                    cm.comment AS inquiry,
                    iq.created_at AS created_at, 
                    iq.updated_at AS updated_at 
                FROM 
                    inquiries AS iq 
                INNER JOIN 
                    comments AS cm 
                    ON iq.id = cm.inquiries_id
                INNER JOIN 
                    login_users AS lu 
                    ON iq.login_users_id = lu.id
                INNER JOIN 
                    user_details AS ud 
                    ON ud.login_users_id = lu.id
                WHERE
                    iq.login_users_id = :login_users_id;';
    
    // データベースに事前にSQLを登録する
    $statement = $dbh->prepare( $sql );

    // データベース変数にphp側の変数を紐づける
    $statement->bindParam( ':login_users_id', $login_users_id );

    // SQLを実行する（結果は使わない）
    $statement->execute();

    // 結果を取得する
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);

} catch ( Exception $e ) {
    // XXX エラーメッセージを表示する
    $error_message = 'データベースエラー';
    echo $error_message . $e->getMessage();
}


// テンプレートとなるhtmlファイルを読み込む
$html = file_get_contents( 'inquiry_list.html' );

// htmlファイルの変更したい部分を変換する
$html = str_replace( '$$$error_message$$$', htmlspecialchars( $error_message ), $html );


// loop部分を切り出す
    // ###loop###の前後で分ける
    $html_top = explode( '###loop###', $html );

    // 後半部分を###/loop###の前後で分ける
    $html_bottom = explode( '###/loop###', $html_top[1] );

    // 真ん中のリスト部分を作る
    $html_middle = '';

    // データ件数分、リスト部分を作る
    foreach( $result as $line ){

        // 一行分のテンプレートを作る
        $html_line = $html_bottom[0];

        // htmlファイルの変更したい部分を変換する
        $html_line = str_replace( '$$$id$$$', htmlspecialchars( $line['id'] ), $html_line );
        $html_line = str_replace( '$$$name$$$', htmlspecialchars( $line['name'] ), $html_line );
        $html_line = str_replace( '$$$mail$$$', htmlspecialchars( $line['mail'] ), $html_line );
        $html_line = str_replace( '$$$inquiry$$$', htmlspecialchars( $line['inquiry'] ), $html_line );
        $html_line = str_replace( '$$$created_at$$$', htmlspecialchars( $line['created_at'] ), $html_line );
        $html_line = str_replace( '$$$updated_at$$$', htmlspecialchars( $line['updated_at'] ), $html_line );

        // 真ん中部分のhtmlとして登録する
         $html_middle= $html_middle . $html_line;
    }

    // 作った真ん中の部分と、前後を貼り合わせる
    $html = $html_top[0] . $html_middle . $html_bottom[1];

// 変換したhtmlを表示する
print( $html );
