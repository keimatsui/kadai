# Product Advertising API （この資料は古い）

APIの最新版：http://aws.amazon.com/archives/Product-Advertising-API

資料：[リクエスト](https://images-na.ssl-images-amazon.com/images/G/09/associates/paapi/dg/index.html?CHAP_MakingRequestsandUnderstandingResponses.html)

## 必要なもの

* awsAccessKeyIdとawsSecretKey
* AssociateTag（矢吹のタグ`inquisitor-22`を使ってもよい）

## Javaの場合

資料：[Javaにおける署名計算のためのサンプルコード](https://images-na.ssl-images-amazon.com/images/G/09/associates/paapi/dg/index.html?AuthJavaSampleSig2.html)（修正が必要）

### 準備

Ubuntu上で開発する。

1. `sudo apt-get install maven`
1. NetBeansのインストール（矢吹の本の2.4.2項を参照）
1. NetBeansでMaven Javaアプリケーションのプロジェクトを作る。プロジェクト名は`amazon`、パッケージは`com.amazon.associates.sample`とする。
1. `pom.xml`をここにあるファイルの通りに修正する。
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
