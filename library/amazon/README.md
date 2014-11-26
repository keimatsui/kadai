# Product Advertising API

APIの最新版：http://aws.amazon.com/archives/Product-Advertising-API

資料：[リクエスト](https://images-na.ssl-images-amazon.com/images/G/09/associates/paapi/dg/index.html?CHAP_MakingRequestsandUnderstandingResponses.html)

## 必要なもの

* awsAccessKeyIdとawsSecretKey
* AssociateTag（矢吹のタグ`inquisitor-22`を使ってもよい）

## Javaの場合

資料：[Javaにおける署名計算のためのサンプルコード](https://images-na.ssl-images-amazon.com/images/G/09/associates/paapi/dg/index.html?AuthJavaSampleSig2.html)（修正が必要）

### 準備

Ubuntu上で開発する。

1. NetBeansのインストール（矢吹の本の2.4.2項を参照）
1. NetBeansでMaven Javaアプリケーションのプロジェクトを作る。プロジェクト名は`amazon`、パッケージは`com.amazon.associates.sample`とする。
1. `pom.xml`を修正する。
1. パッケージ`com.amazon.associates.sample`の中にクラス`SignedRequestsHelper`を作る。これは上記サンプルコードを少し修正したものである。
1. `awsAccessKeyId`と`awsSecretKey`を設定する。

### サンプル1

特定のアイテムについての情報をASINを指定して取得するためのURLを作る。

1. パッケージ`com.amazon.associates.sample`の中にクラス`Sample1`を作る。
1. AssociateTagを設定する。
1. 調べたい商品のASINを設定する。
1. 実行結果のURLにブラウザでアクセスして、APIの結果を確認する。

Title要素の中にタイトルが書かれていることを確認する。

### サンプル2

ASINで指定したアイテムの商品名をAPIで取得する。（矢吹の本の5.3.1項を参照）

1. パッケージ`com.amazon.associates.sample`の中にクラス`Sample2`を作る。
1. AssociateTagを設定する。
1. 調べたい商品のASINを設定する。
1. XPathを確認する。
1. 実行結果が正しいことを確認する。

# レビュー

APIで取得できるのはレビューのページのURLであり、レビュー自体はAPIでは取得できない。

レビューのURLは、APIで取得してもいいが、少し試してみれば、http://www.amazon.co.jp/product-reviews/400339481X/ のようなURLを作ればよいことがわかる。そこで、HTTPクライアントを書いてレビューを取得することにする。

## Java

### サンプル3

ASINで指定したアイテムのレビューを取得する。（矢吹の本の5.2.2項を参照）

1. パッケージ`com.amazon.associates.sample`の中にクラス`Sample3`を作り、実行する。

### サンプル4

ASINで指定したアイテムのレビューを取得・記憶し、重み付き評価値を求める。（1ページ限定）

1. パッケージ`com.amazon.associates.sample`の中にクラス`Review`を作る。
1. パッケージ`com.amazon.associates.sample`の中にクラス`Sample4`を作り、実行する。

### サンプル5

ASINで指定したアイテムのレビューを取得・記憶し、重み付き評価値を求める。（複数ページ対応）

1. パッケージ`com.amazon.associates.sample`の中にクラス`Sample5`を作り、実行する。

503エラーがあると再接続するが、本当に問題がある場合に止まらなくなるため、おかしいときは手動で止めること。


