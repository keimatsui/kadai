#!/usr/bin/env bash
 
while :
do
  echo "select retweet,retweeted from retweets where checked=FALSE limit `python remainingapi.py`;" | mysql -utest -ppass --skip-column-names twitter | python friendship.py | mysql -utest -ppass twitter
  s=`python timetoreset.py`
  echo "sleeping ${s}s..."
  sleep $s
done