# 画像認識

画像認識の方法を二つ紹介する。

* Mathematica (Wolfram Cloud)
* Docomo Developer Support

## 準備

https://github.com/yabukilab/main/tree/master/library/twitter/streaming が途中まで終わっていて，リツイートされたツイートがデータベースに入っているとする。

データベースからリツイート数が2000を超えるツイートをした人のアイコンを30件取得する。（30件に限定しているのは，Wolfram Cloudの時間制限を超えないようにするため。Raspberry Piなど，ローカルでMathematicaが動く環境があるならもっと多くしてもよい。）

```
echo "select distinct profileImageUrl from retweets join users on retweets.retweeted=users.id where rcount>2000 limit 30;" | mysql -uroot -ppass --skip-column-names twitter > images.dat
```

`images.dat`の内容を確認する。このファイルのサンプルがこのフォルダに置いてある。
