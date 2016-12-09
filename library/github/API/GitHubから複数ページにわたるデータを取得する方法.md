# GitHubから複数ページにわたるデータを取得する方法

「[結果が大量にある場合の処理の原理」が面倒だから、それを自動化するスクリプト[`api.py`](https://github.com/taroyabuki/yabukilab/blob/master/library/github/api.py)を書いた。このプログラムはPythonで書かれている。

## 方針

http://developer.github.com/libraries/ ではGitHub APIを利用するためのさまざまなライブラリが紹介されているが、ここでは、(1)データを取得しファイルに保存するだけの単純なプログラム`api.py`と、(2)ファイルからデータを読み出して処理するプログラム`jq`の2つの部分に分けることにする。そうしておけば、試行錯誤の過程で何度もGitHubにアクセスすることを避けられるし、シェルスクリプトなどを活用できるからである。(2)については「GitHub APIで取得したデータの処理方法」で説明する。

## 準備

「GitHub APIを使う練習」の作業の通り、`github.passwd`を作成しておく。

PythonとHTTPアクセスのためのrequestsをインストールする。（requestsにはレスポンスヘッダのlink属性を読み取る機能が用意されている。http://www.python-requests.org/en/latest/user/advanced/#link-headers）

```
sudo apt-get install python python-setuptools
sudo easy_install requests
```

api.pyをダウンロードする。

```
curl -s -u $(cat github.passwd) https://raw.githubusercontent.com/taroyabuki/yabukilab/master/library/github/api.py > api.py
```

## api.pyの利用

### 例1：Open issues

https://github.com/Diogenesthecynic/FullScreenMario のオープンなissuesをすべて取得する。

http://developer.github.com/v3/issues/#list-issues で紹介されている`GET /repos/:owner/:repo/issues`で試す。issueがたくさんある場合でも、`api.py`を使えばすべて取得できる。（`api.py`の1行ごとに何をやっているかを解読すれば、卒論でページを稼げるだろう。）

```
python api.py "https://api.github.com/repos/Diogenesthecynic/FullScreenMario/issues?per_page=100" > openissues.txt
```

`?per_page=100`は無くても動くが、一度に取得するデータを最大にしておくことで、API利用可能回数の消費を抑えたほうがよい。

`>`はリダイレクト（すでに自分で調べているはず）。ここではファイル`openissues.txt`に結果を保存している。APIでデータを取得するのには時間がかかるため、このように、一度ファイルに保存して置くのがいいだろう（つまり、`python api.py URI | ./jq...`のように、次の処理に直接渡さない。）

### 例2：Closes issues

https://github.com/Diogenesthecynic/FullScreenMario のクローズドなissuesをすべて取得する。

```
python api.py "https://api.github.com/repos/Diogenesthecynic/FullScreenMario/issues?per_page=100&state=closed" > closedissues.txt
```

### 例3：コミット履歴

https://github.com/jquery/jquery のコミット履歴をすべて取得する。

http://developer.github.com/v3/repos/commits/#list-commits-on-a-repository で紹介されている`GET /repos/:owner/:repo/commits`を使う。

```
python api.py "https://api.github.com/repos/jquery/jquery/commits?per_page=100" > commits.txt
```

### 例4：活動履歴

ユーザGenki966の活動履歴を取得する。

http://developer.github.com/v3/activity/events/ によれば、eventsは`per_page=30`固定、トータル300件しか取れない。http://developer.github.com/v3/activity/events/#list-events-performed-by-a-user で紹介されている`GET /users/:user/events`を使う。

```
python api.py "https://api.github.com/users/Genki966/events" > events.txt
```

例として、taroyabukiの活動履歴を2013/11/19の18時に取得した結果`events-yabuki-20131119.txt`を置いておく。

### 例5：検索

「minecraft+mod」で検索する。

https://developer.github.com/v3/search/#search-repositories で紹介されている`GET /search/repositories`を使う。

```
python api.py "https://api.github.com/search/repositories?q=minecraft+mod&per_page=100" > repositories.txt
```

ただし、この方法では1000件（100件 * 10ページ）までしか結果を取得できない（https://api.github.com/search/repositories?q=minecraft+mod&per_page=100&page=11 で試すとわかる）。