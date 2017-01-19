# GitHub APIを使う練習

実用的ではないが、感覚をつかむためにこの練習をやってみるといい。

## APIの利用

例として、http://developer.github.com/v3/issues/ で紹介されているAPIを試す。https://github.com/Diogenesthecynic/FullScreenMario のissuesを取得したいときは、次のようにAPIをたたけばよい。

```
curl -s -u ユーザ名:パスワード "https://api.github.com/repos/Diogenesthecynic/FullScreenMario/issues"
```

### ログイン情報入力を省略する方法

ログイン情報を何度も入力するのは面倒だし、画面に表示させるのもいやだから、ユーザ名とパスワードをファイル`github.passwd`に書いておく（ユーザ名とパスワードの間は半角のコロン）。`echo`や`>`（リダイレクト）については自分で調べること（キーワードに「シェルスクリプト」や「bash」を入れると）探しやすい。

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
curl -s -u $(cat github.passwd) "https://api.github.com/repos/Diogenesthecynic/FullScreenMario/issues"
```

## API呼び出し結果の処理

この結果を処理するためにはプログラムを書かなければならないが、内容を取り出す程度の簡単な処理なら次のようにパイプで実現できる。`|`（パイプ）や`grep`については自分で調べること。

```
curl -s -u $(cat github.passwd) "https://api.github.com/repos/Diogenesthecynic/FullScreenMario/issues" | grep title
```

## 自分で調べること

* `curl`
* `echo`
* `>`（リダイレクト）
* `cat`
* `|`（パイプ）
* `grep`