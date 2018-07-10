# Amazonでのスクレイピング

* reviewofasin.py: ASINで指定したアイテムのレビューをすべて取得する

## 参考文献

* クジラ飛行机『Pythonによるスクレイピング＆機械学習』（ソシム, 2016）（研究室蔵書）

## 解説

スクレイピングは、大きく分けて次の二つがある。

* 静的なHTMLを解析すればいいもの　例：指定した商品についてのレビューをすべて取得する。https://www.amazon.co.jp/product-reviews/4822298930/
* 静的なHTMLを解析ではうまく行かないもの　例：指定したユーザが書いたレビューをすべて取得する。https://www.amazon.co.jp/gp/profile/amzn1.account.AFBQH3A3M6XHMLQ6GAZW5ERC3I6Q/ （ブラウザで開いた直後はレビューが表示されず、スクロールすると表示される。HTMLを読むだけではだめで、JavaScriptの実行結果を読まなければならない。）

順番に説明する。


### 静的なHTMLを解析すればいいもの

#### 準備

https://www.amazon.co.jp/product-reviews/4822298930/ のレビューをすべて取得してみよう。

いきなり長いプログラムを書いてはいけない。Pythonのインタラクティブシェルで少しずつ試す。Anaconda Promptまたはvagrant sshで`python`として、インタラクティブシェルを起動する。（JupyterLabやJupyter Notebookでもよい。）

Pythonでのスクレイピングは、BeautifulSoupというライブラリで行うのが便利。

（参考文献の1.2節をやってみる。あるいは、BeautifulSoupで検索し、簡単な例を試してみる。そうすると、とっかかりは次のようなコードになることがわかる。）

```python
import requests
from bs4 import BeautifulSoup

url = 'https://www.amazon.co.jp/product-reviews/4822298930/'
response = requests.get(url)
soup = BeautifulSoup(response.content, 'html.parser')
```

#### レビューの読み取り

https://www.amazon.co.jp/product-reviews/4822298930/ をChromeかFirefoxで開き、Ctrl+Shift+Iとすると開発者ツールが起動する。左上の「インスペクター」を使って、ページ上の要素を調べると、`<div ... data-hook="review">...</div>`が1件のレビューに相当することがわかる。

そうすると、すべてのレビューは次のように取得できる。

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

インスペクターで見ると、レビューのタイトルは`data-hook="review-title"`であることがわかる。要素の中身（テキスト）を取り出してみる。

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

あとは、reviewsofasin.pyを見ること。

#### 複数ページの場合

レビューが複数ページにわたっているときは、次のページを読まなければならない。

インスペクターで見ると、次のページは`class="a-last"`という要素の中のa要素のhref属性に書かれていることがわかる。これを次のように取り出す。

```python
nextPage = soup.select('.a-last a')
```

`select`の結果はリストだから、その要素があるかどうかを調べれば、次のページがあるかどうかがわかる。

```python
nextPageUrl = None
if len(nextPage) != 0:
    nextPageUrl = nextPage[0].get('href')
```

nextPageUrlがNoneでない限り、`nextPageUrl`を読み込んで処理する作業を繰り返せば良い。（詳細はreviewsofasin.pyを参照）

### 静的なHTMLを解析ではうまく行かないもの

あとで書く