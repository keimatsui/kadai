# -*- coding: utf-8 -*- 

import sys,urllib2,re
from HTMLParser import HTMLParser

class MyParser(HTMLParser):
  tableCount = -1
  href = ""
  
  def __init__(self):
    HTMLParser.__init__(self)
  
  def handle_starttag(self,tagname,attribute):
    attrs = dict(attribute)
    if tagname == "table":
      self.tableCount = self.tableCount + 1
    elif tagname == "tr":
      self.href = ""
    elif tagname == "a" and "href" in attrs:
      self.href = "http://ja.wikipedia.org" + attrs['href']
  
  def handle_endtag(self,tagname):
    if tagname == "table":
      self.tableCount = self.tableCount + 1
    if self.tableCount == 0:
      if tagname == "th" or tagname == "td":
        sys.stdout.write(",")
      if tagname == "tr":
        #sys.stdout.write("%s" % self.href)#利用者ページのURL
        sys.stdout.write("\n")
  
  def handle_data(self, data):
    if self.tableCount == 0:
      sys.stdout.write("%s" % re.sub("\(.*\)", "", data).rstrip())

if __name__ == "__main__":
  url = "http://ja.wikipedia.org/wiki/Wikipedia:%E7%B7%A8%E9%9B%86%E5%9B%9E%E6%95%B0%E3%81%AE%E5%A4%9A%E3%81%84%E3%82%A6%E3%82%A3%E3%82%AD%E3%83%9A%E3%83%87%E3%82%A3%E3%82%A2%E3%83%B3%E3%81%AE%E4%B8%80%E8%A6%A7"

  htmldata = urllib2.urlopen(url)
  parser = MyParser()
  parser.feed(htmldata.read())
  
  parser.close()
  htmldata.close()
