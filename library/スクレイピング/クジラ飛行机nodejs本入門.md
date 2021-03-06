# JS+Node.jsによるWebクローラー

クジラ飛行机『JS+Node.jsによるWebクローラー／ネットエージェント』（ソシム, 2015）を、CentOSとUbuntuで試す。

この本はNode.jsを使っているため、（Pythonをメインで使う）矢吹研では、特別な理由がない限り、読まなくていい。

[計算機環境の構築](https://github.com/yabukilab/main/blob/master/%E8%A8%88%E7%AE%97%E6%A9%9F%E7%92%B0%E5%A2%83%E3%81%AE%E6%A7%8B%E7%AF%89.md)のUbuntu（Vagrant上）で試すため，作業フォルダを`/vagrant`にしている。他の環境で試す場合は作業フォルダを確認すること。

## 第1章

Node.jsのインストール

パッケージ管理でないのが気に入らないが，Vagrantだからどうでもいいだろう。

```
curl -o- https://raw.githubusercontent.com/creationix/nvm/v0.25.3/install.sh | bash
source ~/.bashrc

nvm install v0.12.4

cd /vagrant
wget http://www.socym.co.jp/download/993/src.zip
unzip src.zip
```

蛇足：展開したファイルは，ホストでも`C:\vagrant\ubuntu\src`で確認できるはず。

## 第2章

### 01

```
cd /vagrant/src/ch02/01-download/
node download-node.js
```

Rhinoで試す。

CentOSでの準備

```
sudo yum install java-1.8.0-openjdk-devel rhino
```

Ubuntuでの準備

```
sudo apt-get install rhino
```

実行

```
rhino download-rhino.js
```

### 02

Vagrantの共通フォルダ`/vagrant`ではシンボリックリンクが使えないため，`npm`にはオプション`--no-bin-links`が必要である。

```
cd /vagrant/src/ch02/02-analize/
npm install cheerio-httpcli --no-bin-links
```

```
node getpage.js
```

```
node showlink.js
```

### 04

グローバルインストール時に`sudo`は不要。

## 第3章

```
sudo apt-get install fontconfig
npm install -g phantomjs
npm install -g casperjs
```

あとは自分でできるだろう。
