# アマゾンのレビューを取得する

レビューのURLは、APIで取得してもいいが、少し試してみれば、http://www.amazon.co.jp/product-reviews/400339481X/ のようなURLを作ればよいことがわかる。そこで、HTTPクライアントを書いてレビューを取得することにする。

## 取得するもの

**APIではないから、ウェブサイトの書き方が変わればプログラムを書き換えなければならない。**

http://www.amazon.co.jp/product-reviews/400339481X/ をChromeで開き、デベロッパーツールを使って以下のことを確認する。

1. レビューは`a-section review`というclass属性値を持っている。
1. HTMLを`class="a-section review"`という文字列で分割すれば、レビューを1件ずつ処理できる。
1. "a-section review"という文字列が2個あるとする。最初の文字列の前の部分で最初に出てくる、正規表現`5つ星のうち\\s*([0-9|\\.]*)`にマッチする文字列を見れば、平均評価がわかる。それ以降の部分（2カ所）にレビューがある。レビューは合計2件。レビュー自体が評価されていれば、正引き表現`([0-9]*)\\s*人中、([0-9]*)\\s*人の方が.*?5つ星のうち\\s*([0-9|\\.]*)`にマッチする。そうでなければ正規表現`5つ星のうち\\s*([0-9|\\.]*)`にマッチする。

以上をプログラムで表現する。

## Java

### サンプル3

ASINで指定したアイテムのレビューを取得する。（1ページ限定）（矢吹の本の5.2.2項を参照）

1. パッケージ`com.amazon.associates.sample`の中にクラス`Sample3`を作り、実行する。

コンソールでASINを実行時に指定して、結果をファイルに書くこむときは、

```
mvn compile
asin=4873115655
mvn exec:java -quiet -Dexec.mainClass="com.amazon.associates.sample.Sample3" -Dexec.args="$asin" > $asin
```

### サンプル4（古い）

ASINで指定したアイテムのレビューを取得・記憶し、重み付き評価値を求める。（1ページ限定）

1. パッケージ`com.amazon.associates.sample`の中にクラス`Review`を作る。
1. パッケージ`com.amazon.associates.sample`の中にクラス`Sample4`を作り、実行する。

コンソールでASINを実行時に指定して、結果をファイルに書くこむときは、

```
mvn compile
asin=4873115655
mvn exec:java -quiet -Dexec.mainClass="com.amazon.associates.sample.Sample4" -Dexec.args="$asin" > $asin
```

### サンプル5（古い）

ASINで指定したアイテムのレビューを取得・記憶し、重み付き評価値を求める。（複数ページ対応）

1. パッケージ`com.amazon.associates.sample`の中にクラス`Sample5`を作り、実行する。

コンソールでASINを実行時に指定して、結果をファイルに書くこむときは、

```
mvn compile
asin=4873115655
mvn exec:java -quiet -Dexec.mainClass="com.amazon.associates.sample.Sample5" -Dexec.args="$asin" > $asin
```

503エラーがあると再接続するが、本当に問題がある場合に止まらなくなるため、おかしいときは手動で止めること。
