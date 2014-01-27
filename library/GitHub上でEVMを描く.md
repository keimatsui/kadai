#GitHub上でEVMを描く

##アイディア

（略）

##実験

Chromeで`Shift + Ctrl + I`としてデベロッパーツールを起動し、そのConsoleタブで以下のコードを実行する。

```
//img要素の生成
var myEvm = document.createElement("img");

//img要素のsrc属性の設定
myEvm.setAttribute("src", "http://chart.apis.google.com/chart?chs=640x240&chd=t:-1,8000,14000,20000,-1|-1,8200,14500,20700,-1|-1,6000,14000,20000,-1&cht=lc&chco=0000ff,ff0000,00ff00&chxt=x,y&chxl=0:|0|20140110|20140111|20140112||1:|0|5000|10000|15000|20000|25000|2:&chg=5000,-1,1,5&chm=d,0000ff,0,-1,10|s,ff0000,1,-1,10|x,00ff00,2,-1,10&chdl=PV|AC|EV&chds=0,25000");

//img要素のstyle属性の設定（必須ではない）
myEvm.setAttribute("style", "border:solid gray 1px; padding:10px;");

//EVMの挿入場所
var myContainer = document.getElementById("js-repo-pjax-container");

//EVMの挿入
myContainer.parentNode.insertBefore(myEvm, myContainer);
```

##ブックマークレット

上のコードをブックマークレットにする。

```
javascript:(function() {
	var myEvm = document.createElement("img");
	myEvm.setAttribute("src", "http://chart.apis.google.com/chart?chs=640x240&chd=t:-1,8000,14000,20000,-1|-1,8200,14500,20700,-1|-1,6000,14000,20000,-1&cht=lc&chco=0000ff,ff0000,00ff00&chxt=x,y&chxl=0:|0|20140110|20140111|20140112||1:|0|5000|10000|15000|20000|25000|2:&chg=5000,-1,1,5&chm=d,0000ff,0,-1,10|s,ff0000,1,-1,10|x,00ff00,2,-1,10&chdl=PV|AC|EV&chds=0,25000");
	myEvm.setAttribute("style", "border:solid gray 1px; padding:10px;");
	var myContainer = document.getElementById("js-repo-pjax-container");
	myContainer.parentNode.insertBefore(myEvm, myContainer);
})();
```

##TODO

上のsrc属性のURLを、issueに書かれた情報から作ること。
