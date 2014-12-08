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
      idStr = profileImage = retweetedStatus['user']['id_str']#リツイートされた人のID
      profileImage = retweetedStatus['user']['profile_image_url'].replace('_normal.', '.')#リツイートされた人のプロフィール画像（オリジナルサイズ）
      #sys.stderr.write("https://twitter.com/%s/status/%s\n" % (retweeted, tweet))#参考
      #新たに挿入する（すでに挿入されていれば弾かれるから問題ない）
      sys.stdout.write("insert into retweets (retweet,retweeted,tweet) values ('%s','%s','%s');\n" % (escape(retweet), escape(retweeted), tweet));
      sys.stdout.write("insert into users (id, screenName, profileImageUrl) values ('%s','%s','%s');\n" % (idStr, escape(retweeted), profileImage));
  except ValueError:
    pass
