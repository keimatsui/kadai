# GitHub APIで取得したデータの処理方法

「GitHubから複数ページにわたるデータを取得する方法」で取得したデータは、データが1行に1件JSON形式で書かれている。シェル上でJSON形式のデータを扱いたいときは、[jq](http://stedolan.github.io/jq/tutorial/)を使うのが便利。

## 準備

作業ディレクトリに[jqのバイナリ](http://stedolan.github.io/jq/download/linux32/jq)をコピーする。

jqを実行できるようにする。`chmod`については自分で調べること。

```
chmod +x jq
```

## jqの利用

jqの使い方はhttp://stedolan.github.io/jq/tutorial/ やhttp://stedolan.github.io/jq/manual/ で紹介されているが、わかりにくければ日本語の記事を探してみてもよいだろう。

「GitHubから複数ページにわたるデータを取得する方法」の作業でできたファイル、`openissues.txt`と`closedissues.txt`、`commits.txt`、`events.txt`を例に使い方を紹介する。

### データの確認

`less openissues.txt`では結果が見にくい（`less`の使い方は自分で調べる。とりあえず`q`で終了。）

`jq`を使って、`./jq '.' openissues.txt | less`とすると、整形して表示される。

さらに`./jq '.' -C openissues.txt | less -R`とすれば、カラーになる。

### コミット履歴

コミットIDとコミット日時を一覧表示するには`./jq '.commit.committer.date,.sha' commits.txt`。

この結果の偶数行目の時だけ改行するように整形すると、Excelなどで処理しやすくなる。そのためには、`|`（パイプ）で`awk`に渡して、行数を2で割ったあまりがゼロ、つまり偶数行なら改行、奇数行ならカンマを付ければよい。うまくできるようになったら、`> commits.csv`などを付けてファイルに保存する。`awk`については自分で調べること。

```.
/jq '.commit.committer.date,.sha' commits.txt | awk '{ printf($0); if (NR % 2 == 0) printf("\n"); else printf(","); }'
```

### Issues

Issueのタイトルだけを一覧表示するには`./jq '.title' openissues.txt`。結果を別ファイルに保存したければ`>`を使えばよい。

Issueの作成日時だけを一覧表示するには`./jq '.created_at' openissues.txt`。

タイトルは重複する可能性があるから、`./jq '.id,.title,.created_at' openissues.txt`のように、IDをつけておくといい。この結果は、行数を3で割ったあまりが0のときは改行を、それ以外（つまり1か2）のときはカンマを付けて整形すればよい。

```
./jq '.id,.created_at,.title' openissues.txt | awk '{ printf($0); if (NR % 3 == 0) printf("\n"); else printf(","); }'
```

うまくできるようになったら、`> openissues.csv`などを付けてファイルに保存し、ExcelやUbuntuのLibreOffice Calcで読み込む。その際、2列目を「日時」として読み込むようにする。

`closedissues.txt`から、オープン日時とクローズ日時、タイトルの一覧を作るには、次のようにすればよい。うまくできるようになったら、`> closedissues.csv`などを付けてファイルに保存する。

```
./jq '.id,.created_at,.closed_at,.title' closedissues.txt | awk '{printf($0); if (NR % 4 == 0) printf("\n"); else printf(","); }'
```

この結果をCalcで読み込んで、D1に`=(C1-B1)*86400`などと入力してD列全体にコピーすれば、issueがオープンしてからクローズするまでの時間を求められる。

#### ラベルを取得する

ラベルは配列になっているため、取得する時には、`./jq '.id,.created_at,.labels[].name' closedissues.txt`のように、`[]`という記法を用いる。ただ、付いているラベルの数によって形式が変わってしまうため、これでは結果を次の処理に回しにくい。`./jq '.id,.created_at,[.labels[].name]' closedissues.txt`なら形式は揃うが、使いにくいことには変わりがない（`-c`は1行1オブジェクトにするオプション）。

`./jq -c '{id,created_at,label:.labels[].name}' closedissues.txt`とすれば、ラベルの分だけオブジェクトができる（ラベルがないと出てこない）。これにもう一度フィルタをかけてもいいだろう。もっといい方法がありそう。

```
./jq -c '{id,created_at,label:.labels[].name}' closedissues.txt | ./jq '.id,.created_at,.label' | awk '{ printf($0); if (NR % 3 == 0) printf("\n"); else printf(","); }'
```

### 個人の活動

個人の活動の日時とタイプを一覧表示するには`./jq '.created_at,.type' events.txt`。先の例と同様に、結果を整形すると使いやすい。うまくできるようになったら、`> events.csv`などを付けてファイルに保存する。

```
./jq '.created_at,.type' events.txt | awk '{ printf($0); if (NR % 2 == 0) printf("\n"); else printf(","); }'
```

## 自分で調べること

* `chmod`
* `less`
* `awk`：テキスト処理の伝統的なツール。研究室にある雑誌「Software Design」の2013年9月号に「UNIXエンジニアのたしなみ 今からはじめるsed/AWK再入門 ワンライナー vs. スクリプティング」という特集がある。ちなみに、矢吹がはじめて買ったプログラミングの本が、Ahoほか『プログラミング言語AWK』（トッパン, 1989）である。
