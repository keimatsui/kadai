#ブラウザ上のJavaScriptでTwitter APIを利用する方法

ここではhttps://oauth.io/ を利用する。

このディレクトリにあるサンプルは、矢吹がhttps://oauth.io/ で作成したアプリを使うようになっている。とりあえずはこのままで動くが、自分のアプリが必要なときは、まずTwitterのアプリを作り、そのConsumer KeyとConsumer Secretをhttps://oauth.io/ に登録すること。

##準備

Windowsの場合はXAMPPをインストールし、`C:/xampp/htdocs/twitter`にファイルをコピーする。

Ubuntuの場合はApache2をインストールし、`/var/www/twitter`にファイルをコピーする。

いずれの場合も`twitter`の部分は変えてもよい。

##OAuthの確認

http://localhost/twitter/myoauth.html にアクセスする（ブラウザでポップアップは許可しておく）。

##検索APIの確認

https://dev.twitter.com/docs/api/1.1/get/search/tweets に掲載されているAPIを利用する（卒論で利用する場合、このページに書いてあることを、卒論本文に書いておくといいだろう）。mysearch.htmlでは何を検索しているかわかる程度の説明があるとよい。

http://localhost/twitter/mysearch.html にアクセスする。
