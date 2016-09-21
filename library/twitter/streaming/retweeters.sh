#!/usr/bin/env bash
 
while :
do
  echo "select id from retweets where retweetersChecked=false limit 1;" | mysql -utest -ppass --skip-column-names twitter | python retweeters.py | mysql -utest -ppass --force twitter
  sleep 60
done
