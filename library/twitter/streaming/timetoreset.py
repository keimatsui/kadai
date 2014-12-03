# -*- coding: utf-8 -*-

import tweepy
import time, calendar
from auth import api

result = api.rate_limit_status()

reset = result['resources']['friendships']['/friendships/show']['reset']
s = reset - calendar.timegm(time.gmtime())
if s < 0:
  print 0
else:
  print s