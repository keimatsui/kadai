# GitHubから複数ページにわたるデータを取得する方法

「[結果が大量にある場合の処理の原理」が面倒だから、それを自動化するスクリプトを書いた。このプログラムはPythonで書かれている。

## 方針

http://developer.github.com/libraries/ ではGitHub APIを利用するためのさまざまなライブラリが紹介されているが、ここでは、(1)データを取得しファイルに保存するだけの単純なプログラム`api.py`と、(2)ファイルからデータを読み出して処理するプログラム`jq`の2つの部分に分けることにする。そうしておけば、試行錯誤の過程で何度もGitHubにアクセスすることを避けられるし、シェルスクリプトなどを活用できるからである。(2)については「GitHub APIで取得したデータの処理方法」で説明する。

## 準備

PythonとHTTPアクセスのためのrequestsをインストールする。（requestsにはレスポンスヘッダのlink属性を読み取る機能が用意されている。http://www.python-requests.org/en/latest/user/advanced/#link-headers）

```
sudo apt-get install python python-setuptools
sudo easy_install requests
```

## api.pyの利用

「GitHub APIを使う練習」の作業の通り、`github.passwd`を作成しておく。

例1：https://github.com/Diogenesthecynic/FullScreenMario のオープンなissuesをすべて取得する。

今読んでいるファイルのあるディレクトリにある`api.py`を、作業ディレクトリにダウンロードしておく。

http://developer.github.com/v3/issues/#list-issues で紹介されている`GET /repos/:owner/:repo/issues`で試す。issueがたくさんある場合でも、`api.py`を使えばすべて取得できる。（`api.py`の1行ごとに何をやっているかを解読すれば、卒論でページを稼げるだろう。）

```
python api.py "https://api.github.com/repos/Diogenesthecynic/FullScreenMario/issues?per_page=100" > openissues.txt
```

`?per_page=100`は無くても動くが、一度に取得するデータを最大にしておくことで、API利用可能回数の消費を抑えたほうがよい。

例2：https://github.com/Diogenesthecynic/FullScreenMario のクローズドなissuesをすべて取得する。

```
python api.py "https://api.github.com/repos/Diogenesthecynic/FullScreenMario/issues?per_page=100&state=closed" > closedissues.txt
```

例3：https://github.com/jquery/jquery のコミット履歴をすべて取得する。

http://developer.github.com/v3/repos/commits/#list-commits-on-a-repository で紹介されている`GET /repos/:owner/:repo/commits`を使う。

```
python api.py "https://api.github.com/repos/jquery/jquery/commits?per_page=100" > commits.txt
```

例4：ユーザGenki966の活動履歴を取得する。

http://developer.github.com/v3/activity/events/#list-events-performed-by-a-user で紹介されている`GET /users/:user/events`を使う。

```
python api.py "https://api.github.com/users/Genki966/events?per_page=100" > events.txt
```
