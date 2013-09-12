# GitHub APIの試し方

Ubuntuを使う。

`Ctrl + Alt + T`で端末を起動する。

## 準備（この作業は1回だけやればいい）

curlをインストールする。`curl`については自分で調べること。

```
sudo apt-get install curl
```

ログイン情報を何度も入力するのは面倒だから、ユーザ名とパスワードをファイル`github.passwd`に書いておく（ユーザ名とパスワードの間は半角のコロン）。`echo`や`>`については自分で調べること（キーワードに「シェルスクリプト」や「bash」を入れると）探しやすい。

```
echo 'ユーザ名:パスワード' > github.passwd
chmod 600 github.passwd
```

正しく書けたことを確認する。`cat`については自分で調べること。

```
cat github.passwd
```

## APIの利用

例として、http://developer.github.com/v3/issues/ で紹介されているAPIを試す。`$(コマンド)`の部分は、コマンドの実行結果で置き換えられることに注意。

```
curl -u $(cat github.passwd) https://api.github.com/issues
```

この結果を処理するためにはプログラムを書かなければならないが、内容を取り出す程度の簡単な処理なら次のようにパイプで実現できる。`|`や`grep`については自分で調べること。

```
curl -u $(cat github.passwd) https://api.github.com/issues | grep title
```
