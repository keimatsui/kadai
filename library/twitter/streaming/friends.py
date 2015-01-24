# -*- coding: utf-8 -*-

import tweepy
import json
import sys
from pprint import pprint
from auth import api

for line in sys.stdin:
  userId = line.rstrip()
  sys.stderr.write("checking friends of %s...\n" % userId)
  
  #フレンドを調べたことを記録する。
  sys.stdout.write("update users set friendChecked=true where id=%s;\n" % (userId))
  
  try:
    friends = api.friends_ids(userId)
    for friendId in friends:
      sys.stdout.write("insert into friends (user,friend) values (%s,%s);\n" % (userId, friendId))
    sys.stdout.flush()
  except:
    pass
