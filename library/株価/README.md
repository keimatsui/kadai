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
