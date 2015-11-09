# 上位下位関係抽出ツール

[Hyponymy extraction tool](https://alaginrc.nict.go.jp/hyponymy/) のインストール法を説明する。

途中で`wget`でダウンロードするファイルが見つからないときは矢吹に聞くこと。

仮想マシンのスナップショットを撮ってから試すとよい。

## 作業ディレクトリ

```
cd
mkdir tmp
```

## Ruby 1.8（古いRuby）

https://www.brightbox.com/docs/ruby/ubuntu/ を参考にインストールする。（よくわからなければ，1行ずつ実行すること。）

```
sudo apt-get update
sudo apt-get -y install software-properties-common
sudo apt-add-repository ppa:brightbox/ruby-ng
```

Ruby 1.8をインストールする。

```
sudo apt-get update
sudo apt-get -y install ruby-switch ruby1.8 rubygems1.8 ruby1.8-dev
sudo ruby-switch --set ruby1.8
```
バージョン1.8が使えることを確認する。

```
ruby -v
```

次のように表示されれば成功。

```
ruby 1.8.7 (2015-04-14 MBARI 8/0x6770 on patchlevel 375) [x86_64-linux], MBARI 0x6770, Ruby Enterprise Edition 2012.02
```

## MeCab

```
sudo apt-get -y install mecab mecab-ipadic-utf8 libmecab-dev
```

### MeCab-Ruby

（http://d.hatena.ne.jp/zariganitosh/20141110/ruby_187_bundler_mecab_ruby を参考にした。）

RubyからMeCabを使えるようにする。（apt-getで入るパッケージ`ruby-mecab`は，古いRubyに対応していない。）

```
cd ~/tmp
wget -O mecab-ruby-0.994.tar.gz https://mecab.googlecode.com/files/mecab-ruby-0.994.tar.gz
tar zxf mecab-ruby-0.994.tar.gz
cd mecab-ruby-0.994
gem build mecab-ruby.gemspec
sudo gem install mecab-ruby
```

#### 動作確認

`test.rb`の3行目に`require 'rubygems'`を追記し，`ruby test.rb`で動作を確認する（解析結果が表示されればOK）。

## Pecco

```
cd ~/tmp
wget http://www.tkl.iis.u-tokyo.ac.jp/~ynaga/pecco/pecco-latest.tar.gz
tar zxf pecco-latest.tar.gz
cd pecco-XXXX-XX-XX #場合による。「pecco」まで入力してTABキーで補完すればよい。
./configure
sudo make install
```

## Hyponymy

```
cd ~/tmp
wget -O ex-hyponymy-1.0.tar.gz https://alaginrc.nict.go.jp/hyponymy/cgi-bin/dl.1.0.cgi
tar zxf ex-hyponymy-1.0.tar.gz
cd ex-hyponymy-1.0
```

パッチを当てる。

```
wget http://www.tkl.iis.u-tokyo.ac.jp/~ynaga/pecco/ex-hyponymy-1.0-pecco.patch
patch -p0 < ex-hyponymy-1.0-pecco.patch
```

さらに，`script/lib/mecab_part.rb`の`require 'Cut'`の前に`require 'rubygems'`を追記する。

```
gedit script/lib/mecab_part.rb &
```

## テスト

### テストデータの作成

作成済みのもの

* cheese-wine.xml.bz2：チーズとワインだけのWikiをダンプしたもの
* orange-apple.xml.bzp：柑橘類とリンゴだけのWikiをダンプしたもの

作業手順は以下のとおり。

1. https://github.com/taroyabuki/yabukilab/blob/master/library/wikipedia/mediawiki/install.md を参考にMediaWikiをインストールする。
1. 数ページ作ってみる。
1. MediaWikiの全データをXMLにダンプする。（https://www.mediawiki.org/wiki/Manual:DumpBackup.php/ja を参考にした。）

```
php /var/www/html/mediawiki/maintenance/dumpBackup.php --current > all-pages.xml
```

`less all-pages.xml`で結果を確認する。

`bzip2 all-pages.xml`で圧縮すると，`all-pages.xml.bz2`ができる。

ページを削除したいときは，次のようにするのが簡単。

```
echo -e 'チーズ\nワイン' | php /var/www/html/mediawiki/maintenance/deleteBatch.php
```

XMLファイルのインポートは以下のとおり。

```
bzip2 -dc all-pages.xml.bz2 | php /var/www/html/mediawiki/maintenance/importDump.php
```

ページの削除とXMLファイルのインポートを使えば，MediaWikiの内容を簡単に書き換えられる。

### テストデータの解析

```
bash script/ex_hyponymy.sh -E -s -t ./data3 all-pages.xml.bz2
```

#### 解析結果

ディレクトリ`cheese-wine`と`orange-apple`に結果がある。

## Wikipediaデータの解析

https://dumps.wikimedia.org/jawiki/20150901/ から`jawiki-20150901-pages-meta-current.xml.bz2`をダウンロードして実行する（もっと新しいものを使ってもよい）。

（メモリが5GB以下の場合は，省メモリモードのための「`-s`」が必要）

```
bash script/ex_hyponymy.sh -E -s -t ./data3 jawiki-20150901-pages-meta-current.xml.bz2
```

Core i7-4930K，メモリ8Gで，約46時間かかった。