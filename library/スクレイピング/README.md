# スクレイピング

## 入門

スクレイピングは大きく分けて次の二つがある。

* 静的なHTML解析でよいもの
* 静的なHTML解析ではだめなもの

静的なHTML解析でよいものについては、[Amazonのレビューを取得する例](../amazon/)で説明する。

静的なHTML解析ではだめなものについては、[スクロールしてからページを保存する例](scroll.md)で説明する。

## Selenium

静的なHTML解析ではだめなものは、ブラウザを使って処理する。ブラウザを使うと言っても、操作するのは手（マウス）ではなく、プログラムである。

```bash
sudo apt update
sudo apt install -y chromium-chromedriver
```

スクリーンキャプチャを取るだけならこれでいい。（ちゃんと使いたければ、日本語フォントを設定する必要がある。）

```bash
chromium-browser --headless --screenshot --window-size=1280,1024 'https://www.yahoo.co.jp'
```

プログラムで操作するためには、Seleniumを使う。Anaconda環境なら、次のように導入する。

```bash
conda install -y selenium
```

インタラクティブシェルで試す。

### ブラウザの起動

```python
from selenium import webdriver
from selenium.webdriver.chrome.options import Options

options = Options()
options.add_argument('--headless')#GUIがある環境なら不要（研究室公式仮想マシンにはGUIはないから必要）
options.add_argument('--window-size=1080,1980')

driver = webdriver.Chrome('/usr/lib/chromium-browser/chromedriver', chrome_options = options)
driver.get('http://www.yahoo.co.jp')
driver.page_source
```

### 画面キャプチャ

```python
driver.save_screenshot('/vagrant/test.png')
```

### ブラウザを閉じる

```python
driver.close()
```

## プロキシ

スクレイピングのために、何度も同じURLにアクセスするのは無駄だから、プロキシサーバでキャッシュするといい。

* [Squid](https://github.com/yabukilab/machine/tree/master/squid)
* [cocproxy](cocproxy.md)

## 参考文献

* クジラ飛行机『Pythonによるスクレイピング＆機械学習』（ソシム, 2016）（研究室蔵書）