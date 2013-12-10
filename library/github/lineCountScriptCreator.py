# -*- coding: utf-8 -*-
from datetime import datetime
from dateutil.parser import parse
from dateutil import tz
import sys

myProject = sys.argv[1]
for line in sys.stdin:
  x = line.split(',')
  myHash = x[0]
  #git logの結果はタイムゾーンがばらばら。すべてUTCに統一する。
  myDate = datetime.strftime(parse(x[1]).astimezone(tz.gettz('UTC')), '%Y-%m-%d %H:%M:%S')
  print("cd %s") % (myProject)
  print("git checkout %s 2> /dev/null") % (myHash)
  print("cd ..")
  print("echo %s,%s,$(cat $(find %s -type f) | wc -l),$(cat $(find %s/test -type f) | wc -l)") % (myHash, myDate, myProject, myProject);
