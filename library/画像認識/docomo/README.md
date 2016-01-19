# Docomo Developer Support

## 準備

### 利用申請

1. https://dev.smt.docomo.ne.jp/ でユーザ登録をする。
1. API利用申請をして，API Keyを取得する。

## 実験

APIコンソールで試せるが，modelNameを指定しなければならないというわけで，まったく使えないということがわかった。

`modelName=food`としてりんごの画像で試した結果：

```
{
    "jobId": "194429_osJtnTVrLQ",
    "candidates": [
        {
            "tag": "コロッケ",
            "score": 0.46418124437332153
        },
        {
            "tag": "カツ",
            "score": 0.09920137375593185
        },
        {
            "tag": "ローストビーフ",
            "score": 0.06780944764614105
        },
        {
            "tag": "サーターアンダーギー",
            "score": 0.05268698185682297
        },
        {
            "tag": "パン",
            "score": 0.048711370676755905
        }
    ]
}
```
