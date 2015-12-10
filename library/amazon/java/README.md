# アマゾンのレビューを取得する

## クローラ

レビューのURLは、APIで取得してもいいが、少し試してみれば、http://www.amazon.co.jp/product-reviews/400339481X/ のようなURLを作ればよいことがわかる。そこで、HTTPクライアントを書いてレビューを取得することにする。

### 取得するもの

**APIではないから、ウェブサイトの書き方が変わればプログラムを書き換えなければならない。**

http://www.amazon.co.jp/product-reviews/400339481X/ をChromeで開き、デベロッパーツールを使って以下のことを確認する。

1. レビューは`a-section review`というclass属性値を持っている。
1. HTMLを`class="a-section review"`という文字列で分割すれば、レビューを1件ずつ処理できる。
1. "a-section review"という文字列が2個あるとする。最初の文字列の前の部分で最初に出てくる、正規表現`5つ星のうち\\s*([0-9|\\.]*)`にマッチする文字列を見れば、平均評価がわかる。それ以降の部分（2カ所）にレビューがある。レビューは合計2件。レビュー自体が評価されていれば、正引き表現`([0-9]*)\\s*人中、([0-9]*)\\s*人の方が.*?5つ星のうち\\s*([0-9|\\.]*)`にマッチする。そうでなければ正規表現`5つ星のうち\\s*([0-9|\\.]*)`にマッチする。

以上をJavaのプログラムで表現する。

### 準備

Ubuntu上で開発する。

1. `sudo apt-get install maven`
1. NetBeansのインストール（矢吹の本の2.4.2項を参照）
1. NetBeansでMaven Javaアプリケーションのプロジェクトを作る。プロジェクト名は`amazon`、パッケージは`com.amazon.associates.sample`とする。
1. `pom.xml`をここにあるファイルの通りに修正する。

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

## Product Advertising API （この資料は古い）

APIの最新版：http://aws.amazon.com/archives/Product-Advertising-API

資料：[リクエスト](https://images-na.ssl-images-amazon.com/images/G/09/associates/paapi/dg/index.html?CHAP_MakingRequestsandUnderstandingResponses.html)

### 必要なもの

* awsAccessKeyIdとawsSecretKey
* AssociateTag（矢吹のタグ`inquisitor-22`を使ってもよい）
* 資料：[Javaにおける署名計算のためのサンプルコード](https://images-na.ssl-images-amazon.com/images/G/09/associates/paapi/dg/index.html?AuthJavaSampleSig2.html)（修正が必要）

### 準備

1. パッケージ`com.amazon.associates.sample`の中にクラス`SignedRequestsHelper`を作る。これは上記サンプルコードを少し修正したものである。
1. `awsAccessKeyId`と`awsSecretKey`を設定する。

### サンプル1

特定のアイテムについての情報をASINを指定して取得するためのURLを作る。

1. パッケージ`com.amazon.associates.sample`の中にクラス`Sample1`を作る。
1. AssociateTagを設定する。
1. 調べたい商品のASINを設定する。
1. コード上で右クリック、「ファイルの実行」。
1. 実行結果のURLにブラウザでアクセスして、APIの結果を確認する。

Title要素の中にタイトルが書かれていることを確認する。

コンソールで実行する場合は、

```
cd ~/NetBeansProjects/amazon
mvn compile
mvn exec:java -Dexec.mainClass="com.amazon.associates.sample.Sample1"
```

ASINは実行時にも指定できるようにしてある。

```
mvn exec:java -Dexec.mainClass="com.amazon.associates.sample.Sample1" -Dexec.args="4873115655"
```

### サンプル2

ASINで指定したアイテムの商品名をAPIで取得する。（矢吹の本の5.3.1項を参照）

1. パッケージ`com.amazon.associates.sample`の中にクラス`Sample2`を作る。
1. AssociateTagを設定する。
1. 調べたい商品のASINを設定する。
1. XPathを確認する。
1. 実行結果が正しいことを確認する。

コンソールでASINを実行時に指定する場合は、

```
mvn compile
mvn exec:java -Dexec.mainClass="com.amazon.associates.sample.Sample2" -Dexec.args="4873115655"
```
