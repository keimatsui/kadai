# Product Advertising API

## Javaの場合

とりあえず、`Sample.java`を理解する。

1. NetBeansでMaven Javaアプリケーションのプロジェクトを作る。
1. pom.xmlを修正する。
1. パッケージ`com.amazon.associates.sample`を作る。
1. クラス`SignedRequestsHelper`を作る。これは https://images-na.ssl-images-amazon.com/images/G/09/associates/paapi/dg/index.html?AuthJavaSampleSig2.html を少し修正したものである。
1. クラス`Amazon`を作る。
1. クラス`Sample`を作り、`awsAccessKeyId`と`awsSecretKey`、`AssociateTag`をセットする。AssociateTagを持っていないなら`inquisitor-22`でもよい。
1. `Sample`を実行する。