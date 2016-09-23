# -*- coding: utf-8 -*-

from auth import api
import json

result = api.search(q='東京')
for tweet in result:
  print(json.dumps(tweet._json))
