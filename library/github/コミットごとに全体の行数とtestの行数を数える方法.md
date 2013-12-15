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
bash jquery-count.sh > jquery-count-result.csv 2> error.log
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
