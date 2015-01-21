# Streamingデータの処理

（ここでは、フォロー関係の時間変化は考慮していない。）

Streamingデータから、リツイートだけを取り出し、データベースに格納する。リツイートした人、された人のフォロー関係を調べ、データベースに記録する。

## 準備

### Streaming APIの動作確認

以下が動くようにしておく。

1. [Streaming APIで大量のつぶやきをリアルタイムに保存する方法](http://blog.unfindable.net/archives/4257)
1. [Streaming APIで取得したつぶやきの処理方法](http://blog.unfindable.net/archives/4302)

### OAuth

`auth.template.py`を`auth.py`にコピーし、OAuthのための情報を入力しておくこと。

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

これを動かしっぱなしにすればリツイートのデータは集められるが、プロセス自体が落ちた時のために、`retweets.sh`の形にしておくといいだろう。

```bash
sh retweets.sh
```

## リツイートに関する調査

### リツイートした人がフォローしている人の取得（`friends.py`）

指定したIDのユーザがフォローしている人を取得する（最大で5000人、APIの上限）。

#### テスト1

```bash
echo 5739562 | python friends.py
```

#### テスト2

```bash
echo 5739562 | python friends.py | mysql -utest -ppass --force twitter
```

usersに実在するIDを使わないと外部キーエラーになることに注意。

#### テスト3

データベースから、未調査のリツイートを取得する。（誰もフォローしていないユーザで止まらないように、ランダムに取り出している。）

```bash
echo "select retweet from retweets where not exists (select * from friends where friends.user=retweets.retweet) order by rand() limit 1;" | mysql -utest -ppass --skip-column-names twitter
```

#### 本番

以上をつなげて実行する。

```bash
echo "select retweet from retweets where not exists (select * from friends where friends.user=retweets.retweet) order by rand() limit 1;" | mysql -utest -ppass --skip-column-names twitter | python friends.py | mysql -utest -ppass --force twitter
```

あとはこれを1回/1分実行すればよい（APIは15分に15回）。API呼び出し可能回数を確認しながら調べてもよいが、`friends.sh`のように、1回実行したら1分休む程度で十分だろう。

```bash
sh friends.sh
```

### リツイートした人のリストの取得（`retweeters.py`）

指定したIDのツイートをリツイートした人を取得する（最大で100人、APIの上限）。

#### テスト1

```bash
echo 557560378758942720 | python retweeters.py
```

#### テスト2

```bash
echo 557560378758942720 | python retweeters.py | mysql -utest -ppass --force twitter
```

retweetsに実在するIDを使わないと外部キーエラーになることに注意。

#### テスト3

データベースから、未調査のリツイートを取得する。

```bash
echo "select id from retweets where not exists (select * from retweeters where retweeters.tweetId=retweets.id) limit 1;" | mysql -utest -ppass --skip-column-names twitter
```

#### 本番

以上をつなげて実行する。

```bash
echo "select id from retweets where not exists (select * from retweeters where retweeters.tweetId=retweets.id) limit 1;" | mysql -utest -ppass --skip-column-names twitter | python retweeters.py | mysql -utest -ppass --force twitter
```

あとはこれを1回/1分実行すればよい（APIは15分に15回。app authなら60回になるようだが、ここでは試さない）。`checkretweeters.py`でAPIを確認しながら調べてもよいが、`retweeters.sh`のように、1回実行したら1分休む程度で十分だろう。

```bash
sh retweeters.sh
```

## 運用

`retweets.sh`と`friends.sh`、`retweeters.sh`を実行する。

## 解析

### フォローしていない人のつぶやきをリツイートしている事例

リツイートする人・される人のペアは1回しか記録しないから、問い合わせ方法は以下の通りでよい。
誰もフォローしていないユーザのことは考慮していない（TODO:改善）。

分母（リツイートした人で、フォローしている人は調査済みのもの）

```sql
select count(*) from retweets
where
  exists (select * from friends where friends.user=retweets.retweet)-- フォローしている人は調査済み
;
```

分子（リツイートした人で、フォローしている人は調査済み、かつ、リツイートされた人をフォローしていない）

```sql
select count(*) from retweets
where
  exists (select * from friends where friends.user=retweets.retweet) and-- フォローしている人は調査済み
  not exists (select * from friends where friends.user=retweets.retweet and friends.friend=retweets.retweeted)-- フォローしていない
;
```

### フォローしていない人のつぶやき（リツイートも含む）をリツイートしている事例

分母（リツイートした人がフォローしている人は調査済み、かつ、誰にリツイートされたかも調査済みのリツイート）

```sql
select count(*) from retweets
where
  exists (select * from friends where friends.user=retweets.retweet) and-- フォローしている人は調査済み
  exists (select * from retweeters where retweeters.tweetId=retweets.id)-- リツイートした人は調査済み
;
```

分子（リツイートした人がフォローしている人は調査済み、かつ、誰にリツイートされたかも調査済みのリツイートで、リツイートした人はリツイートされた人とリツイートした人をフォローしていない）

```sql
select count(*) from retweets
where
  exists (select * from friends where friends.user=retweets.retweet) and-- フォローしている人は調査済み
  exists (select * from retweeters where retweeters.tweetId=retweets.id) and-- リツイートした人は調査済み
  not exists (select * from friends where friends.user=retweets.retweet and friends.friend=retweets.retweeted) and-- フォローしていない
  not exists (
    select * from friends,retweeters where
      retweeters.tweetId=retweets.id and-- 該当するつぶやきを
      friends.user=retweets.retweet and-- リツイートした人が
      friends.friend=retweeters.retweet)-- フォローしている人がリツイートした例は存在しない
;
```





# 以下は古い実装（そのままでは動かない）

## フォロー関係の調査

APIは15分に180回しか呼び出せない。複数のアカウントを使えば速くなるのだが、以下の方法はそのような並列処理には対応していない。

手順

1. API呼び出しの可能回数を調べる(`checkfriendships.py`)
1. テーブル`retweets`で、フォロー関係を調べたかどうかの情報を更新する
1. テーブル`retweets`から、まだフォロー関係を調べていないユーザを、可能回数分だけ取得する
1. フォロー関係を調べ、データベースに記録するためのSQL文を生成する(`friendship.py`)
1. データベースに記録する
1. API呼び出し回数がリセットされるまで待つ

以上の手順は、1つのPythonプログラムで実行することもできる。しかしここでは、1つの仕事しかしないツールを複数作り、それらを組み合わせて実現する。

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

重複時にエラーで止まらないように、`--force`を付けておく。

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

### テーブル`retweets`の更新

followsに関係が記録されているなら、retweetsのcheckedはTRUEということにする。この作業が意味を持つのは、retweetsに既存のペアとは逆のペアが登録されたときである。そのペアのフォロー関係は、調べる必要はない（フォロー関係が変わらないような短期の調査を仮定している）。

```sql
update retweets set checked=TRUE where checked=FALSE and exists (select * from follows where ((follow=retweet) and (followed=retweeted)) or ((follow=retweeted) and (followed=retweet)));
```

### `checkfriendships.py`

フォロー関係を調べていないユーザのペアを10件取得するなら、

```sql
select retweet,retweeted from retweets where checked=FALSE limit 10;
```

シェルから実行するなら（カラム名は不要だから、オプション`--skip-column-names`をつけておく）、

```bash
echo "select retweet,retweeted from retweets where checked=FALSE limit 10;" | mysql -utest -ppass --skip-column-names twitter 
```

実際の数は、Twitterに問い合わせて確認する。問い合わせると、回数がリセットされる時刻も教えてくれるから、そこから現在時刻を引き、結果の分だけ待機する。

```bash
python checkfriendships.py
```

この結果を実行すれば、APIの限界（若干の余裕あり）まで調べられる。

### まとめ

以上を繰り返すのが`friendship.sh`である。

```bash
sh friendship.sh
```
