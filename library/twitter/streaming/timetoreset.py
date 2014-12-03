# -*- coding: utf-8 -*-
 
import tweepy
import time, calendar
from auth import api

result = api.rate_limit_status()

reset = result['resources']['friendships']['/friendships/show']['reset']
print(reset - calendar.timegm(time.gmtime()))