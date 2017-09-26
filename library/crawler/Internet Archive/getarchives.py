from pprint import pprint
from selenium import webdriver
import urllib.request
import json
import os
import time
import sys

driver = webdriver.Firefox()
driver.set_window_size(1280, 1024);
history = set()#取得済み

for tmp in sys.stdin:#標準入力から対象を取得する
  url = tmp.strip()#改行を削除する
  #for year in range(1996,2018):
  for year in range(1996,1997):#テスト用
    #for month in range(1,13):
    for month in range(1,3):#テスト用
      #API問い合わせ（参照：https://archive.org/help/wayback_api.php）
      apiTemplate = 'http://archive.org/wayback/available?url={0}&timestamp={1}{2:02d}01'
      api = apiTemplate.format(url, year, month)
      #print(api)
      
      with urllib.request.urlopen(api) as res:#問い合わせ
        jsonstr = res.read()
        #print(jsonstr)
        result = json.loads(jsonstr)
        #pprint(result)
        
        if result['archived_snapshots']['closest']['available']:#利用可能なら
          archiveUrl = result['archived_snapshots']['closest']['url']
          print(archiveUrl)
          if not(archiveUrl in history):#未取得なら
            try:
              driver.get(archiveUrl)#取得する
            except Exception as excp:
              print(excp)
            time.sleep(30)#ページ読み込み終了を待つ？
            
            history.add(archiveUrl)
            
            label = archiveUrl[27:33]#西暦と月の部分だけ取り出す
            print(label)
            
            #フォルダに分ける場合
            #if not os.path.isdir(label):#フォルダがなければ作る
            #  os.mkdir(label)
            #filename = '{0}/{0}_{1}.png'.format(label, url.replace(':', '%3a').replace('/', '%2f'))
            
            #フォルダに分けない場合
            if not os.path.isdir('img'):
              os.mkdir('img')
            filename = 'img/{0}_{1}.png'.format(label, url.replace(':', '%3a').replace('/', '%2f'))
            
            print(filename)
            driver.save_screenshot(filename)
