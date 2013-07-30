--
GRANT ALL ON customer_management.* TO test@localhost IDENTIFIED BY 'pass';


--
-- 顧客管理データベース（customer_management）の作成
--
CREATE DATABASE customer_management CHARACTER SET utf8;

USE customer_management;

--
-- 顧客テーブル（customers）の作成
--
CREATE TABLE `customers` (
  `id` int(11) NOT NULL auto_increment,
  `customer_cd` varchar(10) default NULL,
  `name` varchar(50) default NULL,
  `kana` varchar(50) default NULL,
  `gender` int(11) default NULL,
  `zip` varchar(10) default NULL,
  `prefecture_id` int(11) default NULL,
  `address1` varchar(200) default NULL,
  `address2` varchar(200) default NULL,
  `phone` varchar(20) default NULL,
  `fax` varchar(20) default NULL,
  `email` varchar(100) default NULL,
  `lasttrade` date default NULL,
  `facebook_id` varchar(255) default NULL,
  `created` datetime default NULL,
  `modified` datetime default NULL,
  PRIMARY KEY  (`id`),
-- インデックスの付与
  KEY `idx_customers_email` (`email`),
-- FULLTEXTインデックスの付与
  FULLTEXT KEY `customer_cd` (`customer_cd`),
  FULLTEXT KEY `name` (`name`),
  FULLTEXT KEY `kana` (`kana`),
  FULLTEXT KEY `zip` (`zip`),
  FULLTEXT KEY `phone` (`phone`),
  FULLTEXT KEY `email` (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
--
-- 都道府県テーブル（prefectures）の作成
--
CREATE TABLE `prefectures` (
  `id` int(11) NOT NULL auto_increment,
  `pref_name` varchar(20) default NULL,
  `created` datetime default NULL,
  `modified` datetime default NULL,
  PRIMARY KEY  (`id`),
-- FULLTEXTインデックスの付与
  FULLTEXT KEY `pref_name` (`pref_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 製品テーブル（products）の作成
--
CREATE TABLE `products` (
  `id` int(11) NOT NULL auto_increment,
  `product_name` varchar(200) default NULL,
  `unit_price` int(11) default NULL,
  `created` datetime default NULL,
  `modified` datetime default NULL,
  PRIMARY KEY  (`id`),
-- インデックスの付与
  KEY `idx_products_product_name` (`product_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 売上テーブル（sales）の作成
--
CREATE TABLE `sales` (
  `id` int(11) NOT NULL auto_increment,
  `purchase_date` date default NULL,
  `customer_id` int(11) default NULL,
  `product_id` int(11) default NULL,
  `amount` int(11) default NULL,
  `created` datetime default NULL,
  `modified` datetime default NULL,
  PRIMARY KEY  (`id`),
-- インデックスの付与
  KEY `idx_sales_purchase_date` (`purchase_date`),
  KEY `idx_sales_customer_id` (`customer_id`),
  KEY `idx_sales_product_id` (`product_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- ユーザテーブル（users）の作成
--
CREATE TABLE `users` (
  `id` int(11) NOT NULL auto_increment,
  `username` varchar(50) default NULL,
  `password` varchar(50) default NULL,
  `facebook_appid` varchar(255) default NULL,
  `facebook_appsecret` varchar(255) default NULL,
  `created` datetime default NULL,
  `modified` datetime default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 権限の設定
--  ユーザ名：cakeuser
--  パスワード：cakepass
--  customer_managementデータベースに対する全てのアクセス権を与える
-- 
GRANT ALL ON customer_management.* TO 'cakeuser'@'localhost' IDENTIFIED BY 'cakepass';

FLUSH PRIVILEGES;



--
-- テストデータ挿入（customers）
--
INSERT INTO `customers` VALUES (1,'0001','鈴木きょうこ','スズキキョウコ',2,'131-0045',13,'墨田区押上1-1','東京スカイツリー','03-0000-0000','03-0000-0000','k_suzuki@test.co.jp','2012-07-02','',now(),now());
INSERT INTO `customers` VALUES (2,'0002','高橋ゆい','タカハシユイ',2,'220-0012',14,'横浜市西区みなとみらい2-2','横浜ランドマークタワー','046-000-0000','046-000-0000','y_takahashi@test.co.jp','2011-04-01','',now(),now());
INSERT INTO `customers` VALUES (3,'0003','田中ちなつ','タナカチナツ',2,'246-0022',12,'千葉市美浜区美浜1','','043-000-0000','043-000-0000','c_tanaka@test.co.jp','2012-07-02','',now(),now());
INSERT INTO `customers` VALUES (4,'0004','佐藤あかり','サトウアカリ',2,'330-0081',11,'さいたま市中央区新都心8','','048-000-0000','048-000-0000','a_sato@test.co.jp','2012-05-01','',now(),now());

--

-- テストデータ挿入（prefectures）
--
INSERT INTO `prefectures` VALUES (1,'北海道',now(),now());
INSERT INTO `prefectures` VALUES (2,'青森県',now(),now());
INSERT INTO `prefectures` VALUES (3,'岩手県',now(),now());
INSERT INTO `prefectures` VALUES (4,'宮城県',now(),now());
INSERT INTO `prefectures` VALUES (5,'秋田県',now(),now());
INSERT INTO `prefectures` VALUES (6,'山形県',now(),now());
INSERT INTO `prefectures` VALUES (7,'福島県',now(),now());
INSERT INTO `prefectures` VALUES (8,'茨城県',now(),now());
INSERT INTO `prefectures` VALUES (9,'栃木県',now(),now());
INSERT INTO `prefectures` VALUES (10,'群馬県',now(),now());
INSERT INTO `prefectures` VALUES (11,'埼玉県',now(),now());
INSERT INTO `prefectures` VALUES (12,'千葉県',now(),now());
INSERT INTO `prefectures` VALUES (13,'東京都',now(),now());
INSERT INTO `prefectures` VALUES (14,'神奈川県',now(),now());
INSERT INTO `prefectures` VALUES (15,'山梨県',now(),now());
INSERT INTO `prefectures` VALUES (16,'長野県',now(),now());
INSERT INTO `prefectures` VALUES (17,'新潟県',now(),now());
INSERT INTO `prefectures` VALUES (18,'富山県',now(),now());
INSERT INTO `prefectures` VALUES (19,'石川県',now(),now());
INSERT INTO `prefectures` VALUES (20,'福井県',now(),now());
INSERT INTO `prefectures` VALUES (21,'岐阜県',now(),now());
INSERT INTO `prefectures` VALUES (22,'静岡県',now(),now());
INSERT INTO `prefectures` VALUES (23,'愛知県',now(),now());
INSERT INTO `prefectures` VALUES (24,'三重県',now(),now());
INSERT INTO `prefectures` VALUES (25,'滋賀県',now(),now());
INSERT INTO `prefectures` VALUES (26,'京都府',now(),now());
INSERT INTO `prefectures` VALUES (27,'大阪府',now(),now());
INSERT INTO `prefectures` VALUES (28,'兵庫県',now(),now());
INSERT INTO `prefectures` VALUES (29,'奈良県',now(),now());
INSERT INTO `prefectures` VALUES (30,'和歌山県',now(),now());
INSERT INTO `prefectures` VALUES (31,'鳥取県',now(),now());
INSERT INTO `prefectures` VALUES (32,'島根県',now(),now());
INSERT INTO `prefectures` VALUES (33,'岡山県',now(),now());
INSERT INTO `prefectures` VALUES (34,'広島県',now(),now());
INSERT INTO `prefectures` VALUES (35,'山口県',now(),now());
INSERT INTO `prefectures` VALUES (36,'徳島県',now(),now());
INSERT INTO `prefectures` VALUES (37,'香川県',now(),now());
INSERT INTO `prefectures` VALUES (38,'愛媛県',now(),now());
INSERT INTO `prefectures` VALUES (39,'高地県',now(),now());
INSERT INTO `prefectures` VALUES (40,'福岡県',now(),now());
INSERT INTO `prefectures` VALUES (41,'佐賀県',now(),now());
INSERT INTO `prefectures` VALUES (42,'長崎県',now(),now());
INSERT INTO `prefectures` VALUES (43,'熊本県',now(),now());
INSERT INTO `prefectures` VALUES (44,'大分県',now(),now());
INSERT INTO `prefectures` VALUES (45,'宮崎県',now(),now());
INSERT INTO `prefectures` VALUES (46,'鹿児島県',now(),now());
INSERT INTO `prefectures` VALUES (47,'沖縄県',now(),now());

--
-- テストデータ挿入（products）
--
INSERT INTO `products` VALUES (1,'トマト',50,'2009-04-01 10:00:00','2012-06-14 12:00:00');
INSERT INTO `products` VALUES (2,'リンゴ',150,'2010-04-01 10:00:00','2011-06-01 13:00:00');
INSERT INTO `products` VALUES (3,'バナナ',300,'2011-04-01 10:00:00','2011-12-01 13:00:00');
INSERT INTO `products` VALUES (4,'いちご',1000,'2012-04-01 10:00:00','2012-04-01 10:00:00');
INSERT INTO `products` VALUES (5,'メロン',3000,'2012-04-01 10:00:00','2012-04-01 10:00:00');

--
-- テストデータ挿入（sales）
--
INSERT INTO `sales` VALUES (1,'2009-06-02',1,1,100,'2009-06-02 15:00:00','2009-06-02 15:00:00');
INSERT INTO `sales` VALUES (2,'2012-07-02',4,2,5,'2012-07-02 15:00:00','2012-07-02 15:00:00');
INSERT INTO `sales` VALUES (3,'2011-04-01',2,3,10,'2011-04-01 15:00:00','2011-04-01 15:00:00');
INSERT INTO `sales` VALUES (4,'2012-07-02',1,1,1000,'2012-07-02 16:00:00','2012-07-02 16:00:00');
INSERT INTO `sales` VALUES (5,'2012-05-01',3,4,10,'2012-05-01 12:00:00','2012-05-01 12:00:00');
INSERT INTO `sales` VALUES (6,'2012-04-02',3,5,20,'2012-05-01 12:00:00','2012-05-01 12:00:00');
