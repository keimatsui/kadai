#GitHub上でEVMを描く

##アイディア

（略）

##EVMを描くページ

https://github.com/taroyabuki/yabukilab/blob/master/library/evm/evm.html

このファイルは`?user=kudo9160&repo=normal`のようなパラメータを付けると、対応するGitHubリポジトリのEVMを描く。

##ウェブサーバでの配信

https://localhost/evm.html で開けるようにする。SSLのエラーが出るが、ここでは無視する。

動作確認：https://localhost/evm.html?user=kudo9160&repo=normal

##GitHubのページに埋め込む

GitHubのページ（例：https://github.com/kudo9160/normal ）で、Chromeで`Ctrl+Shift+I`として、以下のスクリプトを実行する。

```
(function() {
    var user = location.href.replace(/.*github\.com\/(.*)\/.*/, '$1');
    var repo = location.href.replace(/.*github\.com\/.*\/(.*)/, '$1');
    var myEvm = document.createElement("iframe");
    var url = 'https://localhost/evm.html?user=' + user + '&repo=' + repo;
    myEvm.setAttribute('src', url);
    myEvm.setAttribute('style', 'width:700px; height:500px; border-style:none;');
    var myContainer = document.getElementById("js-repo-pjax-container");
    myContainer.parentNode.insertBefore(myEvm, myContainer);
})();
```

##ブックマークレット

以下のブックマークレットを登録する。

#動作確認

* https://github.com/kudo9160/normal
* https://github.com/kudo9160/excess

##TODO

* まだ終わっていない例が用意して、open issuesも取得するようにする。
* 最終日を求める（最終日は、オープンなものがあるなら今日。無いなら、endの最大値。）
* 順番をちゃんと調べる（ここでは単純に、逆順で処理している）
* タイムゾーンをブラウザのロケールに合わせる
* 開始日を登録するようにする（ここでは、normalとexcessだけ、開始日を設定している）
