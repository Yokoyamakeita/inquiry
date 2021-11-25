<?php
// セッション変数の利用を宣言する
session_start();

// ログイン認証処理 vvvvvvv
require_once( 'inc/auth.inc.php');
// ログイン認証処理 ^^^^^^^^

// 変数を初期化する
$error_message = '';
$id = '';
$reply = '';

// エラーメッセージがセッションにある場合は取り込んでおく
if ( !empty( $_SESSION['error_message'] ) ) {
    $error_message = $_SESSION['error_message'];
    // 使ったセッションはクリアする
    unset( $_SESSION['error_message'] );
}

// セッション変数が登録されていたら、画面に表示する準備をする
if ( empty( $_SESSION['id']) ){
    // XXX idが空の場合はエラー（どこから来たか解らない）ので、リストに戻る
    header( 'Location: inquiry_list.php' );
    exit;
} 

// セッションから該当の問合せIDを取得する
$id = $_SESSION['id'];
// 利用したセッション変数は削除する(編集で戻る場合のため消してはいけない！)
//unset( $_SESSION['id'] );

// GETパラメーターから返答を取得する
if ( isset( $_GET['reply'] ) ){
    $reply = $_GET['reply'];
} else {
    // XXX 返答が空の場合はエラー（どこから来たか解らない）ので、リストに戻る
    header( 'Location: inquiry_list.php' );
    exit;
}

// データベースから表示するデータを取得する
// (エラー処理があれば、する)

// データベース接続処理 vvvvvvvv
require_once( 'inc/db.inc.php');
// データベース接続処理 ^^^^^^^^

try{
    // 例外処理の事前処理として、データベースでエラーが起こったら例外を発行するようにする
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// inquiriesの検索
    // SQLを設定する
    $sql = 'SELECT 
                iq.id AS id,
                iq.login_users_id AS login_users_id,
                ud.name AS name,
                ud.mail AS mail,
                uc.id AS user_comments_id,
                uc.comment AS inquiry,
                iq.created_at AS created_at,
                iq.updated_at AS updated_at
            FROM 
                inquiries AS iq
                INNER JOIN
                    user_comments AS uc
                ON
                    iq.id = uc.inquiries_id 
                INNER JOIN
                    login_users AS lu 
                ON
                    iq.login_users_id = lu.id 
                INNER JOIN
                    user_details as ud 
                ON
                    lu.id = ud.login_users_id 
            WHERE iq.id = :id;';

    // プリペアードステートメントとして登録しておく
    $statement = $dbh->prepare( $sql );
    // 使用する変数をバインドする
    $statement->bindParam( ':id', $id );

    // SQLを実行させる
    $statement->execute();

    // 結果セットを取得する
    $result = $statement->fetch(PDO::FETCH_ASSOC);

} catch (Exception $e) {
    echo "失敗しました。" . $e->getMessage();
}

// 完了画面に渡すセッション変数を登録する
$_SESSION['inquiries_id'] = $id;                                // お問い合わせID
$_SESSION['answers_id'] = $result['answers_id'];                // 回答ID
$_SESSION['reply'] = $reply;                                    // 返答


// 該当するHTMLテンプレートを読み込む
$html = file_get_contents( 'reply_confirm.html' );

// テンプレートの穴あけ部分を埋める
$html = str_replace( '$$$error_message$$$', htmlspecialchars($error_message), $html );
$html = str_replace( '$$$id$$$', htmlspecialchars($result['id']), $html );
$html = str_replace( '$$$name$$$', htmlspecialchars($result['name']), $html );
$html = str_replace( '$$$mail$$$', htmlspecialchars($result['mail']), $html );
$html = str_replace( '$$$inquiry$$$', htmlspecialchars($result['inquiry']), $html );
$html = str_replace( '$$$created_at$$$', htmlspecialchars($result['created_at']), $html );
$html = str_replace( '$$$updated_at$$$', htmlspecialchars($result['updated_at']), $html );
$html = str_replace( '$$$reply$$$', htmlspecialchars($reply), $html );

// 加工したHTMLを出力する
print( $html );