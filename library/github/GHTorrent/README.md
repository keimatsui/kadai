# GHTorrent

GHTorrentのデータをMySQLにインポートして利用する方法を解説する。

## 前提知識

* 矢吹『Webアプリケーション構築入門』の第7章
* テーブルの構成を、http://gousios.gr/bibliography/GS12.html に掲載されている図で確認すること。

## MySQLへのインポート（tsujioka_vmでは実行済み）

GHTorrentのMySQL用データをダウンロードし、共有フォルダに置いておく（`\\share\ghtorrent\mysql-2016-11-01.tar.gz`と仮定）。

MySQLのパスワードを環境変数に入れておく（コマンドラインでパスワードを書いたときに出る警告がうるさいから）。ついでに、データベース名も環境変数に入れておく。

```
export MYSQL_PWD=pass
export dbname=ghtorrent20161101
```

`/etc/mysql/mysql.cnf`に以下を追記し、`sudo service mysql restart`。（主記憶16G程度のマシンを想定している。メモリが少ない場合は、それに応じて数値を減らす。）

```
[mysqld]
skip-external-locking
key_buffer_size = 4096M
max_allowed_packet = 1M
table_open_cache = 512
sort_buffer_size = 2M
read_buffer_size = 2M
read_rnd_buffer_size = 8M
myisam_sort_buffer_size = 4096M
thread_cache_size = 8
query_cache_size = 32M
```

共有フォルダをマウントする。（パスワードなし）

```
cd
mkdir share
sudo mount -t cifs //10.100.192.3/share share -o user=guest
```

ダウンロード済みのファイルを、MySQLがアクセスできる場所で展開する。

```
cd /tmp
tar xf ~/share/ghtorrent/mysql-2016-11-01.tar.gz
```

schema.sqlを修正し、インデックスをすべて削除する（大量のデータをインポートする場合、データをインポートしてからインデックスを張るのが定石。外部キー制約を削除するだけではインデックスが残ることに注意）。最後の「>> schema.sql」を省いて結果を確認してから実行する。

```
cp schema.sql schema.sql.bak

grep CONSTRAINT schema.sql | \
gawk '{print "ALTER TABLE",$2,"DROP FOREIGN KEY",$2,";"; print "ALTER TABLE",$2,"DROP INDEX",$2,";";}' | \
sed 's/_ibfk_.//' | \
sed 's/_fk./s/' \
>> schema.sql
```

ght-restore-mysqlを修正する。修正点は以下の通り。

* LOAD DATAのファイルの場所を自由にする。（そうしないと、secure_file_privで設定された場所からしか読めない。）
* SQLの宣言を緩和する。
* 「# 3」以降、つまりインデックスの作成を止める。

```
cat ght-restore-mysql | \
sed "s/; LOAD DATA INFILE/, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES'; LOAD DATA LOCAL INFILE/" | \
sed 's/$mysql/$mysql --local-infile --force/g' | \
gawk '{ if($0!~/# 3/) print $0; else exit 0; }' \
> ght-restore-mysql2
```

`diff ght-restore-mysql ght-restore-mysql2`の結果を確かめよ。

データベースを作る。

echo "drop database if exists $dbname; create database $dbname;" | mysql -uroot

一般ユーザはLOAD DATAができないから、rootでインポートする（一般ユーザの場合は、grant file on *.* to ...が必要）

```
bash ght-restore-mysql2 -u root -d $dbname .
```

## データの利用

必要なところだけインデックスを張る。

`SET SQL_MODE='';`としから（そうしないと、ERROR 1292 (22007): Incorrect datetime value: '0000-00-00 00:00:00' for column 'created_at' at row 49017 というエラーが出る。）

