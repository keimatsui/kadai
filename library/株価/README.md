# 企業・株価情報

佐々木拓郎，るびきち『Rubyによるクローラー開発技法』（SBクリエイティブ, 2014）の5章14節の方法を試す。

## 準備

Ubuntuで試す。

RubyとAnemoneをインストールする。

```
sudo apt-get -y install ruby-dev libxml2-dev zlib1g-dev
sudo gem install anemone
```

作業ディレクトリを作り、移動する

```
mkdir ruby
cd ruby
```

http://www.sbcr.jp/support/12019.html からサンプルスクリプトをダウンロードしてから先に進む。

```
unzip ~/ダウンロード/RubyCrawlerSample.zip
cd chapter5
```

## 5-14 企業・株価情報を収集する

### 5-14-2

```
ruby nokogiri-stock.rb
```

### 5-14-3

```
ruby nokogiri-stock-history.rb
```

#### 改良

`nokogiri-stock-history.rb`にはバグがあるため，`nokogiri-stock-history2.rb`のように変更する。ついでに，企業（コード）と期間を指定して株価を取得するようにする。

Yahoo (4689)の2015/10/1から2015/12/31の株価を取得するコマンドは次のとおり。

```
code=4689
ruby nokogiri-stock-history2.rb ${code} 2015 10 1 2015 12 31 > ${code}.csv 
```

#### 可視可

準備：Gnuplotをインストールする。

```
sudo apt-get -y install gnuplot-x11 plotutils
```

取得した株価データを可視化する（`history.pl`と`plot.sh`が必要）。

```
sh plot.sh 4689
```

`4689.png`ができているはず。
