# -*- coding: utf-8 -*-

import xml.sax
import sys

class myHandler(xml.sax.ContentHandler):
  #読んでいるタグを記録するのに辞書を使う。
  #こういう方法は，ある要素の中に同じ名前の要素が出てくるとうまく行かない。
  reading = {"title":False} #読んでいるかどうかをチェックするタグ
  title = ""

  def __init__(self):
    xml.sax.ContentHandler.__init__(self)

  #開始タグに出会ったときの処理
  def startElement(self, name, attrs):
    self.reading[name] = True #読んでいる

  #終了タグに出会ったときの処理
  def endElement(self, name):
    self.reading[name] = False #読んでいない
    if name == "title":
      print(self.title)
      self.title = ""

  #内容の処理
  def characters(self, content):
    if self.reading["title"] == True:
      self.title = self.title + content
  
def main():
  xml.sax.parse(sys.stdin, myHandler())
 
if __name__ == "__main__":
  main()
