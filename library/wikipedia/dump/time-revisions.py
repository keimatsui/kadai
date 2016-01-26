# -*- coding: utf-8 -*-

import xml.sax
import sys
import re

class myHandler(xml.sax.ContentHandler):
  reading = {"title":False, "timestamp":False}
  title = ""
  timestamp = ""
  revisions = 0

  def __init__(self):
    xml.sax.ContentHandler.__init__(self)
 
  def startElement(self, name, attrs):
    self.reading[name] = True
 
  def endElement(self, name):
    self.reading[name] = False
    if name == "page":
      print(self.title + "\t" + str(self.revisions))
      self.title = ""
      self.revisions = 0
    elif name == "revision":
      if pattern.match(self.timestamp):
        self.revisions = self.revisions + 1
      self.timestamp = ""
 
  def characters(self, content):
    if self.reading["title"] == True:
      self.title = self.title + content
    elif self.reading["timestamp"] == True:
      self.timestamp = self.timestamp + content

def main():
  xml.sax.parse(sys.stdin, myHandler())
 
if __name__ == "__main__":
  pattern = re.compile(sys.argv[1])
  main()