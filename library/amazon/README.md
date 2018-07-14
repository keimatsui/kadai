# Amazonでのスクレイピング

## 概要

スクレイピングは大きく分けて次の二つがある。

1. 静的なHTML解析でよいもの　例：指定した商品についてのレビューをすべて取得する。https://www.amazon.co.jp/product-reviews/4822298930/
1. 静的なHTML解析ではだめなもの　例：指定したユーザが書いたレビューをすべて取得する。https://www.amazon.co.jp/gp/profile/amzn1.account.AFBQH3A3M6XHMLQ6GAZW5ERC3I6Q/ （ブラウザで開いた直後はレビューが表示されず、スクロールすると表示される。HTMLを読むだけではだめで、JavaScriptの実行結果を読まなければならない。）

1は[ASINで指定したアイテムのレビューをすべて取得するスクリプト](reviewsofasin.py)で解決する。実行例は次のとおり。（このようなスクリプトの書き方を下で説明する。）

```bash
echo 4822298930 | python reviewsofasin.py >> reviews.csv
```

2は[ページをスクロールしてからHTMLを保存するスクリプト](../スクレイピング/scroll.md)と、[HTMLから必要な情報を抽出するスクリプト](reviewsofuser.py)で解決する。実行例は次のとおり。

```bash
echo "https://www.amazon.co.jp/gp/profile/amzn1.account.AFBQH3A3M6XHMLQ6GAZW5ERC3I6Q/" | python scroll.py > AFBQH3A3M6XHMLQ6GAZW5ERC3I6Q.html
echo AFBQH3A3M6XHMLQ6GAZW5ERC3I6Q | python profile.py >> profiles.csv
```

## 静的なHTML解析でよいもの

https://www.amazon.co.jp/product-reviews/4822298930/ のレビューをすべて取得してみよう。

いきなり長いプログラムを書いてはいけない。Pythonのインタラクティブシェルで少しずつ試す。Anaconda Promptまたはvagrant sshで`python`として、インタラクティブシェルを起動する。

Pythonでのスクレイピングは、BeautifulSoupというライブラリで行うのが便利。

（参考文献の1.2節をやってみる。あるいは、BeautifulSoupで検索し、簡単な例を試してみる。そうすると、とっかかりは次のようなコードになることがわかる。）

```python
import requests
from bs4 import BeautifulSoup

url = 'https://www.amazon.co.jp/product-reviews/4822298930/'
response = requests.get(url)
soup = BeautifulSoup(response.content, 'html.parser')
```

### 要素の取得方法

要素の取得方法は、大きく分けて次の四つがある。（`find`と`select`、`find_all`と`select_all`、`find_one`と`select_one`のようになっていればわかりやすかったのだが。）

1. `find(タグ, その他の条件)`：条件に合う最初の要素を返す。タグと他の条件のどちらかは省略可。
1. `find_all(タグ, その他の条件)`：条件に合うすべての要素のリストを返す。
1. `select_one(セレクタ)`：セレクタにマッチする最初の要素を返す。
1. `select(セレクタ)`：セレクタにマッチするすべての要素のリストを返す。

CSSに慣れているなら`select_one`と`select`を使うのが簡単だが、これらではうまく選択できないものもある（例：後に出てくる`data-hook`属性値での選択）。

### レビューの読み取り

https://www.amazon.co.jp/product-reviews/4822298930/ をChromeかFirefoxで開き、Ctrl+Shift+Iとすると開発者ツールが起動する。左上の「インスペクター」を使って、ページ上の要素を調べると、`<div ... data-hook="review">...</div>`が1件のレビューに相当することがわかる。

**わかってから先に進む**

そうすると、すべてのレビューは次のように取得できる。（`soup.find_all('div', attrs={'data-hook':'review'})`でもいいが、このページで`data-hook="review"`になっているのはdiv要素しかなさそうだから、`'div',`は省略できる。）

```python
reviews = soup.find_all(attrs={'data-hook':'review'})
```

何件あるか。

```python
len(reviews)
```

すべてのレビュー（`reviews`）から、レビューを1件ずつ、`review`という名前で取り出して処理することになる。とりあえずは、最初のものだけ処理してみる。

```python
review = reviews[0]
```

インスペクターで見ると、レビューのタイトルは`data-hook="review-title"`という属性を持つ要素であることがわかる。要素の中身（テキスト）を取り出す。

```python
review.find(attrs={'data-hook':'review-title'}).text
```

インスペクターで見ると、レビューの著者は`<a href="**A**", data-hook="review-author">**B**</a>`のhref属性（`**A**`）や中身（`**B**`）の部分であることがわかる。これらを取り出してみる。

```python
tmp = review.find('a', attrs={'data-hook':'review-author'})
tmp.get('href')
```

これをレビュアーのIDと見なしていいだろう。レビュアーの表示名を確認する。

```python
tmp.text
```

あとは、[reviewsofasin.py](reviewsofasin.py)を見ること。

### 複数ページの場合

レビューが複数ページにわたっているときは、次のページを読まなければならない。

インスペクターで見ると、次のページは`class="a-last"`という要素の中のa要素のhref属性に書かれていることがわかる。これまで同様`find`で取り出してもいいが、CSSセレクタを使う方法も試してみよう。

```python
nextPage = soup.select_one('.a-last a')
```

`select_one`の結果がNoneでなければ、href属性を取り出す。

```python
nextPageUrl = None if nextPage is None else nextPage.get('href')
```

nextPageUrlがNoneでない限り、`nextPageUrl`を読み込んで処理する作業を繰り返せば良い。（詳細は[reviewsofasin.py](reviewsofasin.py)を参照）


## 静的なHTML解析ではだめなもの

https://www.amazon.co.jp/gp/profile/amzn1.account.AFBQH3A3M6XHMLQ6GAZW5ERC3I6Q/ のような、スクロールすると続きが表示されるページは、上述の静的解析ではうまくいかない。

そこで、まず[ページをスクロールしてからHTMLを保存するスクリプト](../スクレイピング/scroll.md)でHTMLを保存し、それを読み込んで解析する。

```bash
echo "https://www.amazon.co.jp/gp/profile/amzn1.account.AFBQH3A3M6XHMLQ6GAZW5ERC3I6Q/" | python scroll.py > AFBQH3A3M6XHMLQ6GAZW5ERC3I6Q.html
```

解析方法は上と同じだが、もう一度やってみよう。https://www.amazon.co.jp/gp/profile/amzn1.account.AFBQH3A3M6XHMLQ6GAZW5ERC3I6Q をブラウザで開き、Ctrl+Shift+I、インスペクターを見ながら実験する。

インタラクティブシェルを使う。

```python
from bs4 import BeautifulSoup
import re

amazon = 'https://www.amazon.co.jp'

userId = 'AFBQH3A3M6XHMLQ6GAZW5ERC3I6Q'
file = open(userId + '.html', 'r')
soup = BeautifulSoup(file, 'html.parser')
```

著者名と著者URL

```python
authorName = soup.select_one('.name-container').text
authorUrl = '{}/gp/profile/amzn1.account.{}/'.format(amazon, userId)
[authorName, authorUrl]
```

class属性`reviews-card`の要素が1件のレビューに相当することが、インスペクターでわかる。

**わかってから先に進む**

レビューを取得し、件数を表示する。

```python
reviews = soup.select('.reviews-card')

len(reviews)
```

ここで表示されるレビューの数と、ウェブページに書かれているレビューの数が同じでなければならない。

1件目のレビューで調べる。

```python
review = reviews[0]
```

日付は「・・・(0000)年(00)月(00)日」の、丸括弧の部分を取り出せばよい。こういうのは正規表現を使うのが簡単。

```python
date = review.select_one('.a-profile-descriptor').text
date = re.sub('.*?([0-9]*)年([0-9]*)月([0-9]*)日', r'\1/\2/\3', date)
date
```

評価は「星5つのうち5.0」などと書かれている部分の「5.0」だけ残して、整数に変えればよい。

```python
rating = int(review.select_one('.a-icon-alt').text.replace('星5つのうち', ''))
rating
```

「Amazonで購入」の部分があるかどうかを調べる。

```python
badge = 'Amazonで購入' if review.select_one('.profile-at-review-badge') is not None else ''
badge
```

タイトルと本文は取り出すだけだが、一応、二重引用符をエスケープしておく。表示される本文（の長さ）が、ウェブで開いたものと違うようだ。□要調査

```python
title = review.select_one('.profile-at-review-title').text.replace('"', '\"')
body = review.select_one('p').text.replace('"', '\"')
[title, body]
```

「レビュー全文を表示する」のURLがレビューのURLである。

```python
reviewUrl = amazon + review.select_one('.profile-at-review-link').get('href')
reviewUrl
```

「参考になった投票 5」のような記述があれば、最後の数字だけ残す。

```python
tmp = review.select_one('.profile-at-review-helpful-message-text')
vote = 0 if tmp is None else int(tmp.text.replace('参考になった投票 ', ''))
vote
```

商品のURL（2番目のa要素）の`dp/....?`の部分がASINである。正規表現で取り出す。商品が無くなっていると2番目のa要素が無いから、そのチェックをしておく。

```python
tmp = review.find_all('a')
asin = '' if len(tmp) < 2 else re.sub('.*/dp/(.*)\?.*', r'\1', tmp[1].get('href'))
asin
```

以上をまとめたのが[profile.py](profile.py)である。