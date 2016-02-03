# レビューの取得

`shell.md`は勉強用の資料であり，実用的ではない。本格的にレビューを取得する場合は，ここで紹介する`reviews.rb`を使うこと。

## `reviews.rb`

このスクリプトを読むための参考資料：佐々木拓郎, るびきち『Rubyによるクローラー開発技法』（SBクリエイティブ, 2014）

### 準備

```
sudo apt-get -y install ruby-dev libxml2-dev zlib1g-dev
sudo gem install anemone
```

### スクリプトの使い方

ASINを指定してレビューを取得する。

```
asin=4274064069; ruby reviews.rb ${asin} > ${asin}.csv
```

これにより，`4274064069.csv`というファイルができる。各列の意味は`reviews.rb`を読めばわかる。
