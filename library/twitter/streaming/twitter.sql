-- sudo apt-get install mysql-server mysql-client

-- 管理者rootのパスワードをpassとする。

-- mysql -uroot -ppass

create database twitter default charset=utf8;

-- ユーザtest、パスワードpassでアクセスできるようにする。

grant all on twitter.* to test@localhost identified by 'pass';

use twitter;

-- リツイートを記録するテーブル
drop table if exists retweets;
create table retweets (
  id int auto_increment primary key,
  retweet varchar(100) not null,-- 見やすさのため（IDのほうが効率はいい）
  retweeted varchar(100) not null,
  tweet varchar(50),-- 参考に、Tweetを1件だけ記録しておく
  checked boolean not null default FALSE,-- フォロー関係をチェックしたかどうか
  index(retweet),
  index(retweeted),
  index(checked),
  unique index(retweet,retweeted)-- 重複排除のため
);

desc retweets;

-- フォロー関係を記録するテーブル
drop table if exists follows;
create table follows (
  id int auto_increment primary key,
  follow varchar(100) not null,
  followed varchar(100) not null,
  index(follow),
  index(followed),
  unique index(follow,followed)-- 重複排除のため
);

desc follows;

-- ユーザを記録するテーブル
drop table if exists users;
create table users (
  id bigint auto_increment primary key,
  screenName varchar(100) not null,
  profileImageUrl varchar(1000),
  rekognition text,
  unique index(screenName)
);

desc users;

-- 練習

insert into retweets (retweet,retweeted,tweet) values ('a','b','3');
insert into follows (follow,followed) values ('a','b');

select * from retweets;
select * from follows;

-- 同じ組を入れようとするとエラーになる。
insert into retweets (retweet,retweeted,tweet) values ('a','b','4');
insert into follows (follow,followed) values ('a','b');

-- 空にしておく
truncate retweets;
truncate follows;