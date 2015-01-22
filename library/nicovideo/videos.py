# -*- coding: utf-8 -*-
#!/usr/bin/env python
import sys, json,codecs
from pprint import pprint

for line in sys.stdin:
#  try:
    videos = json.loads(line)
    if 'values' in videos:
      for video in videos['values']:
        if 'cmsid' in video:
          #print(video)
          sys.stdout.write("%s,%s,%d,%s\n" % (video['start_time'],video['cmsid'],video['view_counter'],video['title']))
#  except ValueError:
#    pass
