# -*- coding: utf-8 -*-
 
import tweepy
import json
import sys
from auth import api

result = api.rate_limit_status()
#print result#この結果の読み方はhttps://dev.twitter.com/rest/reference/get/application/rate_limit_statusを参照

friendship = result['resources']['friendships']['/friendships/show']
print >> sys.stderr, friendship

apinum = friendship['remaining']
reset = friendship['reset'] + 15

sys.stdout.write('echo "select retweet,retweeted from retweets where checked=FALSE limit %s;" | mysql -utest -ppass --skip-column-names twitter | python friendship.py | mysql -utest -ppass --force twitter\n' % apinum)
sys.stdout.write("reset=%s\n" % reset)
sys.stdout.write("now=`perl -e 'print time'`\n")
sys.stdout.write("t=`expr $reset \- $now`\n")
sys.stdout.write('echo "sleeping ${t}s..."\n')
sys.stdout.write("sleep $t\n")
