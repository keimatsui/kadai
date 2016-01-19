# Mathematica

## 基本

https://lab.open.wolframcloud.com/objects/wpl/GetStarted.nb にアクセスする。

`1 + 1`と入力して，Shift+Enter。

`ImageIdentify[Import["http://www.unfindable.net/portrait.jpg"]]`と入力して，Shift+Enter。

このように，画像のURLを与えると，その中身を認識してくれる。

## Twitterアイコンの認識

### コンソールでの作業

`images.dat`をMathematicaのリストに変換する。

```
echo "urls = {" > images.m
head -n -1 images.dat | awk '{printf("\"%s\",\n",$0);}' >> images.m
tail -n 1 images.dat | awk '{printf("\"%s\"\n",$0);}' >> images.m
echo "};" >> images.m
```

`images.m`の内容を確認する。このファイルのサンプルがこのフォルダに置いてある。

### Wolfram Cloudでの作業

`images.m`の内容をWolfram Cloudに貼り付けてShift+Enter。

Wolfram Cloudで`Length[urls]`を評価して，30件あることを確認する。

`images = Import/@urls`を評価して，画像を取り込む。

`result=ImageIdentify/@images`を評価して，画像を認識させる。
