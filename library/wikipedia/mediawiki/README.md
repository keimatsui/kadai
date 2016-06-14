# MediaWikiのインストール

研究室公式仮想マシンで作業する。

以下のコマンドは，再現性を確保するために掲載しているものであって，「意味もわからずコピペすればよい」というものではない。（つまり，すべてのコマンドが何を意味しているのかはわかっている必要がある。）

研究室公式仮想マシンでは，展開したファイルを`/var/www/html`に移動するとシンボリックが張れないというエラーがでる。それを回避するために，ファイルを`/home/vagrant/public_html`に置くことにする。

```
cd
wget https://releases.wikimedia.org/mediawiki/1.26/mediawiki-1.26.2.tar.gz
mkdir public_html
tar zxf mediawiki-1.26.2.tar.gz
mv mediawiki-1.26.2 public_html/mediawiki
```

`/home/vagrant/public_html`を有効にする。

```
sudo a2enmod userdir
```

`sudo nano /etc/apache2/mods-enabled/php5.conf`としてエディタを起動し，`php_admin_flag engine Off`を`php_admin_flag engine On`に変更し，`sudo service apache2 restart`。

http://localhost/~vagrant/mediawiki/ にアクセスして初期設定を行う。

1. 「データベースのパスワード」欄に，先に設定したパスワード`pass`を入力する。
1. 「ウィキ名」欄に適当な名前を入力する。
1. 管理アカウントの管理者名を`admin`，パスワードを`pass`とする。
1. 「もう飽きてしまったので、とにかくウィキをインストールしてください。」を選ぶ。
1. `LocalSettings.php`をダウンロードし，`c:/vagrant/`に移動する。

```
mv /vagrant/LocalSettings.php ~/public_html/mediawiki
```

以上。http://localhost/~vagrant/mediawiki/ で利用できる。

## 最初からやり直したいとき

```
# データベースを削除する。
echo 'drop database my_wiki;' | mysql -uroot -ppass

# ファイルを削除する。
sudo rm -r ~/public_html/mediawiki/
```
