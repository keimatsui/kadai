#!/usr/bin/env bash
 
while :
do
  echo "update retweets set checked=TRUE where checked=FALSE and exists (select * from follows where ((follow=retweet) and (followed=retweeted)) or ((follow=retweeted) and (followed=retweet)));" | mysql -uroot -ppass twitter
  python checkfriendships.py | bash
done