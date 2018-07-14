# ページをスクロールしてからHTMLを保存するスクリプト

[scroll.py](scroll.py)

## 概要

実行例は次のとおり。

```bash
echo "https://www.amazon.co.jp/gp/profile/amzn1.account.AFBQH3A3M6XHMLQ6GAZW5ERC3I6Q/" | python scroll.py > AFBQH3A3M6XHMLQ6GAZW5ERC3I6Q.html
```

補足：こうして得られたHTMLの処理方法は、[ここ](../amazon)で紹介している。

## 解説

https://www.amazon.co.jp/gp/profile/amzn1.account.AFBQH3A3M6XHMLQ6GAZW5ERC3I6Q を例に、下まで行くと続きが表示されるようなページを取得する方法を考える。

[スクレイピングの準備（Seleniumのインストール）](README.md)をしてから先に進む。

ページを取得し、サイズを調べる。

```python
driver.get('https://www.amazon.co.jp/gp/profile/amzn1.account.AFBQH3A3M6XHMLQ6GAZW5ERC3I6Q')
content = driver.page_source
print(len(content))
```

画面をスクロールし、5秒待ち、ページサイズを調べる。

```python
import time

driver.execute_script('window.scrollTo(0, document.body.scrollHeight);')
time.sleep(5)
content = driver.page_source
print(len(content))
```

ページサイズが変わっていたら、スクロールして新しいデータが追加されたということである。

以上をまとめたのが[scroll.py](scroll.py)である。

* 無限に続けられると困るから、スクロールは50回までにしている。
* 途中で例外が発生したら、エラーメッセージを表示し、それまでに取得したものを出力するようにしている。
