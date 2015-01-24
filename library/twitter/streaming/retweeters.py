# -*- coding: utf-8 -*-

import tweepy
import json
import sys
from pprint import pprint
from auth import api

for line in sys.stdin:
  tweetId = line.rstrip()
  sys.stderr.write("checking retweeters of %s...\n" % tweetId)
  
  #リツイートした人を調べたことを記録する。
  sys.stdout.write("update retweets set retweetersChecked=true where id=%s;\n" % (tweetId))
  
  try:
    retweeters = api.retweets(tweetId, 100)
    for t in retweeters:
      #このオブジェクトの中身がわかりにくい。（PyDevのデバッガで調べた。）
      user = t.user
      userId = user.id_str
      screenName = user.screen_name
      statuses = user.statuses_count
      friends = user.friends_count
      followers = user.followers_count
      profileImage = user.profile_image_url.replace('_normal.', '.')#プロフィール画像（オリジナルサイズ）
      
      sys.stdout.write("insert into users (id,screenName,statuses,friends,followers,profileImageUrl) values (%s,'%s',%d,%d,%d,'%s');\n" % (userId, screenName, statuses, friends, followers, profileImage))#これは重複エラーになるはず
      sys.stdout.write("insert into retweeters (tweetId,retweet) values (%s,%s);\n" % (tweetId, userId))
    sys.stdout.flush()
  except:
    pass
