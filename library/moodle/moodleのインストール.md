#Moodleのインストール

いろいろカスタマイズしたりする可能性があるから、`sudo apt-get install moodle`ではなく、ソースを使ってインストールする。

##前提（ApacheとMySQLとPHPのインストール）

```
sudo apt-get install apache2 mysql-server mysql-client php5
```

途中でMySQLのroot（管理者）のパスワードを聞かれるから、「pass」のような簡単なものを設定しておく。

矢吹の教科書のHello WorldでPHPの動作確認をする。

##ウェブアプリのインストールの原則

1. データベースの準備
1. データベースのアクセス権の設定
1. ウェブアプリのファイルの展開
1. データベースのアクセス権のデータベースへの登録

```
mysql -uroot -ppass
```

```
CREATE DATABASE moodle;
GRANT ALL ON moodle.* to moodleuser@localhost IDENTIFIED BY 'passwd';
EXIT
```

http://download.moodle.org/ からMoodleをダウンロードする。そのディレクトリにApacheが書き込めるようにしておく。

```
cd /var/www
sudo tar zxf ~/ダウンロード/moodle-latest-26.tgz
sudo chown www-data:www-data moodle
```

http://localhost/moodle/install.php にアクセスするとセットアップが始まる。

「cURL PHP拡張モジュールが必要」とか言われるから、`apt-cache search curl | grep php`などとして探し、インストールする。

```
sudo apt-get install php5-curl
sudo service apache2 restart
```

必要なものを確認しながら進める。

```
sudo mkdir /var/moodledata
sudo chown www-data:www-data
```

```
sudo apt-get install php5-mysqlnd
sudo service apache2 restart
```

cURLと同様に、必要なものを確認してインストールする。

```
sudo apt-get install php5-gd php5-xmlrpc php5-intl
sudo service apache2 restart
```
