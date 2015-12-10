-- sudo apt-get install mysql-server mysql-client

-- 管理者rootのパスワードをpassとする。

-- mysql -uroot -ppass

drop database if exists twitter;
create database twitter default charset=utf8;

-- ユーザtest、パスワードpassでアクセスできるようにする。

grant all on twitter.* to test@localhost identified by 'pass';

use twitter;

-- ユーザを記録するテーブル
drop table if exists users;
create table users (
  id bigint primary key,
  screenName varchar(100) not null,
  statuses int,
  friends int,
  followers int,
  lang varchar(10),
  profileImageUrl varchar(1000),
  rekognition text,-- 画像認識結果のため
  index(lang),
  unique index(screenName)
);

desc users;

-- リツイートを記録するテーブル
drop table if exists retweets;
create table retweets (
  id bigint primary key,-- ツイートID。同じツイートを2回処理しない（最初に観測されたリツイートだけ調べる）
  retweet bigint not null,-- リツイートした人のID（最初に観測されたリツイートだけ調べる）
  retweeted bigint not null,-- リツイートされた人のID
  rcount int not null,-- リツイートされた回数
  fcount int not null,-- お気に入りに登録された回数
  foreign key (retweet) references users (id),
  foreign key (retweeted) references users (id),
  unique index(retweet,retweeted)-- 同じ人間関係を2回調べない
);

desc retweets;

-- リツイートした人のリストを記録するテーブル
drop table if exists retweeters;
create table retweeters (
  id int auto_increment primary key,
  tweetId bigint not null,
  retweet bigint not null,-- リツイートした人のID
  foreign key (tweetId) references retweets (id),
  foreign key (retweet) references users (id)
);

desc retweeters;

-- （リツイートした人が）誰をフォローしているかを記録するテーブル
drop table if exists friends;
create table friends (
  id int auto_increment primary key,
  user bigint not null,
  friend bigint not null,
  foreign key (user) references users (id),
  index(friend)-- これは外部キーにしない（usersに無くてもよい）
);

desc friends;

-- 仕様変更

-- フレンドを調べたかどうか、リツイートした人を調べたかどうかをテーブルに書いておくことにする
alter table users add friendsChecked boolean not null default false;
alter table users add index(friendsChecked);
alter table retweets add retweetersChecked boolean not null default false;
alter table retweets add index(retweetersChecked);

-- すでにフレンドを調べている場合はそのことを反映する。
update users set friendsChecked = true where exists (select * from friends where friends.user=users.id);

-- すでにリツイートした人を調べている場合はそのことを反映する。
update retweets set retweetersChecked = true where exists (select * from retweeters where retweeters.tweetId = retweets.id);
