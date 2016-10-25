#PukiWikiからのデータ取得

例題：http://wiki.skyrim.z49.org/

##準備

プロキシサーバを用意すること。（https://github.com/yabukilab/main/blob/master/library/crawler/%E3%83%97%E3%83%AD%E3%82%AD%E3%82%B7.md を参照）

##作業

プロキシ設定

```
export http_proxy=localhost:5432
```

作業フォルダ

```
mkdir /vagrant/pukiwiki
cd /vagrant/pukiwiki
```

ページ一覧

```
curl http://wiki.skyrim.z49.org/?cmd=list | gunzip | nkf -w > pages.html

less pages.html
```

lessは「q」で終了。

切り出し

```
grep '<li>' pages.html | less
```

?から"までを抜き出す。（sedと正規表現と後方参照を勉強すること。）

```
grep '<li>' pages.html | sed 's/.*?\(.*\)".*/\1/g' > pages.dat

less pages.dat
```

pages.datのすべてのページを処理するのだが、練習のため、MOD%2F%A4%BD%A4%CE%C2%BE だけを処理しよう。

履歴のページのURL

```
page=MOD%2F%A4%BD%A4%CE%C2%BE
url="http://wiki.skyrim.z49.org/?plugin=editlog&mode=search_page&target=all&pname=$page"
file="$page.html"
echo $url
```

```
curl $url | gunzip | nkf -w > $file
less $file
```

h3要素とli要素を取り出す（AWKについて勉強しながらeditlog.awkを読むこと。）

```
gawk -f editlog.awk $file >> log.dat
less log.dat
```

あとはこれをpages.datのすべての行についてループさせればよいのだが、相手に負荷がかかるため、「sleep 30s」などを入れておくとよいだろう。700ページだから、1分2件でも数時間で終わる。

ヒント：

```
for i in `cat pages.dat`;do
  echo $i
  sleep 1s
done
```
