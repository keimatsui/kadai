# ニコニコ動画の情報をAPIで取得する

参考：[ニコニコ動画 『スナップショット検索API』 ガイド](http://search.nicovideo.jp/docs/api/snapshot.html)

上の資料を参考にすると、「minecraft」に関連する動画の情報は、次のように取得できる。

```bash
curl -v "Accept: application/json" -H "Content-type: application/json" -X POST -d '{"query":"minecraft","service":["video"],"search":["title","description","tags"],"join":["cmsid","title","start_time","view_counter"],"from":0,"size":3,"sort_by":"start_time","issuer":"yourservice/applicationname"}' http://api.search.nicovideo.jp/api/snapshot/
```

結果の「total」のところを見ると、動画の数がわかる。

これを書いた時点では89732件だったため、次のようなスクリプトですべて取得する（取得中には増えないと仮定している）。

```bash
rm result.dat

for (( start=0; start<89800; start+=100 ))
do
  curl -v "Accept: application/json" -H "Content-type: application/json" -X POST -d "{\"query\":\"minecraft\",\"service\":[\"video\"],\"search\":[\"title\",\"description\",\"tags\"],\"join\":[\"cmsid\",\"title\",\"start_time\",\"view_counter\"],\"from\":${start},\"size\":100,\"sort_by\":\"start_time\",\"issuer\":\"yourservice/applicationname\"}" http://api.search.nicovideo.jp/api/snapshot/ >> result.dat
done
```

2015年1月23日取得したデータが`20150123_result.dat`である。

こうしてできる`result.dat`はJSONのかたまり。ここから必要な情報を抽出する。

```bash
cat result.dat | python3 videos.py > minecraft.csv
```

2015年1月23日取得したデータが`20150123_minecraft.csv`である。
