# Streamingデータの処理

（ここでは、フォロー関係の時間変化は考慮していない。）

Streamingデータから、リツイートだけを取り出し、データベースに格納する。リツイートした人、された人のフォロー関係を調べ、データベースに記録する。

## 準備

### Streaming APIの動作確認

以下が動くようにしておく。

1. [Streaming APIで大量のつぶやきをリアルタイムに保存する方法](http://blog.unfindable.net/archives/4257)
1. [Streaming APIで取得したつぶやきの処理方法](http://blog.unfindable.net/archives/4302)

### OAuth

`auth.template.py`を`autu.py`にコピーし、OAuthのための情報を入力しておくこと。

### データベース

`twitter.sql`のとおりに準備する。（プログラムの中でも、データベースと同様の名前を使うから、テーブル定義を見ておくように。）

## リツイートの記録

手順

1. Streamingデータを標準出力に書き出す(`stream.py`)
1. 標準出力に書き出されたstreamingデータから、リツイートを抜き出し、データベースに記録するためのSQL文を生成する（`retweets.py`）
1. データベースに記録する

### `stream.py`

試してみる。

```bash
python stream.py
```

止めるときは`Ctrl-C`（以下同様）

このようなコードを書くためには、結果のJSONに何が書かれているかを確認しなければならない。その際には、[GitHub APIで取得したデータの処理方法](https://github.com/taroyabuki/yabukilab/blob/master/library/github/GitHub%20API%E3%81%A7%E5%8F%96%E5%BE%97%E3%81%97%E3%81%9F%E3%83%87%E3%83%BC%E3%82%BF%E3%81%AE%E5%87%A6%E7%90%86%E6%96%B9%E6%B3%95.md)で紹介している`jq`が役立つだろう。

### `retweets.py`

試してみる。

```bash
python stream.py | python retweets.py
```

### データベースに記録する

本番。重複によるエラーが出ても止まらないように、オプション`--force`をつけておく。

```bash
python stream.py | python retweets.py | mysql -utest -ppass --force twitter
```

これを動かしっぱなしにすればリツイートのデータは集められるが、プロセス自体が落ちた時のために、`retweets.sh`の形にしておくといいだろう（もっとちゃんとしたければ、プロセスを監視して・・・）

```bash
sh retweets.sh
```

## フォロー関係の調査

手順

1. API呼び出しの可能回数を調べる(`remainingapi.py`)
1. テーブル`retweets`から、まだフォロー関係を調べていないユーザを、可能回数分だけ取得する
1. フォロー関係を調べ、データベースに記録するためのSQL文を生成する(`friendship.py`)
1. データベースに記録する
1. API呼び出し回数がリセットされるまでの時間を調べる(`timetoreset.py`)
1. API呼び出し回数がリセットされるまで待つ

### `remainingapi.py`

試してみる。

```bash
python remainingapi.py
```

### フォロー関係を調べていないユーザ

10件なら以下の通り（カラム名は不要だから、オプション`--skip-column-names`をつけておく）

```bash
echo "select retweet,retweeted from retweets where checked=FALSE limit 10;" | mysql -utest -ppass --skip-column-names twitter 
```

APIで試せる回数分だけなら、

```bash
echo "select retweet,retweeted from retweets where checked=FALSE limit `python remainingapi.py`;" | mysql -utest -ppass --skip-column-names twitter 
```

### `friendship.py`

試してみる。

```bash
echo "yabuki y5w11" | python friendship.py
```

### データベースに記録する

試してみる。

```sql
insert into retweets (retweet,retweeted) values ('yabuki','y5w11'),('yabuki','Nintendo');
```

```bash
echo "yabuki y5w11" | python friendship.py | mysql -utest -ppass --force twitter
echo "yabuki y5w11" | python friendship.py | mysql -utest -ppass --force twitter
echo "yabuki Nintendo" | python friendship.py | mysql -utest -ppass --force twitter
```

```sql
select * from retweets where retweet='yabuki';
select * from follows where follow='yabuki' or followed='yabuki';
delete from follows where follow='yabuki' or followed='yabuki';
delete from retweets where retweet='yabuki';
```

### `timetoreset.py`

試してみる。

```bash
python timetoreset.py
```

この時間だけ待機させるには、次のようにすればよい。

```bash
s=`python timetoreset.py`
sleep $s
```

### まとめ

以上を`friendship.sh`にまとめ、実行する。

```bash
sh friendship.sh
```