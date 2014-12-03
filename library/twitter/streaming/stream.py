# -*- coding: utf-8 -*-
 
from tweepy.streaming import StreamListener
from tweepy import OAuthHandler
from tweepy import Stream
from auth import auth
 
class StdOutListener(StreamListener):
    def on_data(self, data):
        if data.startswith("{"):
            print data
        return True
 
    def on_error(self, status):
        print status
 
if __name__ == '__main__':
    stream = Stream(auth, StdOutListener())
#    stream.filter(track = [keyword])#検索する場合
    stream.sample()#ツイートのランダムサンプリングを取得する場合
#    stream.userstream()#タイムラインを取得する場合