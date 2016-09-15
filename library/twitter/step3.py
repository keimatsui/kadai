# -*- coding: utf-8 -*-

from auth import api
import json

result = api.search(q='東京')
tweet = result[0]
print(json.dumps(tweet._json))
