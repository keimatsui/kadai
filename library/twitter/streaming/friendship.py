# -*- coding: utf-8 -*-
 
import tweepy
import json
import sys
from auth import api

#無いとは思うが一応「'」と「\」の対策をしておく
def escape(str):
  return str.replace("'", "''").replace("\\", "\\\\")

for line in sys.stdin:
  users = line.split()
  user1 = escape(users[0])
  user2 = escape(users[1])
  #sys.stderr.write("[%s],[%s]\n" % (user1, user2))
  sys.stderr.write(".")
  try:
    friendships = api.show_friendship(source_screen_name=user1, target_screen_name=user2)
    #print(friendships)#これで状況がわかる
    sys.stdout.write("update retweets set checked=TRUE where (retweet='%s' and retweeted='%s') or (retweet='%s' and retweeted='%s');\n" % (user1, user2, user2, user1))
    if friendships[0].following:
      sys.stdout.write("insert into follows (follow,followed) values ('%s','%s');\n" % (user1, user2))
    if friendships[0].followed_by:
      sys.stdout.write("insert into follows (follow,followed) values ('%s','%s');\n" % (user2, user1))
    sys.stdout.flush()
  except:
    pass
sys.stderr.write("\n")
