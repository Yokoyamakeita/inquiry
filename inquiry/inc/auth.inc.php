<?php
// ログイン認証処理 vvvvvvvv

    // 変数の初期化
    $error_message = '';
    $onetime_key = '';

    // セッション変数のワンタイムキーを取得する
    if ( !empty( $_SESSION['onetime_key'] ) ) {
        $onetime_key = $_SESSION['onetime_key'];
        // 使ったセッション変数はクリアしておく
        unset( $_SESSION['onetime_key'] );

        // データベースにアクセスし、ログインセッション一覧に、ワンタイムキーが一致し、有効期限内
        // のデータがあるか検索する
        // DB接続処理
        require_once( 'db.inc.php' );
        // データを取得するSQLを設定する
        $sql = 'SELECT  id, 
                        login_users_id
                    FROM login_sessions 
                    WHERE onetime_key = :onetime_key
                        AND 
                          expire_datetime > NOW();';

        // データベースに事前にSQLを登録する
        $statement = $dbh->prepare( $sql );

        // データベース変数にphp側の変数を紐づける
        $statement->bindParam( ':onetime_key', $onetime_key );

        // SQLを実行する
        $statement->execute();

        // 結果を取得する
        $result = $statement->fetch(PDO::FETCH_ASSOC);

        // 有効期限の切れたデータは削除しておく（本来ならログファイルに書き込んでおく）
        // データを削除するSQLを設定する
        $sql = 'DELETE FROM login_sessions 
                    WHERE expire_datetime <= NOW();';

        // データベースに事前にSQLを登録する
        $statement = $dbh->prepare( $sql );

        // データベース変数にphp側の変数を紐づける

        // SQLを実行する
        $statement->execute();


    // データが存在するなら、新しいキーをログインセッション一覧に登録する
        if ( !empty( $result ) ) {

            // ログインユーザーIDをセッションに保存する（！！！セキュリティ的に要修正）
            $_SESSION['login_users_id'] = $result['login_users_id'];

            // XXX 一意のワンタイムキーを設定する
            $onetime_key = uniqid(dechex(random_int(0, 255)));

            // 有効期限を設定する（分単位：本当なら設定ファイルに書く！）
            $expire_period = 3;

            // データベースにデータを登録する
            try {
                // データベースのエラー発生時に例外を発行するようにする
                $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // ログインセッション一覧を更新する
                // データを更新するSQLを設定する(ログインセッション一覧)
                $sql = 'UPDATE login_sessions 
                            SET 
                                onetime_key = :onetime_key, 
                                expire_datetime = NOW() + INTERVAL :expire_period MINUTE 
                            WHERE
                                id = :id;';
            
                // データベースに事前にSQLを登録する
                $statement = $dbh->prepare( $sql );
            
                // データベース変数にphp側の変数を紐づける
                $statement->bindParam( ':onetime_key', $onetime_key );
                $statement->bindParam( ':expire_period', $expire_period );
                $statement->bindParam( ':id', $result['id'] );
            
                // SQLを実行する（結果は使わない）
                $statement->execute();

                // セッション変数にも登録する
                $_SESSION['onetime_key'] = $onetime_key;

            } catch ( Exception $e ) {
                // XXX エラーメッセージを表示する
                $error_message = 'データベースエラー';
                echo $error_message . $e->getMessage();
            }

        } else {
            // セッション変数にはキーがあるが、ユーザーセッション一覧にはない、または有効期限過ぎ
            $error_message = 'ログインしていません(1)';
            // セッション変数をクリアしておく
            unset( $_SESSION['onetime_key'] );
        }

    } else {
        // セッション変数にそもそもキーがない。不正な遷移の可能性（いきなりURL指定されたとか）
        $error_message = 'ログインしていません(2)';
    }

    // データが存在しないなら、ログインページにリダイレクトする
    if ( !empty( $error_message ) ) {
        $_SESSION['error_message'] = $error_message;
        header( 'Location: login.php' );
        exit;
    }

// ログイン認証処理 ^^^^^^^^
