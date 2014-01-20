#!/bin/bash

python api.py "https://api.github.com/repos/${1}/${2}/issues?per_page=100" > ${1}-${2}-openissues.txt

python api.py "https://api.github.com/repos/${1}/${2}/issues?per_page=100&state=closed" > ${1}-${2}-closedissues.txt
