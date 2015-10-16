# MediaWikiのインストール

Ubuntuで作業する。

MySQLの管理者パスワードを`pass`とする。

```
echo 'mysql-server mysql-server/root_password password pass' | sudo debconf-set-selections
echo 'mysql-server mysql-server/root_password_again password pass' | sudo debconf-set-selections
```

phpMyAdminをインストールする準備をする。

```
echo 'phpmyadmin phpmyadmin/reconfigure-webserver multiselect apache2' | sudo debconf-set-selections
echo 'phpmyadmin phpmyadmin/dbconfig-install boolean true' | sudo debconf-set-selections
echo 'phpmyadmin phpmyadmin/mysql/admin-pass password pass' | sudo debconf-set-selections
echo 'phpmyadmin phpmyadmin/mysql/app-pass password pass' | sudo debconf-set-selections
echo 'phpmyadmin phpmyadmin/app-password-confirm password pass' | sudo debconf-set-selections
```

必要なソフトウェアをインストールする。（phpMyAdminは必須ではないが，これを入れればApacheやPHPも入るから便利）

```
sudo apt-get install -y mysql-server phpmyadmin
```

MediaWikiをダウンロードし，展開する。

```
mkdir tmp
cd tmp
wget https://releases.wikimedia.org/mediawiki/1.25/mediawiki-1.25.2.tar.gz
tar zxf mediawiki-1.25.2.tar.gz
sudo mv mediawiki-1.25.2 /var/www/html/mediawiki
```

設定画面を開く。

```
firefox http://localhost/mediawiki/ &
```

データベースのパスワード欄に、先に設定したパスワード`pass`を入力する。管理者アカウントを適当に設定する。

`LocalSettings.php`を保存する。

```
sudo mv ~/ダウンロード/LocalSettings.php /var/www/html/mediawiki/
```

以上。MediaWikiはhttp://localhost/mediawiki/ で利用できる。
