# -*- coding: utf-8 -*-

import xml.sax
import sys

class myHandler(xml.sax.ContentHandler):
  reading = {"id":False, "contributor":False, "revision":False, "ip":False}
  pageId = ""
  revisionId = ""
  userId = ""
  ip = ""

  def __init__(self):
    xml.sax.ContentHandler.__init__(self)
 
  def startElement(self, name, attrs):
    self.reading[name] = True
 
  def endElement(self, name):
    self.reading[name] = False
    if name == "page":
      self.pageId = ""
    elif name == "revision":
      print(self.pageId + "\t" + self.revisionId + "\t" + self.userId + "\t" + self.ip)
      self.revisionId = ""
      self.userId = ""
      self.ip = ""
 
  def characters(self, content):
    if self.reading["id"] == True:
      if self.reading["contributor"] == True:
        self.userId = self.userId + content
      elif self.reading["revision"] == True:
        self.revisionId = self.revisionId + content
      else:
        self.pageId = self.pageId + content
    elif self.reading["ip"] == True:
        self.ip = self.ip + content
  
def main():
  xml.sax.parse(sys.stdin, myHandler())
 
if __name__ == "__main__":
  main()
