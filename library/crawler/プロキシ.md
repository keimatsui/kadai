# 開発プロキシを使ったクローラーの開発

参考：（るびきち本）佐々木拓郎・るびきち『Rubyによるクローラー開発技法』（SB Creative, 2014）のp.224

クローラ開発中，何度もサーバにアクセスして迷惑をかけないように，プロキシを間に挟む。これにより，同じURLにアクセスするときは，最初のアクセス時のキャッシュを利用するようになる。

**おそらくSSL（https）には対応していない。**

## インストールと起動

```bash
wget http://svn.coderepos.org/share/lang/ruby/cocproxy/proxy.rb
ruby proxy.rb
```

開発中は起動したままでよい。停止するときは`Ctrl-C`。

コマンドの実行には別のターミナルを使う（あるいは`byobu-screen`を使う。使い方は自分で調べる）。

## 使い方

ウェブのクライアントで，http://localhost:5432 をプロキシとして利用する。

### Rubyのプログラムの場合

るびきち本のp.227

RubyとAnemoneをインストールする。

```bash
sudo apt-get -y install ruby-dev libxml2-dev zlib1g-dev
sudo gem install anemone
```

`nokogiri-proxy.rb`を作る（ここにある）。

`ruby nokogiri-proxy.rb`を2回実行したとき，proxy.rbを実行しているターミナルに以下のように表示されればよい。

```
Use default configuration.
Port : 5432
Dir  : files/
Cache: true
Rules:
    1. #{File.basename(req.path_info)}
    2. #{req.host}#{req.path_info}
    3. #{req.host}/#{File.basename(req.path_info)}
    4. .#{req.path_info}
Checking files//
Checking files/www.yahoo.co.jp/
Checking files/www.yahoo.co.jp//
Checking files/./
Cached: http://www.yahoo.co.jp/
Checking files//
Checking files/www.yahoo.co.jp/
Checking files/www.yahoo.co.jp//
Checking files/./
From Cache: http://www.yahoo.co.jp/
```

### ウェブブラウザの場合

矢吹研公式仮想マシンでは，ポートを転送しているから，ホスト側のブラウザでも使える。

ふだん使いでないブラウザで試すといいだろう。IEでプロキシを設定すると，Chromeにも引き継がれてしまうから，Firefoxで試すといい（ツール→オプション→詳細→ネットワーク→接続設定→手動でプロキシを設定する）。HTTPプロキシ（のみ），アドレスを`localhost`，ポートを`5432`に設定する。
