# 画像認識

画像認識の方法を二つ紹介する。

* Wolfram Cloud
* Docomo Developer Support

## Wolfram Cloud

### 基本

https://lab.open.wolframcloud.com/objects/wpl/GetStarted.nb にアクセスする。

`1 + 1`と入力して，Shift+Enter。

`ImageIdentify[Import["http://www.unfindable.net/portrait.jpg"]]`と入力して，Shift+Enter。

このように，画像のURLを与えると，その中身を認識してくれる。

### Twitterアイコンの認識

#### コンソールでの作業

https://github.com/yabukilab/main/tree/master/library/twitter/streaming が途中まで終わっていて，リツイートされたツイートがデータベースに入っているとする。

データベースからリツイート数が2000を超えるツイートをした人のアイコンを30件取得する。（30件に限定しているのは，Wolfram Cloudの時間制限を超えないようにするため。）

```
echo "select distinct profileImageUrl from retweets join users on retweets.retweeted=users.id where rcount>2000 limit 30;" | mysql -uroot -ppass --skip-column-names twitter > images.dat
```

`images.dat`の内容を確認する。

`images.dat`をMathematicaのリストに変換する。

```
echo "urls = {" > images.m
head -n -1 images.dat | awk '{printf("\"%s\",\n",$0);}' >> images.m
tail -n 1 images.dat | awk '{printf("\"%s\"\n",$0);}' >> images.m
echo "};" >> images.m
```

`images.m`の内容を確認する。サンプルをこのフォルダに置いておく。

#### Wolfram Cloudでの作業

`images.m`の内容をWolfram Cloudに貼り付けてShift+Enter。

Wolfram Cloudで`Length[urls]`を評価して，30件あることを確認する。

`images = Import/@urls`を評価して，画像を取り込む。

`result=ImageIdentify/@images`を評価して，画像を認識させる。
