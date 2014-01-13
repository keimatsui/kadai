#コミットごとに全体の行数とtestの行数を数える方法

https://github.com/jquery/jquery を例に説明する。

##準備

PythonとGit、Pythonで日付を操作するモジュールpython-dateutilを使えるようにする。

```
sudo apt-get install python python-setuptools git
sudo easy_install python-dateutil
```

##リポジトリのクローンをローカルに作る

```
git clone https://github.com/jquery/jquery.git
```

この操作は時間がかかるから、`cp -r jquery jquery.original`などとして、バックアップを取っておくとよい。

##コミット一覧の取得

クローンがあれば、コミット一覧を作るのにAPIを使う必要は無い。

```
cd jquery
git log --pretty=format:"%H,%cd" --date=iso --first-parent --no-merges > ../jquery-commits.csv
cd ..
```

[Git: How to list commits on this branch but not from merged branches](http://stackoverflow.com/questions/10248137/git-how-to-list-commits-on-this-branch-but-not-from-merged-branches)を参考に，masterのfirst-parentだけのリストを作っている。

この結果中の日時は、タイムゾーンがばらばらで面倒くさい。

##行数の数え方

参考資料

* [ファイルの行数を数えるのは「wc -l file」ではありません](http://blog.unfindable.net/archives/6937)
* [ディレクトリ内のテキストファイルの総行数を求めるには](http://blog.unfindable.net/archives/6913)

例えば、コミット`95559f5117c8a21c1b8cc99f4badc320fd3dcbda`について、全体の行数（jquery/.git/は除外）とディレクトリ`test`以下の行数を数えたければ、次のようにすればよい。

```
#!/bin/bash
project='jquery'
hash='95559f5117c8a21c1b8cc99f4badc320fd3dcbda'
cd $project
git checkout -f $hash
cd ..
if [ -e $project/test ]; then
  echo $hash,$(grep -rI '' "$project" | grep -v "^$project/\.git/" | wc -l),$(grep -rI '' "$project/test" | wc -l)
else
  echo $hash,$(grep -rI '' "$project" | grep -v "^$project/\.git/" | wc -l),0
fi
```

##自動化

コミット一覧に載っているコミットすべてについて、練習で行った処理を繰り返すスクリプトを、`lineCountScriptCreator.py`を使って作る。コマンド`python`の2番目の引数（この例では`jquery`）は、リポジトリのディレクトリである。

```
cat jquery-commits.csv | python lineCountScriptCreator.py jquery
```

うまく行きそうだったら、`> jquery-count.sh`などとして保存して実行する。

```
bash jquery-count.sh > jquery-count-result.csv 2> jquery-error.log
```

終わったら`jquery-error.log`を見て問題が無いか確認する。（`git checkout`のエラーは、別ファイル`jquery-checkout-error.log`に記録している。）

##うまくいかないときは

最初からやり直す。

```
rm -rf jquery
cp -r jquery.original jquery
```
## グラフ表示

コマンドでグラフを描くための定番ツール、Gnuplotを使う。（詳しい使い方は自分で調べること。）

### 準備

```
sudo apt-get install gnuplot-x11
```

### 実験

Gnuplotでいつも同じデータファイルを使うように、ファイルをコピーしておく。

```
rm -f count-result.csv
cp jquery-count-result.csv count-result.csv
```

`gnuplot`としてGnuplotを起動し、以下のコマンドを試す。（本質は最後だけなのだが、ファイルがカンマ区切りである、横軸が時間である、日時の形式を指定しなければならないといったことへの対応が必要。）

```
set datafile separator ","
set xdata time
set timefmt "%Y-%m-%d %H:%M:%S"
plot 'count-result.csv' using 2:3 with lines, 'count-result.csv' using 2:4 with lines
```

### 形式

#### 線の太さ

```
plot 'count-result.csv' using 2:3 with lines lw 3, 'count-result.csv' using 2:4 with lines lw 3
```

#### 軸ラベル

```
set xl "date time"
set yl "lines"
replot
```

#### 凡例

```
plot 'count-result.csv' using 2:3 with lines lw 3 title 'total', 'count-result.csv' using 2:4 with lines lw 3 title 'test'
```

### ファイルへの出力

```
set terminal png
set out "lines.png"
replot
set terminal wxt
```

### Gnuplotの自動化

`lineCounter.pl`のようなファイルを作って、次のように自動化できる。

```
gnuplot lineCounter.pl
```

## さらに自動化

`lineCounter.sh`と`lineCounter.pl`で自動化する。リポジトリをクローンしてから、次のコマンドを入力するとグラフが描かれる。（クローンの部分はあえて分けている。）

```
bash lineCounter.sh リポジトリ名
```

## さらにさらに自動化

対象プロジェクトのリストを与えたら、そのすべてのグラフを描くようにすることもできる。

##自分で調べること

* `grep`
* `Gnuplot
