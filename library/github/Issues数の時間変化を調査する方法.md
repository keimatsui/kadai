# Issues数の時間変化を調査する方法

## データの取得

https://github.com/Diogenesthecynic/FullScreenMario を例とする。

（すでにデータは取得済みかもしれないが、ファイルの命名規則をわかりやすくするため、改めて取得する。）

```
python api.py "https://api.github.com/repos/Diogenesthecynic/FullScreenMario/issues?per_page=100" > Diogenesthecynic-FullScreenMario-openissues.txt
python api.py "https://api.github.com/repos/Diogenesthecynic/FullScreenMario/issues?per_page=100&state=closed" > Diogenesthecynic-FullScreenMario-closedissues.txt
```

## オープン・クローズの情報だけを抜き出す

```
./jq '.created_at' Diogenesthecynic-FullScreenMario-openissues.txt | awk '{ printf("%s open\n", $0); }' > issues.tmp
./jq '.created_at,.closed_at' Diogenesthecynic-FullScreenMario-closedissues.txt | awk '{ if (NR % 2 == 1) printf("%s open\n", $0); else printf("%s close\n", $0); }' >> issues.tmp
```

データはスペース等を含んでいない単純なもので、次の処理も`awk`だから、カンマ区切りではなくスペース区切りで出力している（`awk`のデフォルトFSはスペース）。`>`はファイルに上書き、`>>`はファイルに追記することに注意。

時間で並び替える。

```
sort issues.tmp > issues.txt
```

Issuesの累積数を求める。

```
awk 'BEGIN { openissues=0; closedissues=0; } $2=="open" { openissues++; } $2=="close" { closedissues++; } { printf("%s,%d,%d\n", $1, openissues, closedissues) }' issues.txt > issues.csv
```

## グラフ表示

コマンドでグラフを描くための定番ツール、Gnuplotを使う。（詳しい使い方は自分で調べること。）

### 準備

```
sudo apt-get -y install gnuplot-x11 plotutils
```

### 実験

`gnuplot`としてGnuplotを起動し、以下のコマンドを試す。（本質は最後だけなのだが、ファイルがカンマ区切りである、横軸が時間である、日時の形式を指定しなければならないといったことへの対応が必要。）

```
set datafile separator ","
set xdata time
set timefmt "%Y-%m-%d %H:%M:%S"
plot 'issues.csv' using 1:2 with lines, 'issues.csv' using 1:3 with lines
```

### 形式

#### 線の太さ

```
plot 'issues.csv' using 1:2 with lines lw 3, 'issues.csv' using 1:3 with lines lw 3
```

#### 軸ラベル

```
set xl "date time"
set yl "number of issues"
replot
```

#### 凡例

```
plot 'issues.csv' using 1:2 with lines lw 3 title 'open issues', 'issues.csv' using 1:3 with lines lw 3 title 'closed issues'
```

### ファイルへの出力

```
set terminal png
set out "issues.png"
replot
set terminal wxt
```

## 自動化

2つの部分に分けて自動化する。（データの取得を何度もやりたくないから。）

### データの取得

下のように、所有者とレポジトリ名を指定してデータを取得する。

```
bash getIssues.sh Diogenesthecynic FullScreenMario
```

### グラフの描画

下のように、所有者とレポジトリ名を指定してグラフを描く（`Diogenesthecynic-FullScreenMario-issuesCountChart.png`のようなファイルができる）。グラフのスタイルを修正したいときは、`issuesCountChart.pl`を編集する。

```
bash issuesCountChart.sh Diogenesthecynic FullScreenMario
```

## 自分で学ぶこと

* Gnuplot
