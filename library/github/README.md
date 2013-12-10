# GitHub APIの試し方

## 準備

Ubuntuを使う。（CentOSでも同じようなことができるはずだが、ここでの説明はUbuntuを想定している。）

`Ctrl + Alt + T`で端末を起動し、以下の文書を順番に読んでいってほしい。

1. [GitHub APIを使う練習](https://github.com/taroyabuki/yabukilab/blob/master/library/github/GitHub%20API%E3%82%92%E4%BD%BF%E3%81%86%E7%B7%B4%E7%BF%92.md)
1. [結果が大量にある場合の処理の原理](https://github.com/taroyabuki/yabukilab/blob/master/library/github/%E7%B5%90%E6%9E%9C%E3%81%8C%E5%A4%A7%E9%87%8F%E3%81%AB%E3%81%82%E3%82%8B%E5%A0%B4%E5%90%88%E3%81%AE%E5%87%A6%E7%90%86%E3%81%AE%E5%8E%9F%E7%90%86.md)（参考）
1. [GitHubから複数ページにわたるデータを取得する方法](https://github.com/taroyabuki/yabukilab/blob/master/library/github/GitHub%E3%81%8B%E3%82%89%E8%A4%87%E6%95%B0%E3%83%9A%E3%83%BC%E3%82%B8%E3%81%AB%E3%82%8F%E3%81%9F%E3%82%8B%E3%83%87%E3%83%BC%E3%82%BF%E3%82%92%E5%8F%96%E5%BE%97%E3%81%99%E3%82%8B%E6%96%B9%E6%B3%95.md)
1. [GitHub APIで取得したデータの処理方法](https://github.com/taroyabuki/yabukilab/blob/master/library/github/GitHub%20API%E3%81%A7%E5%8F%96%E5%BE%97%E3%81%97%E3%81%9F%E3%83%87%E3%83%BC%E3%82%BF%E3%81%AE%E5%87%A6%E7%90%86%E6%96%B9%E6%B3%95.md)
1. （アドバンスト）[コミットごとに全体の行数とtestの行数を数える方法](https://github.com/taroyabuki/yabukilab/blob/master/library/github/%E3%82%B3%E3%83%9F%E3%83%83%E3%83%88%E3%81%94%E3%81%A8%E3%81%AB%E5%85%A8%E4%BD%93%E3%81%AE%E8%A1%8C%E6%95%B0%E3%81%A8test%E3%81%AE%E8%A1%8C%E6%95%B0%E3%82%92%E6%95%B0%E3%81%88%E3%82%8B%E6%96%B9%E6%B3%95.md)

Linuxに慣れていない人は、無料の教科書[LPIC『Linux標準教科書』](http://www.lpi.or.jp/linuxtext/text.shtml)を手元に置いておくとよい。