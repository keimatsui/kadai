#実行例：

from selenium import webdriver
from selenium.webdriver.chrome.options import Options
import time
import sys
import os

scrollLimit = 50

options = Options()
options.add_argument('--headless')#GUIがある環境なら不要（研究室公式仮想マシンにはGUIはないから必要）
options.add_argument('--disable-gpu')
options.add_argument('--window-size=1080,1980')
driver = webdriver.Chrome('/usr/lib/chromium-browser/chromedriver', chrome_options = options)

for line in sys.stdin:
    url = line.strip()
    sys.stderr.write(url + '\n')

    try:
        content = ''
        driver.get(url)

        scroll = 0
        pageLenBefore = 0

        while True:
            content = driver.page_source
            pageLenAfter = len(content)
            if pageLenAfter == pageLenBefore or scrollLimit < scroll:
                break
            pageLenBefore = pageLenAfter
            driver.execute_script('window.scrollTo(0, document.body.scrollHeight);')
            scroll += 1;
            sys.stderr.write('scrolling: {}\n'.format(scroll))
            time.sleep(5)
    except Exception as e:
        sys.stderr.write(e.message + '\n')
    finally:
        print(content)

driver.close()
