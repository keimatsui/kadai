# -*- coding: utf-8 -*-

import tweepy

consumer_key = ""#引用符の中にconsumer_keyの情報を記述する
consumer_secret = ""#引用符の中にconsumer_secretの情報を記述する

access_token = ""#引用符の中にaccess_tokenの情報を記述する
access_token_secret = ""#引用符の中にaccess_token_secretの情報を記述する

auth = tweepy.OAuthHandler(consumer_key, consumer_secret)
auth.set_access_token(access_token, access_token_secret)
api = tweepy.API(auth)