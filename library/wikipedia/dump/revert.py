# -*- coding: utf-8 -*-

import xml.sax
import sys

class myHandler(xml.sax.ContentHandler):
  inTitle = False #title要素解析中
  inSha1 = False #sha1要素解析中
  title = ""
  revisions = 0
  sha1 = ""
  sha1s = set() #SHA1を数える（重複なし）

  def __init__(self):
    xml.sax.ContentHandler.__init__(self)
 
  def startElement(self, name, attrs):
    if name == "title": #title要素の開始
      self.inTitle = True
    elif name == "sha1":
      self.inSha1 = True
      
 
  def endElement(self, name):
    if name == "page": #page要素の終了
      revert = self.revisions - len(self.sha1s) #差し戻し回数
      print(self.title + "\t" + str(self.revisions) + "\t" + str(len(self.sha1s)) + "\t" + str(revert))
      self.title = ""
      self.revisions = 0 #カウンタのリセット
      self.sha1s.clear() #SHA1の集合をクリア
    elif name == "title": #title要素の終了
      self.inTitle = False
    elif name == "sha1": #sha1要素の終了
      self.inSha1 = False
      self.sha1s.add(self.sha1)
      self.revisions = self.revisions + 1
      self.sha1 = ""
 
  def characters(self, content):
    if self.inTitle == True:
      self.title = self.title + content #遅いかも
    elif self.inSha1 == True:
      self.sha1 = self.sha1 + content
  
def main():
  xml.sax.parse(sys.stdin, myHandler())
 
if __name__ == "__main__":
  main()
