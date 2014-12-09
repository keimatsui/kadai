# -*- coding: utf-8 -*-
#!/usr/bin/env python
import sys, json
from pprint import pprint

#無いとは思うが一応「'」と「\」の対策をしておく
def escape(str):
  return str.replace("'", "''").replace("\\", "\\\\")

for line in sys.stdin:
  try:
    tweet = json.loads(line)
    #pprint(tweet)
    if 'retweeted_status' in tweet:
      #pprint(tweet)

      retweet = tweet['user']['screen_name']#リツイートした人
      retweetedStatus = tweet['retweeted_status']
      retweeted = retweetedStatus['user']['screen_name']#リツイートされた人
      tweet = retweetedStatus['id_str']#ツイートID
      rcount = retweetedStatus['retweet_count']#ツイート回数
      fcount = retweetedStatus['favorite_count']#ファボ数
      idStr = retweetedStatus['user']['id_str']#リツイートされた人のID
      statuses = retweetedStatus['user']['statuses_count']#リツイートされた人のツイート数
      followers = retweetedStatus['user']['followers_count']#リツイートされた人のフォロアー数
      friends = retweetedStatus['user']['friends_count']#リツイートされた人のフレンド数
      profileImage = retweetedStatus['user']['profile_image_url'].replace('_normal.', '.')#リツイートされた人のプロフィール画像（オリジナルサイズ）

      #sys.stderr.write("https://twitter.com/%s/status/%s\n" % (retweeted, tweet))#参考
      #新たに挿入する（すでに挿入されていれば弾かれるから問題ない）
      sys.stdout.write("insert into retweets (retweet,retweeted,tweet,rcount,fcount) values ('%s','%s','%s',%d,%d);\n" % (escape(retweet), escape(retweeted), tweet, rcount, fcount));
      sys.stdout.write("insert into users (id,screenName,statuses,friends,followers,profileImageUrl) values ('%s','%s',%d,%d,%d,'%s');\n" % (idStr, escape(retweeted), statuses, friends, followers, profileImage));
  except ValueError:
    pass
