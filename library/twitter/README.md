# Twitter APIの使い方

Streaming APIの応用についてはフォルダ`streaming`を参照。

## Step 0（OAuth認証）

https://apps.twitter.com/ で新しいアプリを作り，OAuth認証に必要な以下の4項目を取得する。

* consumer_key
* consumer_secret
* access_token
* access_token_secret

`step0.template.py`を`step0.py`にコピーし，`step0.py`に上記4項目を記述する。**注意：`step0.py`を公開の場においてはいけない。**

`python step0.py`として実行する。`step0.py`は「東京」というキーワードでツイートを検索するプログラムである。検索（`search`）以外にどういう機能があるかはhttp://docs.tweepy.org/en/v3.5.0/api.html を見ればわかる。

## Step 1（認証情報の管理）

プログラムに毎回認証情報を書くのは面倒だから，`auth.py`に書いて使い回すことにする（`auth.template.py`がテンプレになっている）。**注意：`auth.py`を公開の場においてはいけない。**

`python step1.py`として実行する。`step1.py`でやっているのは`step0.py`と同じことである。結果は意味不明だが，角括弧`[]`で囲われているから**リスト**だということはわかる。（このことはhttp://tweepy.readthedocs.io/en/v3.5.0/api.html#API.search の「Return type」からもわかる。）

## Step 2（リストの処理）

`python step2.py`でリストの先頭要素だけを取り出してみる。

## Step 3（結果の整形）

Tweepyがツイートを管理するオブジェクトは，`_json'という属性を持っていて，そこにツイートについて得られるすべての情報がJSONという形式で格納されている（これがAPIで帰ってきたデータである）。これを出力する。ただし，そのままだとjqで扱えないから，`json_dumps`で修正する。

`python step3.py > one`として，結果をファイル`one`に書き込む（APIの利用回数を節約するため）。`cat one`で結果を確認する。

`cat one | jq '.'`とすると，JSON文字列が整形して表示される。（`jq '.' one`でもいい。）

## Step 4（リストの処理）

リストのすべての要素に対して同じ処理をする。

`python step4.py > all'で結果のすべてが`all`に記録される。

ここからは，APIを呼び出さず，ファイル`one`や`all`に書かれたデータを処理する。

## Step 5（標準入力の処理）

ファイルを直接開くのではなく，標準入力を扱うようにしておくと便利。まずは，ファイルの内容をそのまま表示してみる。

`python step5.py < one`や`cat all | python step5.py`で試す。

## Step 6（必要な情報の抽出）

JSON文字列をオブジェクトに変換し，必要な情報だけを取りだしてみる。ここでは例として，つぶやきのIDとつぶやいた日時，つぶやいた人を取り出し，カンマ区切りで出力する。

## Step 7（結果が大量にある場合）

あとで書く
