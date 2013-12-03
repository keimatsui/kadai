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

http://localhost/twitter/mysearch.html にアクセスする。
