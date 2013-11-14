# GitHub APIで取得したデータの処理方法

「GitHubから複数ページにわたるデータを取得する方法」で取得したデータは、データが1行に1件JSON形式で書かれている。シェル上でJSON形式のデータを扱いたいときは、[jq](http://stedolan.github.io/jq/tutorial/)を使うのが便利。

## 準備

作業ディレクトリに[jqのバイナリ](http://stedolan.github.io/jq/download/linux32/jq)をコピーする。

jqを実行できるようにする。`chmod`については自分で調べること。

```
chmod +x jq
```

## jqの利用

jqの使い方はhttp://stedolan.github.io/jq/tutorial/ で紹介されているが、わかりにくければ日本語の記事を探してみてもよいだろう。

「GitHubから複数ページにわたるデータを取得する方法」の作業でできたファイル、`openissues.txt`と`closedissues.txt`、`commits.txt`、`events.txt`を例に使い方を紹介する。

### データの確認

`less openissues.txt`では結果が見にくい（`less`の使い方は自分で調べる。とりあえず`q`で終了。）

`jq`を使って、`./jq '.' openissues.txt | less`とすると、整形して表示される。

さらに`./jq '.' -C openissues.txt | less -R`とすれば、カラーになる。

### Issues

Issueのタイトルだけを一覧表示するには`./jq '.title' openissues.txt`。結果を別ファイルに保存したければ`>`を使えばよい。

Issueの作成日時だけを一覧表示するには`./jq '.created_at' openissues.txt`。これもファイルに保存し、Excel上で上述のタイトル一覧と合わせてもよいが、`./jq '.created_at,.title' openissues.txt`の結果の偶数行目の時だけ改行するようにするのが簡単（行数を2で割ったあまりがゼロ、つまり偶数行なら改行、奇数行ならカンマを付ける）。`|`（パイプ）で`awk`に渡して処理する。`awk`については自分で調べること。うまくできるようになったら、`> openissues.csv`などを付けてファイルに保存する。

```
./jq '.created_at,.title' openissues.txt | awk '{ printf($0); if (NR % 2 == 0) printf("\n"); else printf(","); }'
```

のように結果をawkに渡して処理すれば、ExcelやUbuntuのLibreOffice Calcで読めるCSV形式になる（読み込むときに、1列目を日時にする）。

`closedissues.txt`から、オープン日時とクローズ日時、タイトルの一覧を作るには、次のようにすればよい。うまくできるようになったら、`> closedissues.csv`などを付けてファイルに保存する。

```
./jq '.created_at,.closed_at,.title' closedissues.txt | awk '{printf($0); if (NR % 3 == 0) printf("\n"); else printf(","); }'
```

この結果をCalcで読み込んで、C1に`=(B1-A1)*86400`などと入力してC列全体にコピーすれば、issueがオープンしてからクローズするまでの時間を求められる。

### コミット履歴

コミットIDとコミット日時を一覧表示するには`./jq '.commit.committer.date,.sha' commits.txt`。先の例と同様に、結果を整形すると使いやすい。うまくできるようになったら、`> commits.csv`などを付けてファイルに保存する。

```.
/jq '.commit.committer.date,.sha' commits.txt | awk '{ printf($0); if (NR % 2 == 0) printf("\n"); else printf(","); }'
```

### 個人の活動

個人の活動の日時とタイプを一覧表示するには`./jq '.created_at,.type' events.txt`。先の例と同様に、結果を整形すると使いやすい。うまくできるようになったら、`> events.csv`などを付けてファイルに保存する。

./jq '.created_at,.type' events.txt | awk '{ printf($0); if (NR % 2 == 0) printf("\n"); else printf(","); }'


## 自分で調べること

* `chmod`
* `less`
* `awk`：テキスト処理の伝統的なツール。研究室にある雑誌「Software Design」の2013年9月号に「UNIXエンジニアのたしなみ 今からはじめるsed/AWK再入門 ワンライナー vs. スクリプティング」という特集がある。ちなみに、矢吹がはじめて買ったプログラミングの本が、Ahoほか『プログラミング言語AWK』（トッパン, 1989）である。
