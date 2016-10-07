#!/usr/bin/env bash
 
while :
do
  echo "select retweet from retweets join users on users.id=retweets.retweet where users.friendsChecked=false limit 1;" | mysql -utest -ppass --skip-column-names twitter | python friends.py | mysql -utest -ppass --force twitter
  sleep 60
done
