# -*- coding: utf-8 -*-
from datetime import datetime
from dateutil.parser import parse
from dateutil import tz
import sys

myProject = sys.argv[1]
print("rm %s-error.log") % (myProject)

for line in sys.stdin:
  x = line.split(',')
  myHash = x[0]
  #git logの結果はタイムゾーンがばらばら。すべてUTCに統一する。
  myDate = datetime.strftime(parse(x[1]).astimezone(tz.gettz('UTC')), '%Y-%m-%d %H:%M:%S')
  print("echo %s >&2") % (myHash)
  print("cd %s") % (myProject)
  print("git checkout -f %s 2>> ../%s-error.log") % (myHash, myProject)
  print("cd ..")
  print("if [ -e %s/test ]; then") % (myProject)
  print(" echo %s,%s,$(grep -I '' -r '%s/'* | wc -l),$(grep -I '' -r '%s/test/'* | wc -l)") % (myHash, myDate, myProject, myProject)
  print("else")
  print(" echo %s,%s,$(grep -I '' -r '%s/'* | wc -l),0") % (myHash, myDate, myProject)
  print("fi")
  