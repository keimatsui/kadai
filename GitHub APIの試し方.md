# GitHub APIの試し方

## 準備

Ubuntuを使う。

### 必要なソフトウェア

必要なソフトウェアを準備する。

```
sudo apt-get install curl
```

### GitHubのログイン情報

ログイン情報を何度も入力するのは面倒だから、コマンドプロンプトで以下のようなコマンドを実行し、ログイン情報を環境変数に保存する（GitHubのユーザ名とパスワードを半角のコロンで区切って記述する）。

```
export GITHUBPASS=ユーザ名:パスワード
```

次のコマンドで、環境変数が正しく設定されていることを確認する。

```
echo $GITHUBPASS
```

## APIの利用

例として、http://developer.github.com/v3/issues/ で紹介されているAPIを試す。

```
curl -u $GITHUBPASS https://api.github.com/issues
```

この結果を処理するためにはプログラムを書かなければならないが、内容を取り出す程度の簡単な処理なら次のようにパイプで実現できる。

```
curl -u $GITHUBPASS https://api.github.com/issues | grep title
```
