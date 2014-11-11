#!/usr/bin/python
# coding: UTF-8

import sys, json, requests

#GitHubのログイン情報をファイルから取得する
#TODO:パスワードに「:」を使っているとダメ
tmp = open('github.passwd').readline().rstrip('\n').split(':');
username = tmp[0]
password = tmp[1]
#print >> sys.stderr, username,password

#APIのURLはコマンドライン引数で与える
url = sys.argv[1]

count = 0
while (url is not None):
  print >> sys.stderr, url
  r = requests.get(url, auth=(username, password))
  print >> sys.stderr, r.headers['status'],
  items = r.json()['items'] if 'items' in r.json() else r.json()
  for item in items:
      count = count + 1
      print json.dumps(item)
  if (r.links.has_key('next')):
    url = r.links['next']['url']
  else:
    url = None
  print >> sys.stderr, count, 'items'