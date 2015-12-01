# -*- coding: utf-8 -*-

import xml.sax
import sys

class myHandler(xml.sax.ContentHandler):
  reading = {"title":False, "sha1":False}
  title = ""
  revisions = 0
  sha1 = ""
  sha1s = set() #SHA1を数える（重複なし）

  def __init__(self):
    xml.sax.ContentHandler.__init__(self)
 
  def startElement(self, name, attrs):
    self.reading[name] = True
 
  def endElement(self, name):
    self.reading[name] = False
    if name == "page":
      revert = self.revisions - len(self.sha1s) #差し戻し回数
      print(self.title + "\t" + str(self.revisions) + "\t" + str(len(self.sha1s)) + "\t" + str(revert))
      self.title = ""
      self.revisions = 0
      self.sha1s.clear() #SHA1の集合をクリア
    elif name == "sha1":
      self.revisions = self.revisions + 1
      self.sha1s.add(self.sha1)
      self.sha1 = ""
 
  def characters(self, content):
    if self.reading["title"] == True:
      self.title = self.title + content
    elif self.reading["sha1"] == True:
      self.sha1 = self.sha1 + content
  
def main():
  xml.sax.parse(sys.stdin, myHandler())
 
if __name__ == "__main__":
  main()
