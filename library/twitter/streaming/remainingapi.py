# -*- coding: utf-8 -*-
 
import tweepy
import json
import sys
from auth import api

result = api.rate_limit_status()
#print result#この結果の読み方はhttps://dev.twitter.com/rest/reference/get/application/rate_limit_statusを参照

print result['resources']['friendships']['/friendships/show']['remaining']
#print 10