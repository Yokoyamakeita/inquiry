<?php

// データベースに接続して、問い合わせのデータを取得する
// データベースに接続する
$dbh = new PDO( 'mysql:host=localhost;dbname=inquiry', 'iqadmin', 'password' );
// データを取得するSQLを設定する
$sql = 'SELECT iq.id AS id,
                iq.name,
                iq.mail,
                cm.comment AS inquiry,
                ans.answer AS answer,
                iq.created_at AS created_at,
                iq.updated_at AS updated_at
        FROM inquiries AS iq
        INNER join
            comments AS cm
            ON iq.id = cm.inquiries_id
        LEFT OUTER JOIN (
            SELECT 
                cal.comments_id,
                cal.answers_id,
                cal.updated_at
            FROM
                comment_answer_links AS cal
                ORDER BY updated_at DESC LIMIT 1
            ) AS cal1
            ON cm.id = cal1.comments_id
        LEFT OUTER JOIN answers AS ans
            ON cal1.answers_id = ans.id;';

// データベースに事前にSQLを登録する /プリペアドステートメント
$statement = $dbh->prepare( $sql );

// SQLを実行する
$statement->execute();

// 結果を取得する
$result = $statement->fetchAll(PDO::FETCH_ASSOC);

//var_dump( $result );

// テンプレートとなるhtmlファイルを読み込む
$html = file_get_contents( 'list.html' );

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
        
        $html_line = str_replace( '$$$answer$$$', htmlspecialchars( $line['answer'] ), $html_line );

        // 真ん中部分のhtmlとして登録する
        $html_middle= $html_middle . $html_line;

    }

    // 作った真ん中の部分と、前後を貼り合わせる
    $html = $html_top[0] . $html_middle . $html_bottom[1];
    
// 変換したhtmlを表示する
print( $html );



        