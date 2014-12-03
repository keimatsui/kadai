#Streaming APIを使う方法

1. [Streaming APIで大量のつぶやきをリアルタイムに保存する方法](http://blog.unfindable.net/archives/4257)
1. [Streaming APIで取得したつぶやきの処理方法](http://blog.unfindable.net/archives/4302)

上記サイトではリツィートを除外する例を紹介しているが、リツィートされたものだけを処理するなら、`parse.py`は次のようになる。

```python
#!/usr/bin/env python
import sys, json
 
for line in sys.stdin:
    try:
        tweet = json.loads(line)
        #pprint(tweet)
        if 'retweeted_status' in tweet:
          retweetUser = tweet['user']['screen_name']
          retweetedStatus = tweet['retweeted_status']
          retweetedUser = retweetedStatus['user']['screen_name']
          tweetId = retweetedStatus['id_str']
          print retweetUser+','+retweetedUser+','+'https://twitter.com/'+retweetedUser+'/status/'+tweetId
    except ValueError:
        pass
```

このようなコードを書くためには、結果のJSONに何が書かれているかを確認しなければならない。その際には、[GitHub APIで取得したデータの処理方法](https://github.com/taroyabuki/yabukilab/blob/master/library/github/GitHub%20API%E3%81%A7%E5%8F%96%E5%BE%97%E3%81%97%E3%81%9F%E3%83%87%E3%83%BC%E3%82%BF%E3%81%AE%E5%87%A6%E7%90%86%E6%96%B9%E6%B3%95.md)で紹介している`jq`が役立つだろう。

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
