#GitHub上でEVMを描く

##アイディア

（略）

##EVMを描くページ（動作確認用）

https://github.com/taroyabuki/yabukilab/blob/master/library/evm/evm.html

このファイルは`?user=kudo9160&repo=normal`のようなパラメータを付けると、対応するGitHubリポジトリのEVMを描く。

##ブックマークレット

これ>>>[EVM](javascript:(function(){var k=[];var m=[];var i=[];var h=function(o){var n=15<=o.substring(11,13)*1?1:0;return o.substring(0,10).replace(/-/g,"")*1+n};var c=function(n){var o=new XMLHttpRequest();o.open("GET",n);o.onreadystatechange=function(){if(o.readyState===4&&o.status===200){var s=JSON.parse(o.responseText);console.log(s);for(var r=s.length-1;0<=r;--r){var x=s[r];var t=x.body;t=JSON.parse(t);console.log(t);if(x.comments!==0){var w=t.time*t["rates per hour"]+t["material costs"];a(x.comments_url,t["rates per hour"],w)}var p;if(f==="normal"){if(r===0){p=20131228}else{if(r===1){p=20131227}else{p=20131226}}}else{if(f==="excess"){p=20140110}}var q=h(t.end);var y=q-p+1;var u=1*t["rates per hour"]*t.time/y;k[p]=k[p]?k[p]+t["material costs"]:t["material costs"];k[p]+=u;for(var v=p+1;v<=q;++v){k[v]=k[v]?k[v]+u:u}}j()}};o.send(null)};var a=function(n,o,q){var p=new XMLHttpRequest();p.open("GET",n);p.onreadystatechange=function(){if(p.readyState===4&&p.status===200){var w=JSON.parse(p.responseText);console.log(w);var v=0;for(var u=0;u<w.length;++u){var y=w[u];var r=y.body;r=JSON.parse(r);console.log(r);var s=h(y.created_at);if(!k[s]){k[s]=0}var x=r["material costs"]+o*r["direct hours"];m[s]=m[s]?m[s]+x:x;var t=(r["measure work in progress"]-v)*q/100;i[s]=i[s]?i[s]+t:t;v=r["measure work in progress"]}j()}};p.send(null)};var j=function(){console.log(i);var q=[];var s=[];var o=[];var n=true;for(var r in k){if(n){q[r]=k[r];s[r]=m[r]?m[r]:0;o[r]=i[r]?i[r]:0;n=false}else{q[r]=q[r-1]+k[r];s[r]=s[r-1]+(m[r]?m[r]:0);o[r]=o[r-1]+(i[r]?i[r]:0)}}var t="";var u="";var p="";var v="";var w="<table><tr><th></th><th>PV</th><th>AC</th><th>EV</th></tr>";for(var r in k){w+="<tr><td>"+r+"</td><td>"+q[r]+"</td><td>"+s[r]+"</td><td>"+o[r]+"</td></tr>";v+="|"+r,t+=q[r]+",";u+=s[r]+",";p+=o[r]+",";b="https://chart.googleapis.com/chart?chs=640x300&cht=lc&chds=a&chxt=x,y&chco=0000ff,ff0000,00ff00&chdl=PV|AC|EV&chd=t:"+t+"|"+u+"|"+p+"&chxl=0:"+v+"&chm=d,0000ff,0,-1,15|d,ff0000,1,-1,15|d,00ff00,2,-1,15";b=b.replace(/,\|/g,"|");b=b.replace(/,&/g,"&")}w+="</table>";document.getElementById("table").innerHTML=w;document.getElementById("evm").setAttribute("src",b)};var e=location.href.replace(/.*github\.com\/(.*)\/.*/,"$1");var f=location.href.replace(/.*github\.com\/.*\/(.*)/,"$1");var d=document.createElement("img");d.setAttribute("id","evm");var l=document.createElement("div");l.setAttribute("id","table");var g=document.getElementById("js-repo-pjax-container");g.parentNode.insertBefore(l,g);l.parentNode.insertBefore(d,l);var b="https://localhost/evm.html?user="+e+"&repo="+f;c("https://api.github.com/repos/"+e+"/"+f+"/issues?per_page=100&state=closed")})();)を登録する。

このブックマークレットの中身は、https://github.com/taroyabuki/yabukilab/blob/master/library/evm/evm.js であるが、http://refresh-sf.com/yui/ で圧縮してからでないと使えないことに注意。

#動作確認

* https://github.com/kudo9160/normal
* https://github.com/kudo9160/excess

##TODO

* まだ終わっていない例が用意して、open issuesも取得するようにする。
* 最終日を求める（最終日は、オープンなものがあるなら今日。無いなら、endの最大値。）
* 順番をちゃんと調べる（ここでは単純に、逆順で処理している）
* タイムゾーンをブラウザのロケールに合わせる
* 開始日を登録するようにする（ここでは、normalとexcessだけ、開始日を設定している）
