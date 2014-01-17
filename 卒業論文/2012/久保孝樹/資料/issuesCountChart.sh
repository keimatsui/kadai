#!/bin/bash
./jq '.created_at' ${1}-${2}-openissues.txt | awk '{ printf("%s open\n", $0); }' > ${1}-${2}-issues.tmp
./jq '.created_at,.closed_at' ${1}-${2}-closedissues.txt | awk '{ if (NR % 2 == 1) printf("%s open\n", $0); else printf("%s close\n", $0); }' >> ${1}-${2}-issues.tmp

sort ${1}-${2}-issues.tmp > ${1}-${2}-issues.txt

awk 'BEGIN { openissues=0; closedissues=0; } $2=="open" { openissues++; } $2=="close" { closedissues++; } { printf("%s,%d,%d\n", $1, openissues, closedissues) }' ${1}-${2}-issues.txt > ${1}-${2}-issues.csv

rm -f issues.csv
cp ${1}-${2}-issues.csv issues.csv

gnuplot issuesCountChart.pl

rm -f ${1}-${2}-issuesCountChart.png
mv issuesCountChart.png ${1}-${2}-issuesCountChart.png
