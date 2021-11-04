-- Project Name : noname
-- Date/Time    : 2021/06/25 15:05:37
-- Author       : dora
-- RDBMS Type   : MySQL
-- Application  : A5:SQL Mk-2

/*
  BackupToTempTable, RestoreFromTempTable疑似命令が付加されています。
  これにより、drop table, create table 後もデータが残ります。
  この機能は一時的に $$TableName のような一時テーブルを作成します。
*/

-- お問い合わせ一覧
--* BackupToTempTable
drop table if exists inquiries cascade;

--* RestoreFromTempTable
create table inquiries (
  id int(11) not null auto_increment comment 'お問い合わせID'
  , name varchar(32) not null comment 'お客様名'
  , mail varchar(255) not null comment 'メールアドレス'
  , inquiry text not null comment 'お問い合わせ内容'
  , created_at timestamp default CURRENT_TIMESTAMP comment '登録日時'
  , updated_at timestamp default CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP comment '更新日時'
  , constraint inquiries_PKC primary key (id)
) comment 'お問い合わせ一覧' ;

