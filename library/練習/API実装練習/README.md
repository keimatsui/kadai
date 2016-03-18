# API実装練習

`tutorial_proc.md`に書かれた仕様どおりのアプリを作成する。（目標30分）

1. テスト環境（CentOS）の構築：[計算機環境の構築](https://github.com/yabukilab/main/blob/master/%E8%A8%88%E7%AE%97%E6%A9%9F%E7%92%B0%E5%A2%83%E3%81%AE%E6%A7%8B%E7%AF%89.md)を参照。ただし，CentOS 7.1を使うため，`vagrant init bento/centos-7.1`とする。（Vagrantファイルを編集し，8080から80へのポート転送も設定すること。）
1. ゲストにSSHでログインする：`vagrant ssh`でもいいのだが，本番環境では使えないから，TeraTermで接続する練習をした方がいい。大学から外部のサーバにSSH接続することはできない。接続手段を別に用意するか，裏技を事前に確認しておくこと。
1. ゲストにファイル`prefectures.csv`を送る：ゲストの`/vagrant`を使ってもいいのだが，本番環境では使えないから，TeraTermでSSHファイル転送する方法を練習した方がいい。以下では`/vagrant/prefectures.csv`があると仮定する。
1. `prefectures.csv`の改行コードをCRLFからLFに変更する。`tr -d \\r < /vagrant/prefectures.csv > /vagrant/prefectures2.csv`（これが必要なことに気付いたのは一度作ってみてから。）
1. ウェブサーバとMariaDB（MySQL），PHPのインストール：`sudo yum install httpd php php-mysql php-pdo mariadb-server nano`とし，ホストからhttp://localhost:8080/ にアクセスして動作を確認する。
1. 各種サーバの起動：`sudo service httpd start`や`sudo service mariadb start`。マシン起動時に起動するようにする場合は，`sudo chkconfig httpd on`や`sudo chkconfig mariadb on`。
1. 実験用のフォルダの作成：`mkdir /vagrant/api; sudo ln -s /vagrant/api /var/www/html/api`
1. PHPの動作確認：`echo '<?php phpinfo();' > /vagrant/api/info.php`とし，http://localhost:8080/api/info.php で確認する。
1. MySQLへの接続：矢吹の本参照。`mysql -uroot --default-character-set=utf8`
1. データベース作成：矢吹の本参照。`create database tutorial charset=utf8; grant all on tutorial.* to test@localhost identified by 'pass';`
1. テーブル作成：矢吹の本参照。`use tutorial; create table prefectures (id int primary key, prefecture varchar(20), prefecture_ruby varchar(50), capital varchar(20), capital_ruby varchar(50), index(prefecture));`
1. データのインポート：矢吹の本参照。`load data local infile '/vagrant/prefectures2.csv' into table prefectures fields terminated by ',' ignore 1 lines (id,prefecture,prefecture_ruby,capital,capital_ruby);`でインポートし，`select * from prefectures\G`で確認する。
1. MySQLからの切断：`exit`
1. `prefectures.php`を`/vagrant/api/prefectures.php`に置き，http://localhost:8080/api/prefectures.php とhttp://localhost:8080/api/prefectures.php?prefecture=%E5%B1%B1 の動作を確認する。（北海道と青森県が返る例は間違い。結果がこの2件になることはあり得ない。）
1. http://localhost:8080/api/prefectures やhttp://localhost:8080/api/prefectures?prefecture=%E5%B1%B1 で動くようにする：`/etc/httpd/conf/httpd.conf`の151行目の`AllowOverride None`を`AllowOverride All`に書き換え，`sudo service httpd restart`。`.htaccess`を`/var/www/html`にコピーし，`chmod 604 /var/www/html/.htaccess`。

## おまけ

`provision.sh`にすべての作業が記述してある。`vagrant init bento/centos-7.1`のあとで`c:/vagrant/centos`に`centos`の中身をコピーした状態で`vagrant up`すれば，いきなりhttp://localhost:8080/api/prefectures にアクセスできるはず。
