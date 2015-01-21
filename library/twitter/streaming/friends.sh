#!/usr/bin/env bash
 
while :
do
  echo "select retweet from retweets where not exists (select * from friends where friends.user=retweets.retweet) order by rand() limit 1;" | mysql -utest -ppass --skip-column-names twitter | python friends.py | mysql -utest -ppass --force twitter
  sleep 60
done
