# 画像認識

画像認識の方法を二つ紹介する。

* Mathematica (Wolfram Cloud)
* Docomo Developer Support（使えない）

## 準備

https://github.com/yabukilab/main/tree/master/library/twitter/streaming が途中まで終わっていて，リツイートされたツイートがデータベースに入っているとする。

リツイート数が2000より多いものに限定して，リツイート数とそのツイートをしたユーザーのアイコンのURLのリストを作る。

```
echo "select rcount,profileImageUrl from retweets join users on retweets.retweeted=users.id where rcount>2000;" | mysql -uroot -ppass --skip-column-names twitter > images.dat
```

`images.dat`の内容を確認する。このファイルのサンプルがこのフォルダに置いてある。
## Mathematica

### 基本

https://lab.open.wolframcloud.com/objects/wpl/GetStarted.nb にアクセスする。

`1 + 1`と入力して，Shift+Enter。

`ImageIdentify[Import["http://www.unfindable.net/portrait.jpg"]]`と入力して，Shift+Enter。

このように，画像のURLを与えて，その画像の中身を認識させられる。

### Twitterアイコンの認識

#### コンソールでの作業

Wolfram Cloudには実行時間の制限があるため，多くの画像をまとめて処理することはできない。そこで，images.datを30件ずつに分ける。

'''注意：`x`で始まる名前のファイルを一時ファイルにするため，作業ディレクトリにそのようなファイルを置かないように。'''

```
rm -f x*
split -l 30 images.dat
```

`x`で始まる名前のファイルが大量にできていることを，`ls`で確認する。

データをMathematicaのコマンドに変換する。

```
for file in x*
do
  echo "urls = {" > $file.m
  head -n -1 $file | awk '{printf("\"%s\",\n",$2);}' >> $file.m
  tail -n 1 $file | awk '{printf("\"%s\"\n",$2);}' >> $file.m
  echo "};" >> $file.m
done
```

### Wolfram Cloudでの作業

`x`で始まり`.m`で終わるファイルがMathematica用のファイルである。この内容をWolfram Cloudに貼り付けてShift+Enter。

Wolfram Cloudで`Length[urls]`を評価して，30件あることを確認する。

`images = Import/@urls`を評価して，画像を取り込む。

`result = Map[ImageIdentify, images]`を評価して，画像を認識させる。

認識結果が正しいかどうかを確認する。
