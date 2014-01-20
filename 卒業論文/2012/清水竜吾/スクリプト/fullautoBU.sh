if [ ! -d $2 ];
then
git clone https://github.com/$1/$2

cd $2
git log --pretty=format:"%H,%cd" --date=iso --first-parent --no-merges > ../CSV/$2-commits.csv
cd ..
cat CSV/$2-commits.csv | python lineCountScriptCreator.py $2 > $2-count.sh
bash $2-count.sh > $2-count-result.csv 2> ERROR/$2-error.log

fi

rm -f count-result.csv
cp $2-count-result.csv count-result.csv

TITLE=$2

gnuplot <<EOF
set datafile separator ","
set xdata time
set timefmt "%Y-%m-%d %H:%M:%S"
set format x "%y-%m"
set xl "Date Time" font 'Times New Roman,25'
set yl "Lines" font 'Times New Roman,25'
set terminal png size 1680,1050
set lmargin 14
set rmargin 15
set tmargin 5
set border 15 lw 2
set tics font "Times New Roman,20"
set title "$TITLE"
set title font 'Times New Roman,50'
set key outside font 'Times New Roman,20' spacing 1.5
set out "lines.png"
plot 'count-result.csv' using 2:3 with lines lw 2 title 'Total', 'count-result.csv' using 2:4 with lines lw 2 title 'Test'
EOF


rm -f $2-lines.png
mv lines.png $2-lines.png
