# Wikipedianの活動調査

## 利用者ページのサイズ

「香辛料」というウィキペディアンがいたとする。

この人のページ[http://ja.wikipedia.org/wiki/利用者:香辛料](http://ja.wikipedia.org/wiki/利用者:香辛料)の履歴表示[http://ja.wikipedia.org/w/index.php?title=利用者:香辛料&action=history](http://ja.wikipedia.org/w/index.php?title=利用者:香辛料&action=history)を見れば、この人のページのサイズがわかる。

履歴表示をファイルに保存してから確かめる。

```bash
wget "http://ja.wikipedia.org/w/index.php?title=利用者:香辛料&action=history" -O tmp

cat tmp\
| gawk -f byte.awk\
| sed 's/,//g'\
| sed 's/空/0/g'\
| head -n 1
```

ファイルに保存せずに調べるなら、

```bash
wget "http://ja.wikipedia.org/w/index.php?title=利用者:香辛料&action=history" -O -\
| gawk -f byte.awk\
| sed 's/,//g'\
| head -n 1
```

ページがない人（「タイポ女子」のページがないとする）の場合、

```bash
wget "http://ja.wikipedia.org/w/index.php?title=利用者:タイポ女子&action=history" -O -\
| gawk -f byte.awk\
| sed 's/,//g'\
| head -n 1
```

何も出力されない。

## 投稿記録

「香辛料」の投稿記録[http://ja.wikipedia.org/w/index.php?title=特別:投稿記録/香辛料&limit=500](http://ja.wikipedia.org/w/index.php?title=特別:投稿記録/香辛料&limit=500)を調べてみる。

ファイルに保存して、差分のバイト数だけを抜き出す。

```bash
wget "http://ja.wikipedia.org/w/index.php?title=特別:投稿記録/香辛料&limit=500" -O tmp

cat tmp\
| gawk -f diff.awk\
> tmp.csv

cat tmp.csv
```

平均と標準偏差を求める。

```bash
cat tmp\
| gawk -f diff.awk\
| gawk -f stat.awk
```

ファイルに保存せずに調べるなら、

```bash
wget "http://ja.wikipedia.org/w/index.php?title=特別:投稿記録/香辛料&limit=500" -O -\
| gawk -f diff.awk\
| gawk -f stat.awk
```

## 編集回数の多いウィキペディアン

[http://ja.wikipedia.org/wiki/Wikipedia:編集回数の多いウィキペディアンの一覧](http://ja.wikipedia.org/wiki/Wikipedia:編集回数の多いウィキペディアンの一覧)のテーブルを抜き出す。

`python topwikipedians.py > topwikipedians.csv`（`topwikipedians.csv`をExcelで読むと文字化けする。Excelで読みたい場合は、`nkf -sjis topwikipedians.csv > topwikipedians.sjis.csv`などとすること。）

Google Spreadsheetで`=IMPORTHTML("http://ja.wikipedia.org/wiki/Wikipedia:%E7%B7%A8%E9%9B%86%E5%9B%9E%E6%95%B0%E3%81%AE%E5%A4%9A%E3%81%84%E3%82%A6%E3%82%A3%E3%82%AD%E3%83%9A%E3%83%87%E3%82%A3%E3%82%A2%E3%83%B3%E3%81%AE%E4%B8%80%E8%A6%A7","table",1)`としてもよいのだが「(bot)」などを手動で削除しなければならない。

1行目は見出しだから無視し、2行目以降で2列目に入っている名前を抜き出す。

```bash
cat topwikipedians.csv\
| tail -n +2\
| gawk -F ',' '{print $2}'
```

あとは上のスクリプトを再現して実行すればよい。

### 編集回数の多いウィキペディアンの個人ページサイズ

```bash
rm pagesize.csv

cat topwikipedians.csv\
| tail -n +2\
| gawk -f pagesize.awk\
| sh
```

結果は`pagesize.csv`に格納される。

### 編集回数の多いウィキペディアンの投稿記録

```bash
rm record.csv

cat topwikipedians.csv\
| tail -n +2\
| gawk -f record.awk\
| sh
```

結果は`record.csv`に格納される。

2015年1月22日に取得したデータが`20150122_topwikipedians.csv`である。