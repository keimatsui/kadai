# -*- coding: utf-8 -*-

import xml.sax
import sys

class myHandler(xml.sax.ContentHandler):
  inTitle = False #title要素解析中
  title = ""

  def __init__(self):
    xml.sax.ContentHandler.__init__(self)
 
  def startElement(self, name, attrs):
    if name == "title": #title要素の開始
      self.inTitle = True
 
  def endElement(self, name):
    if name == "title": #title要素の終了
      self.inTitle = False
      print(self.title)
      self.title = ""
 
  def characters(self, content):
    if self.inTitle == True:
      self.title = self.title + content
  
def main():
  xml.sax.parse(sys.stdin, myHandler())
 
if __name__ == "__main__":
  main()
