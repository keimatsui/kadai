# -*- coding: utf-8 -*-

import sys
import json

for line in sys.stdin:
  tweet = json.loads(line)
  id = tweet['id_str']
  datetime = tweet['created_at']
  name = tweet['user']['screen_name']
  tmp = [id, datetime, name]
  print(','.join(tmp))
