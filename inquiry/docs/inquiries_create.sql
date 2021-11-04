-- Project Name : noname
-- Date/Time    : 2021/06/25 15:05:37
-- Author       : dora
-- RDBMS Type   : MySQL
-- Application  : A5:SQL Mk-2

/*
  BackupToTempTable, RestoreFromTempTable�^�����߂��t������Ă��܂��B
  ����ɂ��Adrop table, create table ����f�[�^���c��܂��B
  ���̋@�\�͈ꎞ�I�� $$TableName �̂悤�Ȉꎞ�e�[�u�����쐬���܂��B
*/

-- ���₢���킹�ꗗ
--* BackupToTempTable
drop table if exists inquiries cascade;

--* RestoreFromTempTable
create table inquiries (
  id int(11) not null auto_increment comment '���₢���킹ID'
  , name varchar(32) not null comment '���q�l��'
  , mail varchar(255) not null comment '���[���A�h���X'
  , inquiry text not null comment '���₢���킹���e'
  , created_at timestamp default CURRENT_TIMESTAMP comment '�o�^����'
  , updated_at timestamp default CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP comment '�X�V����'
  , constraint inquiries_PKC primary key (id)
) comment '���₢���킹�ꗗ' ;

