# -*- coding: utf-8 -*-

from auth import api

result = api.search(q='東京')
tweet = result[0]
print(tweet)
