#!/bin/bash
./jq '.created_at' ${1}-${2}-openissues.txt | awk '{ printf("%s open\n", $0); }' > issues.tmp
./jq '.created_at,.closed_at' ${1}-${2}-closedissues.txt | awk '{ if (NR % 2 == 1) printf("%s open\n", $0); else printf("%s close\n", $0); }' >> issues.tmp

sort issues.tmp > issues.txt

awk 'BEGIN { openissues=0; closedissues=0; } $2=="open" { openissues++; } $2=="close" { closedissues++; } { printf("%s,%d,%d\n", $1, openissues, closedissues) }' issues.txt > issues.csv

gnuplot issuesCountChart.pl

rm -f ${1}-${2}-issuesCountChart.png
mv issuesCountChart.png ${1}-${2}-issuesCountChart.png
