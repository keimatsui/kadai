# Internet Archiveのキャプチャ

Seleniumを使ってInternet Archiveをキャプチャする。Windows上で試す。

## 準備

1. Anacondaをインストールする。
1. Firefoxをインストールする。
1. [Selenium Driver](http://selenium-python.readthedocs.io/installation.html)によると，Firefox用の Driverはhttps://github.com/mozilla/geckodriver/releases にある。geckodriver-v0.16.1-win64.zip をダウンロード・展開して，geckodriver.exeを作業ディレクトリに置く。作業ディレクトリでなく，PATHが通っているところに置いてもよい。
1. コマンドプロンプトで`pip install selenium`

## 基本的な考え方

2013年1月のhttp://www.sony.co.jp/ をキャプチャするという例で説明する。

[Internet ArchiveのAPI](https://archive.org/help/wayback_api.php)を使って，最も近い時点のアーカイブのURLを取得する。この場合は，http://archive.org/wayback/available?url=http://www.sony.co.jp/&timestamp=20130101 である。とりあえず，APIを叩いて結果のJSONを整形表示するには`curl "http://archive.org/wayback/available?url=http://www.sony.co.jp/&timestamp=20130101" | python -m json.tool`

```
{
    "archived_snapshots": {
        "closest": {
            "available": true,
            "url": "http://web.archive.org/web/20121227012631/http://www.sony.co.jp/",
            "timestamp": "20121227012631",
            "status": "200"
        }
    }
}
```

`"available": true`だから，アーカイブされている。そのURLもわかった。URLの27文字目から32文字目が，正確な年月である。これをラベルとする。

あとは，Seleniumでページをキャプチャし，ラベルを付けたファイル名（あるいはフォルダ）に画像を保存すればよい。

## 実装

getarchive.pyは，指定したサイトの指定した年・月のようすをキャプチャし，ディレクトリ`img`に保存するスクリプトである。

### 準備

1. 自分が取得したい範囲の年と月をgetarchive.pyで設定する。
1. ラベルごとにフォルダを分けたい場合は，コメントを参考に修正すること。

### 使い方（一つのサイト）

```
echo http://www.sony.co.jp/ | python getarchive.py
```

### 使い方（複数のサイト）

サイトをwebsites.txtに書いておけばよい。

```
python getarchive.py < websites.txt
```
