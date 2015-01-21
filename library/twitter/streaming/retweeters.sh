#!/usr/bin/env bash
 
while :
do
  echo "select id from retweets where not exists (select * from retweeters where retweeters.tweetId=retweets.id) limit 1;" | mysql -utest -ppass --skip-column-names twitter | python retweeters.py | mysql -utest -ppass --force twitter
  sleep 60
done
