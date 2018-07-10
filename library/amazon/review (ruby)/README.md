# レビューの取得

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
