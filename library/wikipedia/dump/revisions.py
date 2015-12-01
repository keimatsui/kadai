# -*- coding: utf-8 -*-

import xml.sax
import sys

class myHandler(xml.sax.ContentHandler):
  reading = {"title":False}
  title = ""
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
      self.revisions = self.revisions + 1
 
  def characters(self, content):
    if self.inTitle == True:
      self.title = self.title + content
  
def main():
  xml.sax.parse(sys.stdin, myHandler())
 
if __name__ == "__main__":
  main()
