# GitHub APIの試し方

Ubuntuを使う。

`Ctrl + Alt + T`で端末を起動する。

## 準備（この作業は1回だけやればいい）

curlをインストールする。`curl`については自分で調べること。

```
sudo apt-get install curl
```

## APIの利用

例として、http://developer.github.com/v3/issues/ で紹介されているAPIを試す。https://github.com/Diogenesthecynic/FullScreenMario のissuesを取得したいときは、次のようにAPIをたたけばよい。

```
curl -s -u ユーザ名:パスワード https://api.github.com/repos/Diogenesthecynic/FullScreenMario/issues
```

### ログイン情報入力を省略する方法

ログイン情報を何度も入力するのは面倒だし、画面に表示させるのもいやだから、ユーザ名とパスワードをファイル`github.passwd`に書いておく（ユーザ名とパスワードの間は半角のコロン）。`echo`や`>`については自分で調べること（キーワードに「シェルスクリプト」や「bash」を入れると）探しやすい。

```
echo 'ユーザ名:パスワード' > github.passwd
chmod 600 github.passwd
```

正しく書けたことを確認する。`cat`については自分で調べること。

```
cat github.passwd
```

ログイン情報をファイルに書き込んだら、次のように利用する。`$(コマンド)`の部分は、コマンドの実行結果で置き換えられることに注意。

```
curl -s -u $(cat github.passwd) https://api.github.com/repos/Diogenesthecynic/FullScreenMario/issues
```

## API呼び出し結果の処理

この結果を処理するためにはプログラムを書かなければならないが、内容を取り出す程度の簡単な処理なら次のようにパイプで実現できる。`|`や`grep`については自分で調べること。

```
curl -s -u $(cat github.passwd) https://api.github.com/repos/Diogenesthecynic/FullScreenMario/issues | grep title
```

### 結果が大量にある場合

結果が大量にある場合、一度のAPI呼び出しで、そのすべてを取得することはできない。結果の続きの取得先がレスポンスヘッダに書かれるから、それを見ればよい。

まず、次のように、結果を`result.txt`に、レスポンスヘッダを`header.txt`に書き込む。

```
curl -s -u $(cat github.passwd) -D header.txt https://api.github.com/repos/Diogenesthecynic/FullScreenMario/issues > result.txt
```

結果の続きは、`header.txt`の`Link`に書かれている（`cat header.txt`でもよい）。

```
grep Link: header.txt
```

`rel="next"`のURLを見て、結果の続きを`result.txt`に追記する。

```
curl -s -u $(cat github.passwd) -D header.txt https://api.github.com/repositories/7814621/issues?page=2 >> result.txt
```

もう一度、header.txtを見て、`rel="next"`のURLがなければ終了である。

```
grep Link: header.txt
```

結果をためた`result.txt`から、タイトルだけを取り出す。

```
grep title result.txt
```

おまけ：issueの件数を調べて、ウェブサイト上の数字と合っていることを確認する。`wc`については自分で調べること。

```
grep title result.txt | wc -l
```
