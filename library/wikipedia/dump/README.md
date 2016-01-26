# Wikipediaの履歴の調査

## 環境の準備

### Windows

TeraTermをインストールする．

### Ubuntu

Pythonとbyobu-screenをインストールする．（screenの使い方をどこかで学ぶこと）

## サーバへのログイン

TeraTermでサーバにログインする．

`byobu-screen -r`でセッションにアタッチする．（通信が切れても大丈夫なように）

## データの準備

日本語Wikipediaのデータは，https://dumps.wikimedia.org/jawiki/latest/ からダウンロードできる．

http://www.mwsoft.jp/programming/munou/wikipedia_data_list.html によると，stub-meta-history.xml が使えそう．

再現性を確保するため，20150901版を使う（latestはダウンロードの途中で変わるとやっかいだということもある）．並列化も試すため，分割版もダウンロードする．

```
wget https://dumps.wikimedia.org/jawiki/20150901/jawiki-20150901-stub-meta-history.xml.gz &
wget https://dumps.wikimedia.org/jawiki/20150901/jawiki-20150901-stub-meta-history1.xml.gz &
wget https://dumps.wikimedia.org/jawiki/20150901/jawiki-20150901-stub-meta-history2.xml.gz &
wget https://dumps.wikimedia.org/jawiki/20150901/jawiki-20150901-stub-meta-history3.xml.gz &
wget https://dumps.wikimedia.org/jawiki/20150901/jawiki-20150901-stub-meta-history4.xml.gz &
```

### データの確認

展開せずに中味を確認する．`q`で終了．`-c`の意味は`man gunzip`で確認する．

```
gunzip -c jawiki-20150901-stub-meta-history.xml.gz | less
```

このファイルのダウンロードには時間がかかるから，一度ダウンロードしたら消さずに取っておく．展開する場合は，`-k`を付ける．

```
gunzip -k jawiki-20150901-stub-meta-history.xml.gz
gunzip -k jawiki-20150901-stub-meta-history{1,2,3,4}.xml.gz
```

展開してから仕事をするのと，展開せずに仕事をするのでは，どちらが速いのか．調べてみる（展開後の大きいファイルをディスクから読むのには時間がかかるのでは，という仮説）

例題として，Wikipediaのページ数を数える．

展開してから仕事をする（先頭の`time `は時間を計るためのもの）．

```
time grep '<title>' jawiki-20150901-stub-meta-history.xml | wc -l
```

結果は2m5sだった．（ここで使ったデータでは2810474件）

次に，展開せずに仕事をする．

```
time gunzip -c jawiki-20150901-stub-meta-history.xml.gz | grep '<title>' | wc -l
```

結果は2m15sだった．

というわけで，このマシン（のHDDの速度）では，オンデマンドで展開しても速度はあまり変わらない．

## 月別編集回数

編集履歴のデータから，`grep`でタイムスタンプを取り出し，`cut`で「西暦-月」を切り出し，`sort`で並び替え，`uniq`で集計する。（「`|`」の後を`less`にして，少しずつ動かして試すとよい。）

```
gunzip -c jawiki-20150901-stub-meta-history.xml.gz | grep "<timestamp>" | cut -c18-24 | sort | uniq -c > month.dat
```

この`month.dat`をExcelで（テキスト形式で）読み込めばよいが，次のようにCSV形式にした方が簡単だろう。

```
cat month.dat | awk '{print $2","$1}' > month.csv
```

## SAX

SAXでXMLを解析する．

### 練習1：タイトルを取り出す．

`grep '<title>'`でできる話をやってみる．詳細は`titles.py`を参照．

動作確認（`q`で止める）．

```
cat jawiki-20150901-stub-meta-history.xml | python3 titles.py | less
```

本番（30分くらいかかる．並列化したい）

```
cat jawiki-20150901-stub-meta-history.xml | python3 titles.py > titles.dat
```

grepの場合と比較する．

```
wc -l titles.dat
```

2810474となって，先の結果と合っている．

### 練習2：ページごとに，revision数を求める

動作確認（`q`で止める）．詳細は`revisions.py`を参照．

```
cat jawiki-20150901-stub-meta-history.xml | python3 revisions.py | less
```

本番（27分くらいかかる．上とは違う環境）

```
cat jawiki-20150901-stub-meta-history.xml | python3 revisions.py > revisions.dat
```

revision数が多いページを見つける．

練習（`""`の中はTAB．Ctrl-vを押してからTABキーで入力する．`revisions.sh`からコピペしてもいい）

```
head revisions.dat | sort -r -n -k 2 -t "	"
```

本番（TABの入力が不安だから，`revisions.sh`に書いておく．5m30sくらいかかる）

```
time sh revisions.sh
```

「`head sort.dat`」の結果は以下のとおり．

```
Wikipedia:保護依頼/history20080331      13939
Wikipedia:メインページ新着投票所/新しい項目候補/ログ/2014年     12071
Wikipedia:メインページ新着投票所/新しい項目候補/ログ/2007年まで 10335
Wikipedia:井戸端/history 070722 7918
Wikipedia:保護解除依頼/history20080331  6963
Wikipedia:管理者伝言板/荒らし/history20080331   6811
Wikipedia:改名提案/history20140727      6760
Wikipedia:改名提案/history20120406      6557
Wikipedia:井戸端/過去ログ/2006年9月     6556
Wikipedia:管理者伝言板/投稿ブロック/history20110116     6412
```

## 更新日時での絞り込み

更新日時を特定の時期に絞り込むためには，`time-revisions.py`を使う。

たとえば，2004年1月に更新されたものだけを数えたい場合は次のとおり（後述の方法で並列化した方が速い）。

```
cat jawiki-20150901-stub-meta-history1.xml | python3 time-revisions.py 2004-01
```

`time-revisions.py`は，引数として与えられたパターンに，先頭からマッチする内容のtimestamp要素を持つrevisionだけを数える。パターンには正規表現を使えるが，先頭からマッチさせる仕様になっている（つまり，`^`を付かなくても`^`を付けたのと同じ結果になる）。

## 並列化

`jawiki-20150901-stub-meta-history1.xml`などの分割ファイルを使う．

### 準備

```
tail jawiki-20150901-stub-meta-history1.xml
```

などとして，page要素の途中では分割されていないことを確認する．

### タイトルの抽出

`&`を付けて実行し，バックグラウンドで処理させる．(30分から12分に短縮)

```
cat jawiki-20150901-stub-meta-history1.xml | python3 titles.py > titles1.dat &
cat jawiki-20150901-stub-meta-history2.xml | python3 titles.py > titles2.dat &
cat jawiki-20150901-stub-meta-history3.xml | python3 titles.py > titles3.dat &
cat jawiki-20150901-stub-meta-history4.xml | python3 titles.py > titles4.dat &
```

`top`で負荷を観察する（`q`で終了）．`jobs`の結果，バックグラウンド・ジョブが無くなっていればよい（途中の`jobs`の結果と比較せよ）．バックグラウンド・ジョブの殺し方などは，自分で調べておくこと．

結果の確認

```
cat titles{1,2,3,4}.dat | wc -l
```

2810474となって，先の結果と合っている．

### revision数を求める（27分から10分に短縮）

```
cat jawiki-20150901-stub-meta-history1.xml | python3 revisions.py > revisions1.dat &
cat jawiki-20150901-stub-meta-history2.xml | python3 revisions.py > revisions2.dat &
cat jawiki-20150901-stub-meta-history3.xml | python3 revisions.py > revisions3.dat &
cat jawiki-20150901-stub-meta-history4.xml | python3 revisions.py > revisions4.dat &
```

結合してソートする（5分くらいかかる）．

```
cat revisions{1,2,3,4}.dat | sort -r -n -k 2 -t "	" > sort-parallel.dat
```

`head sort-parallel.dat`で結果を確認する．`diff sort.dat sort-parallel.dat`の結果が空，つまり両者に違いが無ければよい．

## 差し戻し回数

SHA1が同じなら差し戻しとみなすことにする．厳密には，

+ テキストが違ってもSHA1が同じになることはある
+ 差し戻してそのまま編集された場合をカウントできない．

SHA1を単純に数えた結果が編集回数．そこから重複を削除した結果が差し戻し以外の回数．両者の差が，ここでいう差し戻しの回数となる．詳細は`revert.py`を参照すること．

```
cat jawiki-20150901-stub-meta-history1.xml | python3 revert.py > revert1.dat &
cat jawiki-20150901-stub-meta-history2.xml | python3 revert.py > revert2.dat &
cat jawiki-20150901-stub-meta-history3.xml | python3 revert.py > revert3.dat &
cat jawiki-20150901-stub-meta-history4.xml | python3 revert.py > revert4.dat &
```

```
cat revert{1,2,3,4}.dat | sort -r -n -k 4 -t "	" > revert.dat
```

「`head revert.dat`」の結果は以下のとおり．

```
Portal:スポーツ/今日は何の日    3654    1114    2540
Portal:文学/今日は何の日        3922    1518    2404
朝ズバッ!       2572    668     1904
Template:今日は何の日   4470    2884    1586
Portal:災害/今日は何の日        1421    500     921
ログ・ホライズン        1312    466     846
デュラララ!!    1432    607     825
KARA    1941    1133    808
水橋かおり      1077    311     766
魔法少女まどか☆マギカ  2683    1933    750
```

## 編集者の調査

`pageId[TAB]revisionId[TAB]userId[TAB]ip`というデータを作る。

```
cat jawiki-20150901-stub-meta-history.xml | python3 editors.py > editors.dat
```
