# -*- coding: utf-8 -*-

import xml.sax
import sys

class myHandler(xml.sax.ContentHandler):
  inRevision = False
  inContributor = False
  inId = False
  inIp = False
  pageId = ""
  revisionId = ""
  userId = ""
  ip = ""

  def __init__(self):
    xml.sax.ContentHandler.__init__(self)
 
  def startElement(self, name, attrs):
    if name == "revision":
      self.inRevision = True
    elif name == "contributor":
      self.inContributor = True
    elif name == "id":
      self.inId = True
    elif name == "ip":
      self.inIp = True
 
  def endElement(self, name):
    if name == "page":
      self.pageId = ""
    if name == "revision":
      print(self.pageId + "\t" + self.revisionId + "\t" + self.userId + "\t" + self.ip)
      self.inRevision = False
      self.revisionId = ""
      self.userId = ""
      self.ip = ""
    elif name == "contributor":
      self.inContributor = False
    elif name == "id":
      self.inId = False
    elif name == "ip":
      self.inIp = False
 
  def characters(self, content):
    if self.inId == True:
      if self.inContributor == True:
        self.userId = self.userId + content
      elif self.inRevision == True:
        self.revisionId = self.revisionId + content
      else:
        self.pageId = self.pageId + content
    elif self.inIp == True:
        self.ip = self.ip + content
  
def main():
  xml.sax.parse(sys.stdin, myHandler())
 
if __name__ == "__main__":
  main()
