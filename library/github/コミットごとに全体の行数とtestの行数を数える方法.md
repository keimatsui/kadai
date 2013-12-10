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

###方法1

`grep -I '' -r "jquery/"* | wc -l`で行数を数える。`grep`については自分で調べること。`.git`のような`.`で始まる隠しディレクトリは自動的に除外される（それを数えなければならない場合には困ったことになる）。シンボリックリンクが無い場合にエラーが表示されるが、気にしないことにする。

###方法2（不採用）

`find ディレクトリ -type f`でファイルを列挙し、そのファイルを`cat`で表示、`wc -l`で行数を数える。jquery/test以下のファイルの行数は`cat $(find jquery -type f) | wc -l`で数えられるが、jquery以下のファイルの行数を数えるときは、`cat $(find jquery -type f | grep -v /.git/) | wc -l`などとして、列挙したファイルから`.git`にあるものを削除する。

ただしこの方法には、バイナリファイルも対象にしてしまうという欠点があるため、ここでは使わない。（バイナリファイルも評価した方がいいかもしれないが、そうだとしても評価基準は行数ではない。）

##練習

例えば、コミット`372e04e78e81cc8eb868c5fc97f271a695569aa5`について、全体の行数とディレクトリ`test`以下の行数を数えたければ、次のようにすればよい。

```
#!/bin/sh
project='jquery'
hash="372e04e78e81cc8eb868c5fc97f271a695569aa5"
cd $project
git checkout -f $hash
cd ..
if [ -e $project/test ]; then
  echo $hash,$(grep -I '' -r "$project/"* | wc -l),$(grep -I '' -r "$project/test/"* | wc -l)
else
  echo $hash,$(grep -I '' -r "$project/"* | wc -l),0
fi
```

##自動化

コミット一覧に載っているコミットすべてについて、練習で行った処理を繰り返すスクリプトを、`lineCountScriptCreator.py`を使って作る。コマンド`python`の2番目の引数（この例では`jquery`）は、リポジトリのディレクトリである。

```
cat jquery-commits.csv | python lineCountScriptCreator.py jquery
```

うまく行きそうだったら、`> jquery-count.sh`などとして保存して実行する。

```
sh jquery-count.sh > jquery-count-result.csv 2> error.log
```

終わったら`error.log`を見て問題が無いか確認する。（`git checkout`のエラーは、別ファイル`jquery-error.log`に記録している。）

##うまくいかないときは

最初からやり直す。

```
rm -rf jquery
cp -r jquery.original jquery
```

##自分で調べること

* `grep`
