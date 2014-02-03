javascript:(function() {
    var pv = [];//累積ではない
    var ac = [];//累積ではない
    var ev = [];//累積ではない

    var toDate = function(str) {
        var x = 15 <= str.substring(11, 13) * 1 ? 1 : 0;//日本時間に変換
        //TODO:ちゃんとブラウザのロケールに合わせる
        return str.substring(0, 10).replace(/-/g, '') * 1 + x;
    };

    var getIssues = function(url) {
        var xhr = new XMLHttpRequest();
        xhr.open('GET', url);
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                var issues = JSON.parse(xhr.responseText);
                console.log(issues);
                for (var i = issues.length - 1; 0 <= i; --i) {
                    var issue = issues[i];
                    var body = issue.body;
                    body = JSON.parse(body);
                    console.log(body);
                    if (issue.comments !== 0) {
                        var totalcost = body.time * body['rates per hour'] + body['material costs'];
                        getComments(issue.comments_url, body['rates per hour'], totalcost);
                    }
                    //TODO:開始日が登録されていない
                    var start;
                    if (repo === 'normal') {
                        if (i === 0)
                            start = 20131228;
                        else if (i === 1)
                            start = 20131227;
                        else
                            start = 20131226;
                    } else if (repo === 'excess') {
                        start = 20140110;
                    }
                    var end = toDate(body.end);
                    var days = end - start + 1;
                    var costperday = 1.0 * body['rates per hour'] * body.time / days;
                    pv[start] = pv[start] ? pv[start] + body['material costs'] : body['material costs'];
                    pv[start] += costperday;
                    for (var day = start + 1; day <= end; ++day) {
                        pv[day] = pv[day] ? pv[day] + costperday : costperday;
                    }
                }
                drawevm();
            }
        };
        xhr.send(null);
    };

    var getComments = function(url, rates, totalcost) {
        var xhr = new XMLHttpRequest();
        xhr.open('GET', url);
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                var comments = JSON.parse(xhr.responseText);
                console.log(comments);
                var pre = 0;
                for (var i = 0; i < comments.length; ++i) {
                    var comment = comments[i];
                    var body = comment.body;
                    body = JSON.parse(body);
                    console.log(body);
                    var day = toDate(comment.created_at);
                    if (!pv[day])
                        pv[day] = 0;
                    var cost = body['material costs'] + rates * body['direct hours'];
                    ac[day] = ac[day] ? ac[day] + cost : cost;
                    //その日のEVだけを求める
                    var progress = (body['measure work in progress'] - pre) * totalcost / 100.;
                    ev[day] = ev[day] ? ev[day] + progress : progress;
                    pre = body['measure work in progress'];
                }
                drawevm();
            }
        };
        xhr.send(null);
    };

    var drawevm = function() {
        //console.log(pv);
        //console.log(ac);
        console.log(ev);

        var cpv = [];//累積
        var cac = [];//累積
        var cev = [];//累積
        var isfirstday = true;
        for (var i in pv) {
            if (isfirstday) {
                cpv[i] = pv[i];
                cac[i] = ac[i] ? ac[i] : 0;
                cev[i] = ev[i] ? ev[i] : 0;
                isfirstday = false;
            } else {
                cpv[i] = cpv[i - 1] + pv[i];
                cac[i] = cac[i - 1] + (ac[i] ? ac[i] : 0);
                cev[i] = cev[i - 1] + (ev[i] ? ev[i] : 0);
            }
        }
        //console.log(cpv);
        //console.log(cac);
        //console.log(cev);

        var urlpv = '';
        var urlac = '';
        var urlev = '';
        var days = '';
        var table = '<table><tr><th></th><th>PV</th><th>AC</th><th>EV</th></tr>';
        for (var i in pv) {
            table += '<tr><td>' + i + '</td><td>' + cpv[i] + '</td><td>' + cac[i] + '</td><td>' + cev[i] + '</td></tr>';
            days += '|' + i,
                    urlpv += cpv[i] + ',';
            urlac += cac[i] + ',';
            urlev += cev[i] + ',';

            url = 'https://chart.googleapis.com/chart'
                    + '?chs=640x300'//チャートのサイズ
                    + '&cht=lc'//折れ線グラフ
                    + '&chds=a'//描画範囲（すべて）
                    + '&chxt=x,y'//軸表示
                    + '&chco=0000ff,ff0000,00ff00'//線の色
                    + '&chdl=PV|AC|EV'//凡例
                    + '&chd=t:' + urlpv + '|' + urlac + '|' + urlev//データ
                    + '&chxl=0:' + days//X軸メモリ
                    + '&chm=d,0000ff,0,-1,15|d,ff0000,1,-1,15|d,00ff00,2,-1,15';//マーク

            url = url.replace(/,\|/g, '|');
            url = url.replace(/,&/g, '&');
        }
        table += '</table>';
        document.getElementById('table').innerHTML = table;
        document.getElementById('evm').setAttribute('src', url);
    };

    var user = location.href.replace(/.*github\.com\/(.*)\/.*/, '$1');
    var repo = location.href.replace(/.*github\.com\/.*\/(.*)/, '$1');

    var myEvm = document.createElement("img");
    myEvm.setAttribute('id', 'evm');
    var myTable = document.createElement("div");
    myTable.setAttribute('id', 'table');

    var myContainer = document.getElementById("js-repo-pjax-container");
    myContainer.parentNode.insertBefore(myTable, myContainer);
    myTable.parentNode.insertBefore(myEvm, myTable);

    var url = 'https://localhost/evm.html?user=' + user + '&repo=' + repo;
    getIssues('https://api.github.com/repos/' + user + '/' + repo + '/issues?per_page=100&state=closed');
})();
