#!/bin/bash

cd $1
git log --pretty=format:"%H,%cd" --date=iso --first-parent --no-merges > ../$1-commits.csv
cd ..

cat $1-commits.csv | python lineCountScriptCreator.py $1 > $1-count.sh 

bash $1-count.sh > $1-count-result.csv 2> $1-error.log

rm -f count-result.csv
cp $1-count-result.csv count-result.csv

gnuplot lineCounter.pl

rm -f $1-lines.png
mv lines.png $1-lines.png
