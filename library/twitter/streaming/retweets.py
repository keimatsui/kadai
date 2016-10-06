# -*- coding: utf-8 -*-
#!/usr/bin/env python
import sys, json
from pprint import pprint

def extractUserProfile(user):
  id = user['id_str']
  screenName = user['screen_name']
  statuses = user['statuses_count']
  friends = user['friends_count']
  followers = user['followers_count']
  lang = user['lang']
  profileImage = user['profile_image_url'].replace('_normal.', '.')#プロフィール画像（オリジナルサイズ）
  sys.stdout.write("insert into users (id,screenName,statuses,friends,followers,lang,profileImageUrl) values (%s,'%s',%d,%d,%d,'%s','%s');\n" % (id, screenName, statuses, friends, followers, lang, profileImage))
  return id

for line in sys.stdin:
  try:
    tweet = json.loads(line)
    #pprint(tweet)
    if 'retweeted_status' in tweet:
      #pprint(tweet)#内容確認（これを有効にして「| less」で実行する）
      
      retweet = extractUserProfile(tweet['user'])#リツイートした人
      
      retweetedStatus = tweet['retweeted_status']
      tweet = retweetedStatus['id_str']#ツイートID
      rcount = retweetedStatus['retweet_count']#ツイート回数
      fcount = retweetedStatus['favorite_count']#ファボ数
      retweeted = extractUserProfile(retweetedStatus['user'])#リツイートされた人

      sys.stdout.write("insert into retweets (id,retweet,retweeted,rcount,fcount) values (%s,%s,%s,%d,%d);\n" % (tweet, retweet, retweeted, rcount, fcount))
      sys.stdout.flush()
  except ValueError:
    pass
