# -*- coding: utf-8 -*-
 
import json
import sys
import requests
import MySQLdb
from rekognitionAuth import auth

conn = MySQLdb.connect(host="localhost", db="twitter", user="test", passwd="pass", charset="utf8")
cursor = conn.cursor()

for line in sys.stdin:
  args = line.split()
  screenName = args[0]
  image = args[1]
  url = "http://rekognition.com/func/api/?"+auth+"&jobs=face_aggressive_gender_emotion_race_age_glass_mouth_open_wide_eye_closed_mustache_beard_beauty,scene_understanding_3&urls="+image
  result = requests.get(url).text
  #result = "test"
  #print result
  stmt = "update users set rekognition=%s where screenName=%s"
  cursor.execute(stmt,(result, screenName))
  conn.commit()

cursor.close()
conn.close()