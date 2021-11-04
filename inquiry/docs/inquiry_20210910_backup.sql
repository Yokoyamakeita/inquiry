-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- ホスト: 127.0.0.1
-- 生成日時: 2021-09-10 06:03:16
-- サーバのバージョン： 10.4.19-MariaDB
-- PHP のバージョン: 8.0.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- データベース: `inquiry_friday`
--
CREATE DATABASE IF NOT EXISTS `inquiry_friday` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `inquiry_friday`;

-- --------------------------------------------------------

--
-- テーブルの構造 `answers`
--

CREATE TABLE `answers` (
  `id` int(11) NOT NULL COMMENT '回答ID',
  `inquiries_id` int(11) NOT NULL COMMENT 'お問い合わせID',
  `answer` text NOT NULL COMMENT '回答',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() COMMENT '登録日時',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT '更新日時'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='回答一覧';

-- --------------------------------------------------------

--
-- テーブルの構造 `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL COMMENT '発言ID',
  `inquiries_id` int(11) NOT NULL COMMENT 'お問い合わせID',
  `comment` text DEFAULT NULL COMMENT '発言',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() COMMENT '登録日時',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT '更新日時'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='ユーザー発言一覧';

--
-- テーブルのデータのダンプ `comments`
--

INSERT INTO `comments` (`id`, `inquiries_id`, `comment`, `created_at`, `updated_at`) VALUES
(1, 1, '修正したコメント', '2021-09-03 04:23:34', '2021-09-03 05:38:38'),
(3, 3, 'ガチャの確率ひどくないですか？！', '2021-09-03 05:37:50', '2021-09-03 05:37:50');

-- --------------------------------------------------------

--
-- テーブルの構造 `comment_answer_links`
--

CREATE TABLE `comment_answer_links` (
  `id` int(11) NOT NULL COMMENT 'リンクID',
  `comment_id` int(11) NOT NULL COMMENT '発言ID',
  `answer_id` int(11) NOT NULL COMMENT '回答ID',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() COMMENT '登録日時',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT '更新日時'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='発言ー回答　リンクテーブル';

-- --------------------------------------------------------

--
-- テーブルの構造 `inquiries`
--

CREATE TABLE `inquiries` (
  `id` int(11) NOT NULL COMMENT 'お問い合わせID',
  `name` varchar(32) NOT NULL COMMENT 'お客様名',
  `mail` varchar(255) NOT NULL COMMENT 'メールアドレス',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() COMMENT '登録日時',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT '更新日時'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='お問い合わせ一覧';

--
-- テーブルのデータのダンプ `inquiries`
--

INSERT INTO `inquiries` (`id`, `name`, `mail`, `created_at`, `updated_at`) VALUES
(1, '田丸 英嗣', 'code.drank@gmail.com', '2021-09-03 04:23:34', '2021-09-03 04:23:34'),
(3, '田丸 英嗣', 'code.drank@gmail.com', '2021-09-03 05:37:50', '2021-09-03 05:37:50');

--
-- ダンプしたテーブルのインデックス
--

--
-- テーブルのインデックス `answers`
--
ALTER TABLE `answers`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `comment_answer_links`
--
ALTER TABLE `comment_answer_links`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `inquiries`
--
ALTER TABLE `inquiries`
  ADD PRIMARY KEY (`id`);

--
-- ダンプしたテーブルの AUTO_INCREMENT
--

--
-- テーブルの AUTO_INCREMENT `answers`
--
ALTER TABLE `answers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '回答ID';

--
-- テーブルの AUTO_INCREMENT `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '発言ID', AUTO_INCREMENT=4;

--
-- テーブルの AUTO_INCREMENT `comment_answer_links`
--
ALTER TABLE `comment_answer_links`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'リンクID';

--
-- テーブルの AUTO_INCREMENT `inquiries`
--
ALTER TABLE `inquiries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'お問い合わせID', AUTO_INCREMENT=4;
--
-- データベース: `inquiry_thirsday`
--
CREATE DATABASE IF NOT EXISTS `inquiry_thirsday` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `inquiry_thirsday`;

-- --------------------------------------------------------

--
-- テーブルの構造 `answers`
--

CREATE TABLE `answers` (
  `id` int(11) NOT NULL COMMENT '回答ID',
  `inquiries_id` int(11) NOT NULL COMMENT 'お問い合わせID',
  `answer` text NOT NULL COMMENT '回答',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() COMMENT '登録日時',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT '更新日時'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='回答一覧';

--
-- テーブルのデータのダンプ `answers`
--

INSERT INTO `answers` (`id`, `inquiries_id`, `answer`, `created_at`, `updated_at`) VALUES
(1, 1, '提供率は5％です', '2021-09-02 04:56:33', '2021-09-02 04:56:33');

-- --------------------------------------------------------

--
-- テーブルの構造 `inquiries`
--

CREATE TABLE `inquiries` (
  `id` int(11) NOT NULL COMMENT 'お問い合わせID',
  `name` varchar(32) NOT NULL COMMENT 'お客様名',
  `mail` varchar(255) NOT NULL COMMENT 'メールアドレス',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() COMMENT '登録日時',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT '更新日時'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='お問い合わせ一覧';

--
-- テーブルのデータのダンプ `inquiries`
--

INSERT INTO `inquiries` (`id`, `name`, `mail`, `created_at`, `updated_at`) VALUES
(1, '田丸 英嗣', 'code.drank@gmail.com', '2021-09-02 04:11:19', '2021-09-02 04:11:19'),
(2, '田丸 英嗣', 'code.drank@gmail.com', '2021-09-02 04:11:24', '2021-09-02 04:11:24'),
(3, 'aaaaa', 'ssssss', '2021-09-02 04:11:38', '2021-09-02 04:11:38');

-- --------------------------------------------------------

--
-- テーブルの構造 `qa_links`
--

CREATE TABLE `qa_links` (
  `id` int(11) NOT NULL COMMENT 'ID',
  `user_comments_id` int(11) NOT NULL COMMENT 'ユーザー発言ID',
  `answers_id` int(11) NOT NULL COMMENT '回答ID'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='発言ー回答　リンクテーブル';

--
-- テーブルのデータのダンプ `qa_links`
--

INSERT INTO `qa_links` (`id`, `user_comments_id`, `answers_id`) VALUES
(1, 1, 1),
(3, 4, 1);

-- --------------------------------------------------------

--
-- テーブルの構造 `user_comments`
--

CREATE TABLE `user_comments` (
  `id` int(11) NOT NULL COMMENT 'ユーザー発言ID',
  `inquiries_id` int(11) NOT NULL COMMENT 'お問い合わせID',
  `comment` text NOT NULL COMMENT '発言',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() COMMENT '登録日時',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT '更新日時'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='ユーザー発言一覧';

--
-- テーブルのデータのダンプ `user_comments`
--

INSERT INTO `user_comments` (`id`, `inquiries_id`, `comment`, `created_at`, `updated_at`) VALUES
(1, 1, 'ガチャの確率ひどくないですか？！', '2021-09-02 04:11:19', '2021-09-02 04:11:19'),
(2, 2, 'cccccc', '2021-09-02 04:11:24', '2021-09-02 04:11:24'),
(3, 3, 'dddddddd', '2021-09-02 04:11:38', '2021-09-02 04:11:38'),
(4, 1, 'aaaaaaa', '2021-09-02 05:54:34', '2021-09-02 05:54:34');

--
-- ダンプしたテーブルのインデックス
--

--
-- テーブルのインデックス `answers`
--
ALTER TABLE `answers`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `inquiries`
--
ALTER TABLE `inquiries`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `qa_links`
--
ALTER TABLE `qa_links`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `user_comments`
--
ALTER TABLE `user_comments`
  ADD PRIMARY KEY (`id`);

--
-- ダンプしたテーブルの AUTO_INCREMENT
--

--
-- テーブルの AUTO_INCREMENT `answers`
--
ALTER TABLE `answers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '回答ID', AUTO_INCREMENT=2;

--
-- テーブルの AUTO_INCREMENT `inquiries`
--
ALTER TABLE `inquiries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'お問い合わせID', AUTO_INCREMENT=4;

--
-- テーブルの AUTO_INCREMENT `qa_links`
--
ALTER TABLE `qa_links`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID', AUTO_INCREMENT=4;

--
-- テーブルの AUTO_INCREMENT `user_comments`
--
ALTER TABLE `user_comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ユーザー発言ID', AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
