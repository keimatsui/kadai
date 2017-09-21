#!/usr/bin/env bash
 
while :
do
  python stream.py | python retweets.py | mysql -utest -ppass --force twitter
  sleep 300
done
