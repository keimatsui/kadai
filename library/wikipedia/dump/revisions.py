# -*- coding: utf-8 -*-

import xml.sax
import sys

class myHandler(xml.sax.ContentHandler):
  inTitle = False #title要素解析中
  title = ""
  revisions = 0

  def __init__(self):
    xml.sax.ContentHandler.__init__(self)
 
  def startElement(self, name, attrs):
    if name == "page": #page要素の開始
      self.title = ""
      self.revisions = 0 #カウンタのリセット
    elif name == "title": #title要素の開始
      self.inTitle = True
    elif name == "revision":
      self.revisions = self.revisions + 1
 
  def endElement(self, name):
    if name == "page": #page要素の終了
      print(self.title + "\t" + str(self.revisions))
    elif name == "title": #title要素の終了
      self.inTitle = False
 
  def characters(self, content):
    if self.inTitle == True:
      self.title = self.title + content #遅いかも
  
def main():
  xml.sax.parse(sys.stdin, myHandler())
 
if __name__ == "__main__":
  main()
